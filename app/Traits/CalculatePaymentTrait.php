<?php

namespace App\Traits;

use App\Enums\CouponType;
use App\Models\SpecificPrice;
use App\Database\Models\Product;
use App\Models\ProductAttribute;
use App\Database\Models\Variation;
use Illuminate\Support\Facades\DB;
use App\Exceptions\MarvelException;
use App\Models\Category;
use App\Models\SiteConfiguration;

trait CalculatePaymentTrait
{
    use WalletsTrait;

    public function calculateSubtotal($cartItems)
    {
        if (!is_array($cartItems)) {
            throw new MarvelException(CART_ITEMS_NOT_FOUND);
        }
        $subtotal = 0;
        try {
            foreach ($cartItems as $item) {

                if (isset($item['product_attribute_id'])) {
                    $variation = ProductAttribute::findOrFail($item['product_attribute_id']);
                    $variation['is_product_attribute'] = true;
                    $product = Product::findOrFail($item['product_id']);
                    $subtotal += $this->calculateEachItemVariationTotal($variation, $product, $item);
                } else {
                    $product = Product::findOrFail($item['product_id']);
                    $subtotal += $this->calculateEachItemTotal($product, $item['order_quantity']);
                }

            }

            return $subtotal;
        } catch (\Throwable $th) {
            dd($th);
            throw new MarvelException(NOT_FOUND);
        }
    }

    public function calculateDiscount($coupon, $subtotal,$delivery_charge)
    {
        if ($coupon->id) {
            if($coupon->min_amount > $subtotal){
                // throw new \Exception('Order amount is less than minimum discount amount');
                // abort(200, json_encode(['error'=>'discount amount is less than minimum amount']), ['Content-Type: application/json']);
                 return ['status'=>false,'message'=>'Order amount is less than minimum coupon discount'];
            }
            $shipping_amount = $delivery_charge;
            if(isset($coupon->free_shipping) && 1 == $coupon->free_shipping) {
                $shipping_amount = 0;
            }
            // $total_amount = $shipping_amount + $subtotal;
            $total_amount = $subtotal;

            if ($coupon->discount_type === CouponType::PERCENTAGE_COUPON) {
                $discount = $total_amount * ($coupon->discount / 100);
                return ['status'=>true,'message'=>'discount applied','discount'=>$discount];
            } else if ($coupon->discount_type === CouponType::AMOUNT) {
                return ['status'=>true,'message'=>'discount applied','discount'=>$coupon->discount];

            }
        } else {
            return ['status'=>false,'message'=>'discount not applied','discount'=>0];
        }
    }


    public function calculateEachItemTotal($item, $quantity)
    {
        $total = 0;
        if ($item->sale_price) {
            $total += $item->sale_price * $quantity;
        } else {
            $total += $item->retail_price * $quantity;
        }
        return $total;
    }
    public function calculateEachItemVariationTotal($variation, $product, $item)
    {
        $total = 0;
        $customer_specific_prices = '';
        $item['order_quantity'] = (isset($item['quantity'])) ? $item['quantity'] : $item['order_quantity'];
        $total += $variation->price * $item['order_quantity'];
        // if(isset(auth()->user()->id)){
        //     $customer_specific_prices = $specific_price = SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->where('customer_id',auth()->user()->id)->exists() ? SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->where('customer_id',auth()->user()->id)->first() : SpecificPrice::where('product_id',$product->id)->where('product_attribute_id',0)->first();

        // }
        // if($customer_specific_prices){
        //    $specific_price =  $customer_specific_prices;
        // }
        // else{
        //     $specific_price = SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->exists() ? SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->first() : SpecificPrice::where('product_id',$product->id)->where('product_attribute_id',0)->first();
        // }
        // $specific_price = SpecificPrice::where(['product_id'=>$product->id,'product_attribute_id'=>$item['product_attribute_id']])->first();
        // $specific_price = SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->exists() ? SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->first() : SpecificPrice::where('product_id',$product->id)->where('product_attribute_id',0)->first();
        // if (isset($specific_price) && $item['order_quantity'] >= $specific_price->from_quantity ) {
        //     if((!isset($specific_price->from) && !isset($specific_price->to)) || ($specific_price->from <= date('Y-m-d H:i:s') && $specific_price->to >= date('Y-m-d H:i:s'))){
        //         if ($specific_price->reduction_type == 'percentage') {
        //             $discounted_amount =  ($variation->price - (($variation->price * $specific_price->reduction)/100));
        //         }

        //         else if($specific_price->reduction_type == "dollar"){
        //             $discounted_amount = ($variation->price) - ($specific_price->reduction);
        //             if($discounted_amount < 0){
        //                 $discounted_amount = 0;
        //             }
        //         }
        //         // $total += $discounted_amount * $item['order_quantity'];
        //         $total += $variation->price * $item['order_quantity'];
        //     }
        //     else{
        //         $total += $variation->price * $item['order_quantity'];
        //     }
        // } else {
        //     $total += $variation->price * $item['order_quantity'];
        // }
        return $total;
    }

