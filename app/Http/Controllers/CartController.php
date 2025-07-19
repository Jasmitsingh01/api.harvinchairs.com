<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Http\Requests\CartProductRequest;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Database\Models\Product;
use App\Models\ProductAttribute;
use App\Exceptions\MarvelException;
use App\Http\Requests\CartCreateRequest;
use App\Database\Repositories\CartRepository;
use App\Traits\CartTrait;
use App\Traits\CalculatePaymentTrait;

class CartController extends Controller
{
    use CartTrait,CalculatePaymentTrait;
    public $repository;
    public function __construct(CartRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        $user = $request->user();
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && (!isset($request->shop_id) || $request->shop_id === 'undefined')) {
            return $this->repository->paginate($limit);
        }
        if ($this->repository->hasUserPermission($request->user())) {
            //update below code as if multiple product is out of stock then it will show all products in response along with its message

            $errorCount = 0;
            $cart = $this->repository->where('user_id', $request->user()->id)->where('is_confirm', false)->with('cartProducts')->first();
            if($cart && !empty($cart)){
                return $this->getCartResponseWithValidation($cart);
            }
            return (object) $cart;
            // $outOfStockProducts = $cart;
            // if (isset($cart) && $cart != '') {
            //     foreach ($cart->cartProducts as $product) {
            //         $productModel = Product::find($product->product_id);
            //         $productAttribute = ProductAttribute::find($product->product_attribute_id);

            //         $error = false; // Initialize error flag for this product

            //         if (!$productModel || !$productModel->is_active) {
            //             $product->message = 'Product is not Exists.';
            //             $errorCount ++;
            //         } elseif (!$productAttribute || ($productAttribute->out_of_stock == 0 && $productAttribute->quantity < $product->quantity)) {
            //             $product->message = 'Product is out of stock.';
            //             $errorCount ++;
            //         } elseif ($productAttribute->minimum_quantity > $product->quantity) {
            //             $product->message = 'Quantity is less than the minimum required.';
            //             $errorCount ++;
            //         }

            //         // Add the product to the result array, with or without a "message"
            //         $outOfStockProducts['cart_products'] = $product;
            //     }
            // }
            // // dd();
            // if ($errorCount > 0) {
            //     $outOfStockProducts['message'] = 'Validation error.';
            //     return response()->json($outOfStockProducts, 400);
            // }
            // return $cart;

        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CartCreateRequest $request)
    {
        // if ($this->repository->hasUserPermission($request->user())) {
            return $this->repository->storeCart($request);
        // } else {
        //     throw new MarvelException(NOT_AUTHORIZED);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cartId = 1; // Replace with the actual cart ID

        $cartProducts = CartProduct::select('cart_products.*', 'pa.reference_code', 'prod.name')
        ->selectRaw('COALESCE(pa.wholesale_price, 0) + COALESCE(prod.price, 0) as price')
        ->selectRaw('CASE WHEN COALESCE(pa.minimum_quantity, 0) > 0 THEN pa.minimum_quantity ELSE prod.minimum_quantity END as minimal_quantity')
        ->selectRaw("CAST(COALESCE(prod.price, 0) + COALESCE(pa.wholesale_price, 0) AS decimal(18, 2)) - CAST(CAST(COALESCE(prod.price, 0) + COALESCE(pa.wholesale_price, 0) AS decimal(18, 2)) * COALESCE(reduction, 0) / 100 AS decimal(18, 2)) AS DiscountPrice")
        ->selectRaw("cart_products.quantity * (CAST(COALESCE(prod.price, 0) + COALESCE(pa.wholesale_price, 0) AS decimal(18, 2)) - CAST(CAST(COALESCE(prod.price, 0) + COALESCE(pa.wholesale_price, 0) AS decimal(18, 2)) * COALESCE(reduction, 0) / 100 AS decimal(18, 2))) AS TotalPrice")
        ->selectRaw('carts.delivery_address_id AS AddId')
        ->selectRaw('prod.quantity as StockQty')
        ->selectRaw('prod.out_of_stock')
        ->selectRaw("CASE WHEN (prod.quantity >= (CASE WHEN COALESCE(pa.minimum_quantity, 0) > 0 THEN pa.minimum_quantity ELSE prod.minimum_quantity END) OR prod.out_of_stock != 0) THEN 'InStock' ELSE 'OutOfStock' END as StockStatus")
        ->selectRaw("CASE WHEN prod.out_of_stock = 0 THEN 'Deny' ELSE 'Allow' END as IsStockAllow")
        ->selectRaw("COALESCE(
            CASE
                WHEN prod.deleted_at != NULL OR prod.is_active = 0  THEN 'This_product_does_not_exist'
                ELSE
                    CASE WHEN prod.out_of_stock = 0 THEN
                        CASE WHEN COALESCE(prod.quantity, 0) <= 0 THEN 'Out_of_stock'
                        ELSE
                            CASE WHEN cp.quantity > prod.quantity THEN
                                CASEWHEN (CASE WHEN COALESCE(pa.minimum_quantity, 0) > 0 THEN pa.minimum_quantity ELSE prod.minimum_quantity END) > prod.quantity THEN 'out_of_stock_product'
                                ELSE CONCAT('Only ', CAST(prod.quantity AS VARCHAR(10)), 'quantity_available_in_stock')
                                END
                            ELSE
                                CASE WHEN (CASE WHEN COALESCE(pa.minimum_quantity, 0) > 0 THEN pa.minimum_quantity ELSE prod.minimum_quantity END) > prod.quantity THEN 'out_of_stock_product'
                                ELSE
                                    CASE WHEN (CASE WHEN COALESCE(pa.minimum_quantity, 0) > 0 THEN pa.minimum_quantity ELSE prod.minimum_quantity END) > cart_products.quantity THEN CONCAT('Minimum order quantity is ', CAST(prod.minimum_quantity AS VARCHAR(10)))
                                    END
                                END
                            END
                        END
                    END
                END, '') AS Availability1")

            ->join('carts', 'cart_products.cart_id', '=', 'carts.id')
            ->join('products as prod', 'cart_products.product_id', '=', 'prod.id')
            // ->join('product_lang as pl', 'cp.id_product', '=', 'pl.id_product')
            ->join('categories as cat', 'prod.default_category', '=', 'cat.id')
            ->leftJoin('product_attributes as pa', function ($join) {
                $join->on('cart_products.product_id', '=', 'pa.product_id')
                    ->on('cart_products.product_attribute_id', '=', 'pa.id');
            })
            ->leftJoin('specific_prices as psp', function ($join) {
                $join->on('cart_products.product_id', '=', 'psp.product_id');
            })
            ->where('prod.language', 'en')
            ->where('cart_products.cart_id', $cartId)
            ->get();

        return response()->json(['data'=>$cartProducts]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if ($this->repository->hasUserPermission($request->user())) {
            return $this->repository->updateCart($request);
        } else {
            throw new MarvelException(NOT_AUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validateCartProduct(CartProductRequest $request){
        return $this->repository->validatecart($request);
    }
}
