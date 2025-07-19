<?php

namespace App\Traits;

use App\Database\Models\Address;
use App\Database\Models\Product;
use App\Models\PostcodeRegion;
use App\Models\ProductAttribute;
use App\Models\SiteConfiguration;


trait CartTrait
{
    public function getCartResponseWithValidation($cart){
        $outOfStockProducts = $cart;
        $errorCount = 0;
        if (isset($cart) && !empty($cart)) {
            if(isset($cart->delivery_address_id))
            {
                $postcodeRegions = false;
                //get delivery address
                $deliveryAddress = Address::find($cart->delivery_address_id);
                $deliveryAddress->postal_code = $deliveryAddress->postal_code ? $deliveryAddress->postal_code : '';

                //get postcode_regions
                if($deliveryAddress->postal_code){
                    $postcodeRegions = PostcodeRegion::where('postcode', 'like', '%'.$deliveryAddress->postal_code.'%')->exists();
                }
                if(!$postcodeRegions){

                    $outOfStockProducts['message'] = 'Delivery address is not in serviceable area.';
                    $errorCount ++;
                }
            }
            $cartItems =  $cart->cartProducts->toArray();
            $GSTcalculation =  $this->calculateWithGST($cartItems);
            $cart->gst_calculation = $GSTcalculation;
            foreach ($cart->cartProducts as $product) {
                $productModel = Product::find($product->product_id);
                $productAttribute = ProductAttribute::find($product->product_attribute_id);
                if($productModel){
                    $GSTProductcalculation =  $this->calculateProductWithGST($product);
                    // $product->price_without_gst = $productAttribute->price_without_gst;
                    // $product->total_tax = $productAttribute->product_total_tax;
                    // $product->gst_percentage =$productAttribute->gst_percentage;
                    $product->gst_calculation = $GSTProductcalculation;
                }
                $error = false; // Initialize error flag for this product
                //dd($productAttribute->maximum_quantity, $product->quantity);
                if (!$productModel || !$productModel->is_active) {
                    $product->message = 'Product is not Exists.';
                    $errorCount ++;
                } elseif (!$productAttribute || ($productAttribute->out_of_stock == 0 && $productAttribute->quantity < $product->quantity)) {
                    $product->message = 'Product is out of stock.';
                    $errorCount ++;
                } elseif ($productAttribute->minimum_quantity > $product->quantity) {
                    $product->message = 'Quantity is less than the minimum required.';
                    $errorCount ++;
                } elseif ($productAttribute->maximum_quantity > 0 && $productAttribute->maximum_quantity < $product->quantity){
                    $product->message = 'The quantity exceeds the maximum allowed quantity of '.$productAttribute->maximum_quantity.'. Please adjust accordingly.';
                    $errorCount ++;
                }

                // Add the product to the result array, with or without a "message"
                $outOfStockProducts['cart_products'] = $product;
            }
        }
        if ($errorCount > 0) {
            $outOfStockProducts['message'] ? $outOfStockProducts['message'] : $outOfStockProducts['message'] = 'Validation error.';
            return response()->json($outOfStockProducts, 400);
        }
        return $cart;
    }
}
?>
