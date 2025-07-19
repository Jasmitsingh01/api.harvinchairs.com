<?php

namespace App\Imports;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeCombination;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToArray;

class ProductCombinationImport implements ToArray, WithHeadingRow, WithStartRow
{
    public function headingRow(): int
    {
        return 1;
    }
    public function startRow(): int
    {
        return 2;
    }

    public function array(array $row)
    {
        foreach($row as $combinationArr){
            if(isset($combinationArr['reference_code'])){
                $product = Product::where('reference_code',$combinationArr['reference_code'])->first();
                if($product){

                    // Fetch existing combinations for the product
                    $existingCombinations = ProductAttributeCombination::whereHas('productAttribute', function ($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })->with('attributeValue')->get();

                    $existingCombinationsGrouped = [];

                    foreach ($existingCombinations as $combination) {
                        $productAttributeId = $combination->product_attribute_id;

                        if (!isset($existingCombinationsGrouped[$productAttributeId])) {
                            $existingCombinationsGrouped[$productAttributeId] = [];
                        }

                        $attributeId = $combination->attribute_id;
                        $valueId = $combination->attribute_value_id;

                        $existingCombinationsGrouped[$productAttributeId][$attributeId] = $valueId;
                    }
                    $existingCombStrings= [];
                    foreach($existingCombinationsGrouped as $key=>$comb){
                        ksort($comb);
                        $existingCombStrings[] = '{' . implode(',', array_map(
                            function ($k, $v) {
                                return "$k:$v";
                            },
                            array_keys($comb),
                            $comb
                        )) . '}';
                    }
                    // Fetch existing combinations for the product end

                    //explode and get attribute and value
                    $product_attributes = explode(',',$combinationArr['product_attribute']);
                    $attributeArr = [];
                    foreach($product_attributes as $product_attribute){

                        $keyvalue = explode(':',$product_attribute);
                        $attributeName = current($keyvalue);
                        $attributeValueName = last($keyvalue);

                        $attribute = Attribute::where('name',$attributeName)->first();
                        if(!$attribute){
                            $attribute = Attribute::create([
                                'name' => $attributeName,
                                'slug' => $this->customAttributeSlugify($attributeName)
                            ]);
                        }

                        //find attribute value
                        $attributeValue = AttributeValue::where('value',$attributeValueName)->where('attribute_id',$attribute->id)->first();
                        if(!$attributeValue){
                            $attributeValue = AttributeValue::create([
                                'value' => $attributeValueName,
                                'attribute_id' => $attribute->id,
                                'slug' => $this->customAttributeValueSlugify($attributeValueName)
                            ]);
                        }

                        $attributeArr[] = array($attribute->id => $attributeValue->id);

                    }

                    $attributecombArr[] = array_reduce($attributeArr, function ($carry, $item) {
                        return $carry + $item;
                    }, []);

                    $newCombinations = [];

                    foreach ($attributecombArr as $attribute) {
                        ksort($attribute);
                        $combinationString = '{' . implode(',', array_map(
                            function ($k, $v) {
                                return "$k:$v";
                            },
                            array_keys($attribute),
                            $attribute
                        )) . '}';
                        // Create a unique representation of the combination
                        if (!in_array($combinationString, $existingCombStrings)) {
                            $newCombinations = $attribute;
                        }
                    }

                    if(count($newCombinations) > 0){
                        $productAttribute = ProductAttribute::create([
                            'product_id' => $product->id,
                            'reference_code' => $combinationArr['combination_reference_code'],
                            'price' => $combinationArr['price'],
                            'price_without_gst' => $combinationArr['price_without_gst'],
                            'minimum_quantity' => $combinationArr['minimum_quantity'],
                            'maximum_quantity' => $combinationArr['maximum_quantity'],
                            'quantity' => $combinationArr['stock_quantity'],
                            // 'bulk_buy_discount' => $combinationArr['bulk_discount'],
                            // 'bulk_buy_minimum_quantity' => $combinationArr['minimum_bulk_quantity'],
                            'enabled' => $combinationArr['enabled'],
                            'out_of_stock'=> $combinationArr['out_of_stock']
                        ]);

                        // upload images start
                        // upload images end

                        // add product attribute combination start
                        foreach($newCombinations as $key => $value){
                            ProductAttributeCombination::create([
                                'product_attribute_id' => $productAttribute->id,
                                'attribute_id' => $key,
                                'attribute_value_id' => $value
                            ]);
                        }
                        // add product attribute combination end

                    }
                }else{
                    \Log::info('product not found'.$combinationArr['reference_code']);
                }
            }
        }
    }

    public function customAttributeSlugify($text, string $divider = '-')
    {
        $slug      = str_replace(' ', '-', $text);
        $slugCount = Attribute::where('slug', $slug)->orWhere('slug', 'like',  $slug . '%')->count();

        if (empty($slugCount)) {
            return $slug;
        }

        return $slug . $divider . $slugCount;
    }

    public function customAttributeValueSlugify($text, string $divider = '-')
    {
        $slug      = str_replace(' ', '-', $text);
        $slugCount = AttributeValue::where('slug', $slug)->orWhere('slug', 'like',  $slug . '%')->count();

        if (empty($slugCount)) {
            return $slug;
        }

        return $slug . $divider . $slugCount;
    }
}
