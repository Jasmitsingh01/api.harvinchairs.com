<?php


namespace App\Database\Repositories;

use Exception;
use App\Models\Cart;
use App\Traits\CartTrait;
use App\Models\CartProduct;
use App\Traits\ShippingTrait;
use App\Database\Models\Product;
use App\Models\ProductAttribute;
use App\Database\Models\Settings;
use App\Database\Models\Attribute;
use Illuminate\Support\Facades\DB;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\CalculatePaymentTrait;
use App\Database\Models\AttributeValue;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class CartRepository extends BaseRepository
{
    use ShippingTrait,CartTrait,CalculatePaymentTrait;

    protected $dataArray = [
        'user_id',
        'delivery_address_id',
        'billing_address_id',
        'total',
        'totalUniqueItems',
        'language',
        'isEmpty',
        'is_confirm'
    ];

    public function boot()
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
            //
        }
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Cart::class;
    }

    public function storeCart($request)
    {
        $request['is_confirm'] = false;
        $product_array = $request['products'];
        // Check if the product is active
        $errorProducts = [];
        $errorCount = 0;
        if (!empty($product_array)) {
            if(!isset(Auth::guard('api')->user()->id)){
                foreach ($product_array as $product) {
                    $productModel = Product::find($product['product_id']);
                    $productAttribute = ProductAttribute::find($product['product_attribute_id']);

                    if (!$productModel || !$productModel->is_active) {
                        $product['message'] = 'Product is not Exists.';
                        $errorCount ++;
                    } elseif (!$productAttribute || ($productAttribute->out_of_stock == 0 && $productAttribute->quantity < $product['quantity'])) {
                        $product['message'] = 'Product is out of stock.';
                        $errorCount ++;
                    } elseif ($productAttribute->minimum_quantity > $product['quantity']) {
                        $product['message'] = 'Quantity is less than the minimum required.';
                        $errorCount ++;
                    } elseif ($productAttribute->maximum_quantity > 0 && $productAttribute->maximum_quantity < $product['quantity']) {
                        $product['message'] = 'The quantity exceeds the maximum allowed quantity of '.$productAttribute->maximum_quantity > 0 && $productAttribute->maximum_quantity.'. Please adjust accordingly.';
                        $errorCount ++;
                    }

                    // Add the product to the result array, with or without a "message"
                    // $product['product'] = $productModel;
                    $errorProducts[] = $product;
                }
            }
        }
        else{
            $cart = $this->where('user_id',Auth::guard('api')->user()->id)->where('is_confirm',false)->first();
            if(isset($cart)){
                $cart->cartProducts()->delete();
                $cart->delete();
            }
            return response()->json(['message' => 'Cart deleted successfully.','products' => $request['products']], 200);
        }

        if(!isset(Auth::guard('api')->user()->id)){
            if ($errorCount > 0) {
                return response()->json(['message' => 'Validation error.', 'products' => $errorProducts], 400);
            }
            return $request['products'];
        }
        $request['user_id'] = Auth::guard('api')->user()->id;
        $request['totalUniqueItems'] = count($request['products']);
        $request['products'] = $this->processProducts($request['products']);
        $request['total'] = $this->calculateSubtotal($request['products']);
        $activeCart = $this->where('user_id',$request['user_id'])->where('is_confirm',false)->with('cartProducts')->first();

        if (isset($activeCart) && !empty($activeCart->cartProducts)) {
            foreach ($activeCart->cartProducts as $product) {
                $key = array_search($product->product_id, array_column($request['products'], 'product_id'));
                $attribute_key = array_search($product->product_attribute_id, array_column($request['products'], 'product_attribute_id'));

                if ($key === false || $attribute_key === false) {
                    CartProduct::where([
                        'cart_id' => $activeCart->id,
                        'product_id' => $product->product_id,
                        'product_attribute_id' => $product->product_attribute_id
                    ])->delete();
                }
            }
            foreach ($request['products'] as $product) {
                $exist_product =  CartProduct::where(['cart_id'=>$activeCart->id, 'product_id'=>$product['product_id'],'product_attribute_id'=>$product['product_attribute_id']])->first();
                if (isset($exist_product)) {
                    $exist_product->update($product);
                } else {
                    $product['cart_id'] = $activeCart->id;
                    CartProduct::create($product);
                }
            }
            $current_products = CartProduct::where(['cart_id'=>$activeCart->id])->get()->toArray();
            $request['total'] = $this->calculateSubtotal($current_products);
            $activeCart->update($request->only($this->dataArray));
            $cart = $this->where('user_id',$request['user_id'])->where('is_confirm',false)->with('cartProducts')->first();
        }
       else{
            $cart = $this->create($request->only($this->dataArray));
            if (isset($request['products'])) {
                $cart->cartProducts()->createMany($request['products']);
            }
        }

        //$settings = Settings::first();
        $updatedCart = $this->where('user_id',Auth::guard('api')->user()->id)->where('is_confirm',false)->with(['cartProducts' => function ($query) {
            $query->whereHas('product', function($q){
                // $q->makeHidden(['product_features']);
            });
        }])->first();

        return $this->getCartResponseWithValidation($updatedCart);
        //$updatedCart->shipping_charges =  $this->getFinalShippingCharge($request->total, Auth::guard('api')->user()->id, $request->delivery_address_id, $settings->options['FreeShippingThresholdAmount']);


        // if ($errorCount > 0) {
        //     return response()->json(['message' => 'Validation error.', 'products' => $errorProducts, 'cart' => $updatedCart], 400);
        // }
        // return response()->json(['message' => 'cart added successfully.', 'cart' => $updatedCart], 200);
        //return $updatedCart;
    }

    public function updateCart($request)
    {
        $request['user_id'] = Auth::guard('api')->user()->id;
        $activeCart = $this->where('user_id',$request['user_id'])->where('is_confirm',false)->with('cartProducts')->first();

        if (isset($request['products']) && isset($activeCart)) {
            // foreach ($cart->cartProducts as $product) {
            //     // dd($product->product_id);
            //     $key = array_search($product->product_id, array_column($request['products'], 'product_id'));
            //     if (!$key && $key !== 0) {
            //         CartProduct::findOrFail($product->id)->delete();
            //     }
            // }
            $errorProducts = [];
            $total_price = 0;
            $request['products'] = $this->processProducts($request['products']);
            $request['total'] = $this->calculateSubtotal($request['products']);
            foreach ($request['products'] as $key=>$product) {
                $exist_product =  CartProduct::where(['cart_id'=>$activeCart->id, 'product_id'=>$product['product_id'],'product_attribute_id'=>$product['product_attribute_id']])->first();
                if (isset($exist_product)) {
                    $qty = $product['quantity'] + $exist_product->quantity;
                    $product['quantity'] = $qty;
                    $productModel = Product::find($product['product_id']);
                    if (!$productModel || !$productModel->is_active) {
                        $errorProducts[] = ['product_id' => $product['product_id'], 'product_attribute_id' => $product['product_attribute_id'], 'message' => 'Product is not Exists.'];
                    } else {
                        $productAttribute = ProductAttribute::find($product['product_attribute_id']);
                        if (!$productAttribute || ($productAttribute->out_of_stock == 0 && $productAttribute->quantity < $qty)) {
                            $errorProducts[$key] = ['product_id' => $product['product_id'], 'product_attribute_id' => $product['product_attribute_id'], 'message' => 'Product is out of stock.'];
                        } else if ($productAttribute->minimum_quantity > $qty) {
                            $errorProducts[$key] = ['product_id' => $product['product_id'], 'product_attribute_id' => $product['product_attribute_id'], 'message' => 'Quantity is less than the minimum required.'];
                        }
                    }
                    if(!isset($errorProducts[$key])){
                        $exist_product->update($product);
                    }
                    // $price += $product['unit_price'] * $qty;
                } else {
                    if(!isset($errorProducts[$key])){
                        $product['cart_id'] = $activeCart->id;
                        CartProduct::create($product);
                    }
                }

                $total_price += ($product['quantity'] * $product['unit_price']);
            }

            $current_products = CartProduct::where(['cart_id'=>$activeCart->id])->get()->toArray();
            $request['totalUniqueItems'] = count($current_products);
            $request['total'] = $this->calculateSubtotal($current_products);

            $updatedCart = $activeCart->update([
                'total' => $request['total'],
                'billing_address_id' => $request['billing_address_id'],
                'delivery_address_id' => $request['delivery_address_id']
            ]);
            $updatedCart = $this->where('user_id',Auth::guard('api')->user()->id)->where('is_confirm',false)->with('cartProducts')->first();
            // if (!empty($errorProducts)) {
            //     return response()->json(['message' => 'Validation error.', 'products' => $errorProducts,'cart' => $updatedCart], 400);
            // }
            return $this->getCartResponseWithValidation($updatedCart);
            //$settings = Settings::first();
            //$updatedCart->shipping_charges =  $this->getFinalShippingCharge($request->total, Auth::guard('api')->user()->id, $request->delivery_address_id, $settings->options['FreeShippingThresholdAmount']);
            //return $updatedCart;
        }
        else{
            return response()->json(['success'=>false,'message'=>'Cart not found.'], 400);
        }

    }

    public function validatecart($request){
        try{
            $productModel = Product::find($request->product_id);
            $productAttribute = ProductAttribute::find($request->product_attribute_id);

            $errorCount = 0;
            $message = "Product is valid";
            if (!$productModel || !$productModel->is_active) {
                $message = 'Product is not Exists.';
                $errorCount ++;
            } elseif (!$productAttribute || ($productAttribute->out_of_stock == 0 && $productAttribute->quantity < $request->quantity)) {
                $message = 'Product is out of stock.';
                $errorCount ++;
            } elseif ($productAttribute->minimum_quantity > $request->quantity) {
                $message = 'Quantity is less than the minimum required.';
                $errorCount ++;
            } elseif ($productAttribute->maximum_quantity > 0 && ($productAttribute->maximum_quantity < $request->quantity)) {
                $message = 'The quantity exceeds the maximum allowed quantity of '.$productAttribute->maximum_quantity.'. Please adjust accordingly.';
                $errorCount ++;
            }
            if($errorCount > 0){
                return response()->json(['success'=>false,'message'=>$message], 400);
            }
            return response()->json(['success'=>true,'message'=>$message], 200);

        } catch(\Exception $e){
            throw new \Exception("Internal server error.");
        }
    }
    protected function processProducts($products)
    {
        $mergedProducts = [];

        foreach ($products as $product) {
            $key = "{$product['product_id']}_{$product['product_attribute_id']}";

            if (isset($mergedProducts[$key])) {
                // If the product already exists, update the quantity
                $mergedProducts[$key]['quantity'] += $product['quantity'];
            } else {
                // If the product does not exist, add it to the merged array
                $mergedProducts[$key] = $product;
            }
        }

        // Convert the merged array back to indexed array
        $mergedProducts = array_values($mergedProducts);

        // Perform additional logic (e.g., fetch product attributes) if needed
        foreach ($mergedProducts as $key => $product) {
            try {
                if (isset($product['product_attribute_id'])) {
                    $prodAttribute = ProductAttribute::find($product['product_attribute_id']);
                    $mergedProducts[$key]['base_price'] = $prodAttribute->price ? $prodAttribute->price : 0;
                    $mergedProducts[$key]['unit_price'] = $prodAttribute->discounted_price ? ($prodAttribute->discounted_price->discounted_price) : $prodAttribute->price;
                }
            } catch (Exception $e) {
                throw new MarvelException(NOT_FOUND);
            }
        }
        return $mergedProducts;
    }
}