    public function calculateEachItemDiscountVariationTotal($variation, $product, $item){
        $total = 0;
        $customer_specific_prices = '';
        if(isset(auth()->user()->id)){
            $customer_specific_prices = $specific_price = SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->where('customer_id',auth()->user()->id)->exists() ? SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->where('customer_id',auth()->user()->id)->first() : SpecificPrice::where('product_id',$product->id)->where('product_attribute_id',0)->first();

        }
        if($customer_specific_prices){
           $specific_price =  $customer_specific_prices;
        }
        else{
            $specific_price = SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->exists() ? SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->first() : SpecificPrice::where('product_id',$product->id)->where('product_attribute_id',0)->first();
        }
        $specific_price = SpecificPrice::where(['product_id'=>$product->id,'product_attribute_id'=>$item['product_attribute_id']])->first();
        $specific_price = SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->exists() ? SpecificPrice::where('product_attribute_id',$item['product_attribute_id'])->first() : SpecificPrice::where('product_id',$product->id)->where('product_attribute_id',0)->first();
        if (isset($specific_price) && $item['order_quantity'] >= $specific_price->from_quantity ) {
            if((!isset($specific_price->from) && !isset($specific_price->to)) || ($specific_price->from <= date('Y-m-d H:i:s') && $specific_price->to >= date('Y-m-d H:i:s'))){
                if ($specific_price->reduction_type == 'percentage') {
                   // $discounted_amount =  ($variation->price - (($variation->price * $specific_price->reduction)/100));
                    $discounted_amount =  (($variation->price * $specific_price->reduction)/100);
                }

                else if($specific_price->reduction_type == "dollar"){
                    //$discounted_amount = ($variation->price) - ($specific_price->reduction);
                    $discounted_amount =$specific_price->reduction;
                    if($discounted_amount < 0){
                        $discounted_amount = 0;
                    }
                }
                $total += $discounted_amount * $item['order_quantity'];
                //$total += $variation->price * $item['order_quantity'];
            }
            else{
                //$total += $variation->price * $item['order_quantity'];
            }
        } else {
            //$total += $variation->price * $item['order_quantity'];
        }
        return $total;
    }

    public function getUserWalletAmount($user)
    {
        $amount = 0;
        $wallet = $user->wallet;
        if (isset($wallet->available_points)) {
            $amount =  $this->walletPointsToCurrency($wallet->available_points);
        }
        return $amount;
    }

    public function calculateAssemblyCharges($cartItems){
        if (!is_array($cartItems)) {
            throw new MarvelException(CART_ITEMS_NOT_FOUND);
        }
        $subtotal = 0;
        try {
            foreach ($cartItems as $item) {

                $product = Product::findOrFail($item['product_id']);
                $subtotal += $product->assembly_charges;
            }

            return $subtotal;
        } catch (\Throwable $th) {
            dd($th);
            throw new MarvelException(NOT_FOUND);
        }
    }

    public function calculateProductDiscount($cartItems){
        if (!is_array($cartItems)) {
            throw new MarvelException(CART_ITEMS_NOT_FOUND);
        }
        $subtotal = 0;
        try {
            foreach ($cartItems as $item) {

                if (isset($item['product_attribute_id'])) {
                    $variation = ProductAttribute::findOrFail($item['product_attribute_id']);
                    $variation['is_product_attribute'] = true;
                    $product = Product::findOrFail($item['product_id']);
                    $subtotal += $this->calculateEachItemDiscountVariationTotal($variation, $product, $item);
                } else {
                    $product = Product::findOrFail($item['product_id']);
                    $subtotal += $this->calculateEachItemTotal($product, $item['order_quantity']);
                }

            }

            return $subtotal;
        } catch (\Throwable $th) {
            dd($th);
            throw new MarvelException(NOT_FOUND);
        }
    }

