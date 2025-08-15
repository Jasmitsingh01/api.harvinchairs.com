<?php


namespace App\Database\Repositories\admin;

use Exception;
use ImageKit\ImageKit;
use App\Models\Product;
use App\Models\FeatureValue;
use App\Models\SpecificPrice;
use App\Models\ProductFeature;
use App\Models\ProductAttribute;
use App\Database\Models\Attribute;
use Illuminate\Support\Facades\Cache;
use App\Database\Models\AttributeValue;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Database\Repositories\FeatureValueRepository;
use App\Models\Faq;
use Prettus\Repository\Exceptions\RepositoryException;

class ProductRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'        => 'like',
        'shop_id',
        'language',
    ];

    protected $dataArray = [
        'name',
        'reference_code',
        'slug',
        'type_id',
        'author_id',
        'language',
        'manufacturer_id',
        'product_type',
        'quantity',
        'unit',
        'is_digital',
        'is_external',
        'external_product_url',
        'external_product_button_text',
        'description',
        'sku',
        'image',
        // 'gallery',
        'status',
        'height',
        'length',
        'width',
        'in_stock',
        'is_taxable',
        'shop_id',
        'redirect_when_disabled',
        // 'options',
        'conditions',
        'retail_price',
        'unit_price',
        'unity',
        'meta_title',
        'meta_description',
        'from_date',
        'to_date',
        'from_time',
        'to_time',
        'video_link',
        // 'cover_image',
        'video_heading',
        'video_description',
        'weight',
        'depth',
        'additional_shipping_fees',
        'default_category',
        'available_now',
        'available_later',
        'isNew',
        'isFeatured',
        'show_price',
        'online_only',
        'available_for_order',
        'out_of_stock',
        'minimum_quantity',
        'is_active',
        'creative_cuts',
        'shop_id',
        'warranty_details',
        'dimension',
        'postcodes',
        'maintenance_details',
        'assembly_charges',
        'cgst_rate',
        'sgst_rate'

    ];
    public $featureValueRepository;
    public function boot()
    {
        $this->featureValueRepository = resolve(FeatureValueRepository::class);
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
        return Product::class;
    }

    public function storeProduct($request)
    {
        try {
            Cache::flush();
            $data = $request->only($this->dataArray);
            $stringifySlug = $this->customSlugify($request->name);
            $data['slug'] = $stringifySlug;
            $data['shop_id'] = config('shop.shop_id');
            $product = $this->create($data);
            return $product;
        } catch (ValidatorException $e) {
            throw new Exception("Internal server error.");
        }
    }

    public function updateProduct($request, $product)
    {
        try {
            Cache::flush();
            if (is_array($request['metas'])) {
                foreach ($request['metas'] as $key => $value) {
                    $metas[$value['key']] = $value['value'];
                    $product->setMeta($metas);
                }
            }
            $language = $request->language ? $request->language : config('shop.default_language');
            if ($request->language == 'en') {
                $language = config('shop.default_language');
            }

            $product->language = $language;
            if (isset($request['categories'])) {
                $product->categories()->sync($request['categories']);
            }
            if (isset($request['dimension'])) {
                $product->dimension =$request['dimension'];
                $product->save();
            }
            if (isset($request['tags'])) {
                $product->tags()->sync($request['tags']);
            }
            if (isset($request['dropoff_locations'])) {
                $product->dropoff_locations()->sync($request['dropoff_locations']);
            }
            if (isset($request['pickup_locations'])) {
                $product->pickup_locations()->sync($request['pickup_locations']);
            }

            if (isset($request['features'])) {
                $product->features()->sync($request['features']);
            }

            if (isset($request['comb_qty'])) {

                foreach ($request['comb_qty'] as $key => $value) {
                    $value = [];
                    $value['reference_code'] = $request->comb_ref[$key];
                    $value['price'] = $request->comb_price[$key];
                    $value['price_without_gst'] = $request->comb_price_without_gst[$key];
                    $value['minimum_quantity'] = $request->comb_minqty[$key];
                    $value['maximum_quantity'] = $request->comb_maxqty[$key];
                    $value['quantity'] = $request->comb_qty[$key];
                    // $value['bulk_buy_discount'] = $request->comb_bulk_discount[$key];
                    // $value['bulk_buy_minimum_quantity'] = $request->comb_minqty_bulk[$key];
                    $value['enabled'] = $request->enabled[$key];
                    $value['out_of_stock']= $request['out_of_stock'];
                    $request->discount_type != null ? $value['discount_type'] = $request->discount_type : null;
                    $request->unit_id != null ? $value['unit_id'] = $request->unit_id : null;
                    $prod_attribute = ProductAttribute::find($key);

                    if (isset($prod_attribute)) {
                        $prod_attribute->update($value);
                    }
                }
            }
            $new_feature_records = [];
            $old_feature_records = [];
            if (isset($request['feature_value_text'])) {

                foreach ($request['feature_value_text'] as $key => $value) {
                    if (isset($value) && $value != null) {
                        $featureValue =   $this->featureValueRepository->where(['feature_id' => $key, 'is_custom' => true, 'value' => $value])->first();
                        $feature = [];
                        if (!isset($featureValue)) {
                            $feature['value'] = $value;
                            $feature['feature_id'] = $key;
                            $feature['is_custom'] = true;

                            $featureValue =  $this->featureValueRepository->storeFeatureValue($feature);
                        }
                        $feature['product_id'] = $product->id;
                        $feature['feature_value_id'] = $featureValue->id;
                        //find product feature
                        $prod_feature = $product->productFeatures()->where('feature_value_id', $feature['feature_value_id'])->first();

                        //store new product feature if not exists.
                        if (!isset($prod_feature)) {
                            $prod_feature = $product->productFeatures()->create(['feature_value_id' => $feature['feature_value_id']]);
                        }
                        $new_feature_records[] = $prod_feature->id;
                    }
                }
            }

            if (isset($request['feature_value'])) {

                foreach ($request['feature_value'] as $key => $value) {
                    if (isset($value) && $value != null) {
                        $feature['feature_value_id'] = $value;
                        //find product feature
                        $prod_feature = $product->productFeatures()->where('feature_value_id', $feature['feature_value_id'])->first();

                        //store new product feature if not exists.
                        if (!isset($prod_feature)) {
                            $prod_feature = $product->productFeatures()->create(['feature_value_id' => $feature['feature_value_id']]);
                        }
                        $new_feature_records[] = $prod_feature->id;
                    }
                }
            }

            if (!empty($new_feature_records)) {
                $data = ProductFeature::where('product_id', $product->id)->whereNotIn('id', $new_feature_records)->delete();
            }
            if (isset($request['product_specific_prices'])) {
                $old_ids = [];
                foreach ($request['product_specific_prices'] as $specific_price) {
                    $specific_price['product_id'] = $product->id;
                    if (isset($specific_price['id'])) {
                        $old_ids[] = $specific_price['id'];
                    } else {
                        if (is_array($specific_price['customer_id'])) {
                            foreach ($specific_price['customer_id'] as $customer) {
                                $specific_price['customer_id'] = $customer;
                                $sp_price = new SpecificPrice();
                                $data = $sp_price->create($specific_price);
                                $old_ids[] = $data->id;
                            }
                        } else {
                            $sp_price = new SpecificPrice();
                            $data = $sp_price->create($specific_price);
                            $old_ids[] = $data->id;;
                        }
                    }
                }
                //delete old specific prices
                SpecificPrice::where(['product_id' => $product->id])->whereNotIn('id', $old_ids)->delete();
            }
            // if(isset($request['combinations'])){
            //     foreach ($request['combinations'] as $value) {
            //         $prod_attribute = ProductAttribute::find($value['product_attribute_id']);
            //         if(isset($prod_attribute)){
            //             $prod_attribute->update(['product_id'=>$product->id,'quantity'=>$value['quantity'], 'out_of_stock'=>$request['out_of_stock']]);
            //         }
            //     }
            // }
            $data = $request->only($this->dataArray);
            // dd($data);
            if(isset($request->slug)){
                $data['slug'] = $request->slug;
            }
            if(isset($request['postcodes']) ||  ($request->input('tabname') == "postcode") ){
                $postcodes = $request->postcodes;
                $postcodesArray = array_filter(array_map('trim', explode(',', $postcodes)));
                $data['postcodes'] = count($postcodesArray) > 0 ? json_encode($postcodesArray) : null;
            }
            if (isset($request['cover_image'])) {
                $imgdata = $product->addMedia(storage_path('tmp/uploads/' . basename($request['cover_image'])))->preservingOriginal()->toMediaCollection('cover_image');
                $converted_url = [
                    'thumbnail' => $imgdata->getUrl('thumb'),
                    'original' => $imgdata->getUrl(),
                    'filepath' =>$imgdata->getPath()
                ];
                $data['cover_image'] = $converted_url;
            }

            if (isset($request['dimension_image']) && ($request->input('tabname') == "dimensions")) {

                $newFileName = basename($request['dimension_image']);
                if (!$product->dimension_image || $request->input('dimension_image') !== $product->dimension_image->name) {
                    // Delete the existing image if it exists
                    if ($product->dimension_image) {
                        $product->dimension_image->delete();
                    }

                    // Store the new image
                    $dimentionImage = $product->addMedia(storage_path('tmp/uploads/' . $newFileName))->preservingOriginal()->toMediaCollection('dimension_image');
                    $dimentionImage->url = $dimentionImage->getUrl();
                    $dimentionImage->thumbnail = $dimentionImage->getUrl('thumb');
                    $dimentionImage->filepath = $dimentionImage->getPath();

                    $converted_url = [
                        'thumbnail' =>  $dimentionImage->thumbnail,
                        'original' =>    $dimentionImage->url,
                        'filepath' =>    $dimentionImage->filepath,
                    ];

                    // Update the product with the new image details
                    $product->update(['dimension_image' => $converted_url]);
                }
            } elseif(! isset($request['dimension_image']) && ($request->input('tabname') == "dimensions")){
                // No new image provided, delete the existing image if it exists
                if ($product->dimension_image) {
                    $product->dimension_image->delete();
                    $product->update(['dimension_image' => null]);
                }
            }

            if (isset($request['gallery_ext'])) {
                $converted_url = [];

                foreach($request['gallery_ext'] as $key=>$g){

                    $gallery = $product->gallery[$key];
                    $galley_id = isset($gallery['id']) ? $gallery['id'] : random_int(100000, 999999);
                    $gallery = [
                        'thumbnail' => $gallery['thumbnail'],
                        'original' =>$gallery['original'],
                        'filepath' =>$gallery['filepath'],
                        'caption' => $request['caption_ext'][$key],
                        'position' => $request['position_ext'][$key],
                        'id'=>$galley_id
                    ];

                    $converted_url[] = $gallery;
                }
                $data['gallery'] =json_encode($converted_url);
            }

            if (isset($request['gallery'])) {
                $converted_url = [];
                $gallery = $product->gallery;
                if(isset($request['gallery_ext'])){
                    foreach(json_decode($data['gallery']) as $g){
                        $converted_url[] = $g;
                    }
                }else{
                    // if(is_array($gallery)){
                    //     foreach($gallery as $g){
                    //         $converted_url[] = $g;
                    //     }
                    // }
                }

                foreach($request['gallery'] as $key=>$g){
                    $imgdata = $product->addMedia(storage_path('tmp/uploads/' . basename($g)))->preservingOriginal()->toMediaCollection('gallery');
                    if(config('app.env') != 'local'){
                        $imageKit = new ImageKit(
                            config('shop.imagekit.public_key'),
                            config('shop.imagekit.private_key'),
                            config('shop.imagekit.endpoint')
                        );
                        $slug= $this->customSlugify($product->name);
                        $cleanedImgCaption = str_replace(['(', ')', ' ', '/'], ['','','','-'], trim($slug));
                        // Upload the file to ImageKit
                        $image_path = storage_path('tmp/uploads/'.basename($g));
                        // dd($image_path);
                        $uploadFile = $imageKit->uploadFile([
                            "file" => fopen($image_path,"r"),
                            "fileName" => $cleanedImgCaption,
                            "folder" => "products"
                        ]);
                        $converted_url[] = [
                            'thumbnail' => $uploadFile->result->thumbnailUrl,
                            'original' => $uploadFile->result->url,
                            'filepath' => $image_path,
                            'caption' => $request['caption'][$key],
                            'position' => $request['position'][$key],
                            'id'=> $imgdata->id
                        ];
                        $media = $product->getMedia('gallery')->find($imgdata->id);

                        if ($media) {
                            $media->delete();
                        }
                    }
                    else{
                        $converted_url[] = [
                            'thumbnail' => $imgdata->getUrl('thumb'),
                            'original' => $imgdata->getUrl(),
                            'filepath' =>$imgdata->getPath(),
                            'caption' => $request['caption'][$key],
                            'position' => $request['position'][$key],
                            'id'=> $imgdata->id
                        ];
                    }
                    // unset($g['filepath']);
                }
                $data['gallery'] =json_encode($converted_url);
            }

            if(!isset($request['gallery_ext']) && !isset($request['gallery']) && ($request->input('tabname') == "gallery")){
                $data['gallery'] =null;
            }
            if (isset($request->retail_price)) {
                $data['price'] =  $request->retail_price;
            }
            if(isset($request['tabname']) && $request['tabname'] == 'faq'){
                //dd($request->all());
                $faq = Faq::create([
                    'question' => $request->question,
                    'answer' => $request->answer,
                    'product_id' => $product->id,
                    'status' => true,
                ]);
                //$data['faq'] = $faq;
            }

            if(isset($request['tabname']) && $request['tabname'] == 'gst'){
                $data['cgst_rate'] = $request['cgst_rate'];
                $data['sgst_rate'] = $request['sgst_rate'];
            }

            $product->update($data);

            return $product;
        } catch (ValidatorException $e) {
            throw new Exception("internal server error.");
        }
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
}
