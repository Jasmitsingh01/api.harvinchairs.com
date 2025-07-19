<?php

namespace App\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Database\Models\Product;
use App\Database\Models\Variation;
use App\Models\ProductAttribute;

class ProductInventoryDecrement implements ShouldQueue
{
    protected function updateProductInventory($product)
    {
        try {
            // $updatedQuantity = $product->quantity - $product->pivot->order_quantity;
            // if ($updatedQuantity > -1) {
            //     // if (TRANSLATION_ENABLED) {
            //     //     $this->updateTranslationsInventory($product, $updatedQuantity);
            //     // } else {
            //         $updated_product = Product::find($product->id)->update(['quantity' => $updatedQuantity]);
            //     // }
                if (!empty($product->pivot->product_attribute_id)) {
                    $variationOption = ProductAttribute::findOrFail($product->pivot->product_attribute_id);
                    if($variationOption->quantity > 0){
                        $variationOption->quantity = $variationOption->quantity - $product->pivot->order_quantity;
                        $variationOption->update(['quantity' => $variationOption->quantity]);
                    }
                }
                $total_qty = 0;
                foreach($product->product_combinations as $combination){
                    if($combination->quantity > 0){
                        $total_qty +=  $combination->quantity;
                    }
                }
                if($total_qty == 0){
                    Product::find($product->id)->update(['in_stock' => false]);
                }

            // }else{
            //     Product::find($product->id)->update(['in_stock' => false]);
            // }
        } catch (Exception $th) {
        }
    }

    public function updateTranslationsInventory($product, $updatedQuantity)
    {
        Product::where('sku', $product->sku)->update(['quantity' => $updatedQuantity]);
    }

    public function updateVariationTranslationsInventory($variationOption, $updatedQuantity)
    {
        Variation::where('sku', $variationOption->sku)->update(['quantity' => $updatedQuantity]);
    }


    public function handle($event)
    {
        $products = $event->order->products;
        foreach ($products as $product) {
            $this->updateProductInventory($product);
        }
    }
}
