<?php

namespace App\Imports;

use App\Database\Models\Category;
use App\Models\Feature;
use App\Models\FeatureValue;
use App\Models\ProductFeature;
use App\Models\ProductTag;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToArray;

class ProductImport implements ToArray, WithHeadingRow, WithStartRow
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
        foreach($row as $productArr){

            // check duplicate product start

            // check duplicate product end


            $desiredKeys = [
                'name',
                'reference_code',
                'slug',
                'product_type',
                'description',
                // 'image',
                // 'gallery',
                'status',
                'meta_title',
                'meta_description',
                'video_link',
                // 'cover_image',
                'video_heading',
                'video_description',
                'available_for_order',
                'out_of_stock',
                'is_active',
                'warranty_details',
                'maintenance_details',
                'assembly_charges'
            ];
            $filteredData = collect($productArr)->only($desiredKeys)->all();

            $filteredData = array_filter($filteredData, function($value) {
                // Check if the value is not blank
                return !empty($value);
            });
            //dd($filteredData['name']);

            if(isset($filteredData) && !empty($filteredData) && !empty($filteredData['name'])){
                $name  = (isset($filteredData['name'])) ? $filteredData['name'] : 'test';
                $filteredData['slug'] = $this->customSlugify($name);
                $filteredData['shop_id'] = 1;

                // dimension field changes start
                if(isset($productArr['dimension'])){
                    $filteredData['dimension'] = [];
                    $dimension = $productArr['dimension'];
                    $dimension = explode(',',$dimension);
                    foreach($dimension as $dimen){
                        $keyvalue = explode(':',$dimen);
                        $filteredData['dimension'][] = ["name" => current($keyvalue),"value" => last($keyvalue)];
                    }
                    $filteredData['dimension'] = json_encode($filteredData['dimension']);
                }
                // dimension field changes end

                // postcode field changes start
                if(isset($productArr['postcodes'])){
                    $filteredData['postcodes'] = [];
                    $postcodes = $productArr['postcodes'];
                    $postcodes = explode(',',$postcodes);
                    foreach($postcodes as $postcode){
                        $filteredData['postcodes'][] = trim($postcode, '"');
                    }
                    $filteredData['postcodes'] = json_encode($filteredData['postcodes']);
                }
                // postcode field changes end

                //find category id from name
                if(isset($productArr['default_category'])){
                    $category  = Category::where('name',$productArr['default_category'])->first();
                    $filteredData['default_category'] = (!empty($category)) ? $category->id : null;
                }

                $product = Product::where('reference_code',$productArr['reference_code'])->first();
                if($product){
                    $product->update($filteredData);
                }else{
                    $product = Product::create($filteredData);
                }


                $data = [];
                //attach cover image to media start
                if (isset($productArr['cover_image']) && !empty($productArr['cover_image']) && @getimagesize($productArr['cover_image'])) {
                    $imgdata = $product->addMediaFromUrl($productArr['cover_image'])->preservingOriginal()->toMediaCollection('cover_image');
                    $converted_url = [
                        'thumbnail' => $imgdata->getUrl('thumb'),
                        'original' => $imgdata->getUrl()
                    ];
                    $data['cover_image'] = $converted_url;
                }
                //attach cover image to media end

                //attach dimension image to media start
                if (isset($productArr['dimension_image']) && !empty($productArr['dimension_image']) && @getimagesize($productArr['dimension_image'])) {
                    $imgdata = $product->addMediaFromUrl($productArr['dimension_image'])->preservingOriginal()->toMediaCollection('dimension_image');
                    $converted_url = [
                        'thumbnail' => $imgdata->getUrl('thumb'),
                        'original' => $imgdata->getUrl()
                    ];
                    $data['dimension_image'] = $converted_url;
                }
                //attach dimension image to media end

                //attach gallery image to media start
                if (isset($productArr['gallery'])) {
                    $converted_url = [];
                    $gallerys = explode(',',$productArr['gallery']);
                    $i=0;
                    foreach($gallerys as $gallery){
                        if(!empty($gallery) && @getimagesize($gallery)){
                            $imgdata = $product->addMediaFromUrl($gallery)->preservingOriginal()->toMediaCollection('gallery');
                            $converted_url[] = [
                                'thumbnail' => $imgdata->getUrl('thumb'),
                                'original' => $imgdata->getUrl(),
                                'filepath' =>$imgdata->getPath(),
                                'caption' => '',
                                'position' => $i,
                                'id'=> $imgdata->id
                            ];

                            //$converted_url[] = $gallery;
                            //$data['dimension_image'] = $converted_url;
                        }
                        $i++;
                    }
                    $data['gallery'] =json_encode($converted_url);

                }
                //attach dimension image to media end

                $product->update($data);

                // add product category start
                if(isset($productArr['category']) && !empty($productArr['category'])){
                    $categorys = explode(',',$productArr['category']);
                    $categoryIds = [];
                    foreach($categorys as $category){
                        $category  = Category::where('name',$category)->first();
                        if($category){
                            $categoryIds[] = $category->id;
                        }
                    }
                    $product->categories()->sync($categoryIds);
                }
                // add product category end

                //add product_feature start
                if(isset($productArr['product_feature'])){
                    $filteredData['dimension'] = [];
                    $product_feature = $productArr['product_feature'];
                    $product_feature = explode(',',$product_feature);
                    foreach($product_feature as $productFeat){
                        $keyvalue = explode(':',$productFeat);

                        //find Feature Title Start
                        $feature = Feature::where('title',current($keyvalue))->first();
                        if($feature){
                            $featureValue = FeatureValue::where('feature_id',$feature->id)->where('value',last($keyvalue))->first();
                            if(!$featureValue){
                                $featureValue = FeatureValue::create([
                                    'feature_id' => $feature->id,
                                    'value' => last($keyvalue),
                                    'is_custom' => 1
                                ]);
                            }

                            // insert product feature
                            ProductFeature::create([
                                'product_id' => $product->id,
                                'feature_value_id' => $featureValue->id
                            ]);
                        }
                        //find Feature Title End
                    }
                }
                //add product_feature end


                //add product tags start
                if(isset($productArr['tags'])){
                    $product_tags = [];
                    $tags = explode(',',$productArr['tags']);
                    foreach($tags as $tag){
                        $tagModel = Tag::where('name',$tag)->first();
                        if(!$tagModel){
                            $tagModel = Tag::create([
                                'name' => $tag,
                                'slug' => $this->customTagSlugify($tag)
                            ]);
                        }
                        $product_tags[] = $tagModel->id;
                    }
                    $product->tags()->sync($product_tags);
                }
                //add product tags end
            }
        }
    }

    public function model(array $row)
    {


        // } catch(\Exception $e){
        //     dd($e->getMessage());
        // }

    }

    public function customSlugify($text, string $divider = '-')
    {
        $slug      = str_replace(' ', '-', $text);
        $slugCount = Product::where('slug', $slug)->orWhere('slug', 'like',  $slug . '%')->count();

        if (empty($slugCount)) {
            return $slug;
        }

        return $slug . $divider . $slugCount;
    }

    public function customTagSlugify($text, string $divider = '-')
    {
        $slug      = str_replace(' ', '-', $text);
        $slugCount = Tag::where('slug', $slug)->orWhere('slug', 'like',  $slug . '%')->count();

        if (empty($slugCount)) {
            return $slug;
        }

        return $slug . $divider . $slugCount;
    }
}