    public function getGSTRates($product){

        $cgst = $sgst = 0;

        // Get product GST rate
        if (isset($product->cgst_rate) && $product->cgst_rate > 0) {
            $cgst = $product->cgst_rate;
        }

        if (isset($product->sgst_rate) && $product->sgst_rate > 0) {
            $sgst = $product->sgst_rate;
        }

        // If CGST or SGST is not set or <= 0, check default category
        if (($cgst <= 0 || $sgst <= 0) && $product->default_category > 0) {
            $category = Category::find($product->default_category);
            if ($category && !empty($category)) {
                if ($cgst <= 0) {
                    $cgst = $category->cgst_rate;
                }
                if ($sgst <= 0) {
                    $sgst = $category->sgst_rate;
                }
            }
        }

        // If CGST or SGST is still not set or <= 0, get from site configuration
        if ($cgst <= 0) {
            $cgst = SiteConfiguration::where('varname', 'CGST_MAX_PERCENTAGE')->value('value') ?? 0;
        }
        if ($sgst <= 0) {
            $sgst = SiteConfiguration::where('varname', 'SGST_MAX_PERCENTAGE')->value('value') ?? 0;
        }

        return [
            'cgst' => (int)$cgst,
            'sgst' => (int)$sgst,
        ];
    }

    public function calculateWithGST($cartItems){
        if (!is_array($cartItems)) {
            throw new MarvelException(CART_ITEMS_NOT_FOUND);
        }

        $CGSTTotal = 0;
        $SGSTTotal = 0;
        $CGSTRate = 0;
        $SGSTRate = 0;
        $count = 0;

        try {
            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);
                if(!$product){
                    continue;
                }

                $item['quantity'] = (isset($item['order_quantity'])) ? $item['order_quantity'] : $item['quantity'];
                $item['unit_price'] = (isset($item['discounted_price'])) ? $item['discounted_price'] : $item['unit_price'];

                $gstarr = $this->getGSTRates($product);
                $CGSTTotal += ($item['unit_price'] * $item['quantity'] * $gstarr['cgst']) / 100;
                $SGSTTotal += ($item['unit_price'] * $item['quantity'] * $gstarr['sgst']) / 100;

                $CGSTRate += $gstarr['cgst'];
                $SGSTRate += $gstarr['sgst'];

                $count++;
            }

            $averageCGST = $count > 0 ? $CGSTRate / $count : 0;
            $averageSGST = $count > 0 ? $SGSTRate / $count : 0;

            return [
                'Total_CGST' => $CGSTTotal,
                'Total_SGST' => $SGSTTotal,
                'Average_CGST' => $averageCGST,
                'Average_SGST' => $averageSGST
            ];
        } catch (\Throwable $th) {
            dd($th);
            throw new MarvelException(NOT_FOUND);
        }
    }

    public function calculateProductWithGST($product){
        if (!$product) {
            throw new MarvelException(NOT_FOUND);
        }

        $productDetail = Product::find($product['product_id']);

        $gstarr = $this->getGSTRates($productDetail);
        //* $product['quantity']
        $CGSTTotal = ($product['unit_price'] * $gstarr['cgst']) / 100;
        $SGSTTotal = ($product['unit_price'] * $gstarr['sgst']) / 100;

        $CGSTRate = $gstarr['cgst'];
        $SGSTRate = $gstarr['sgst'];

        return [
            'Total_CGST' => $CGSTTotal,
            'Total_SGST' => $SGSTTotal,
            'Average_CGST' => $CGSTRate,
            'Average_SGST' => $SGSTRate
        ];
    }

    public function calculatePaidTotal($request){
        return $request['total'] + $request['assembly_charges'] + $request['total_cgst'] + $request['total_sgst'] - $request['product_discount'] -  $request['discount'];
    }

    public function calculateProductDetailGSTCalculation($product_id,$price){
        $product = Product::find($product_id);
        if(!$product){
            return [];
        }

        $gstarr = $this->getGSTRates($product);
        $CGSTTotal = ($price * $gstarr['cgst']) / 100;
        $SGSTTotal = ($price * $gstarr['sgst']) / 100;

        $CGSTRate = $gstarr['cgst'];
        $SGSTRate = $gstarr['sgst'];

        return [
            'Total_CGST' => $CGSTTotal,
            'Total_SGST' => $SGSTTotal,
            'Average_CGST' => $CGSTRate,
            'Average_SGST' => $SGSTRate
        ];

    }
}
