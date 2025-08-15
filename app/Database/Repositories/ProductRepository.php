<?php


namespace App\Database\Repositories;

use Exception;
use Carbon\Carbon;
// use ImageKit\ImageKit;
use Carbon\CarbonPeriod;
use Spatie\Period\Period;
use App\Enums\ProductType;
use Spatie\Period\Precision;
use App\Models\SpecificPrice;
use Spatie\Period\Boundaries;
use App\Models\ProductFeature;
use App\Database\Models\Product;
use App\Models\ProductAttribute;
use App\Database\Models\Category;
use App\Database\Models\Resource;
use App\Models\LastViewedProduct;
use App\Database\Models\Variation;
use App\Exceptions\MarvelException;
use Illuminate\Support\Facades\Log;
use App\Database\Models\Availability;
use Illuminate\Support\Facades\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Database\Repositories\FeatureValueRepository;
use Prettus\Repository\Exceptions\RepositoryException;

class ProductRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'        => 'like',
        'shop_id',
        'status',
        'is_rental',
        'type.slug',
        'dropoff_locations.slug' => 'in',
        'pickup_locations.slug' => 'in',
        'persons.slug' => 'in',
        'deposits.slug' => 'in',
        'features.slug' => 'in',
        'categories.slug' => 'in',
        'tags.slug' => 'in',
        'author.slug',
        'manufacturer.slug' => 'in',
        'min_price' => 'between',
        'max_price' => 'between',
        'price' => 'between',
        'language',
        'metas.key',
        'metas.value',

    ];

    protected $dataArray = [
        'name',
        'slug',
        'price',
        'sale_price',
        'max_price',
        'min_price',
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
        'gallery',
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
        'cover_image',
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
        'reference_code'
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

    /**
     * storeProduct
     *
     * @param  mixed $request
     * @return void
     */
    public function storeProduct($request)
    {
        try {
            $data = $request->only($this->dataArray);

            if ($request->product_type == ProductType::SIMPLE) {
                $data['max_price'] = $data['price'];
                $data['min_price'] = $data['price'];
            }
            $product = $this->create($data);

            if(isset($request->retail_price)){
                $product->price = $request->retail_price;
            }
            $language = $request->language ? $request->language : config('shop.default_language');
            if($request->language == 'en'){
                $language = config('shop.default_language');
            }
            $product->language = $language;
            if (empty($product->slug) || is_numeric($product->slug)) {
                $product->slug = $this->customSlugify($product->name);
            }
            if (isset($request['metas'])) {
                foreach ($request['metas'] as $value) {
                    $metas[$value['key']] = $value['value'];
                    $product->setMeta($metas);
                }
            }
            if(isset($request['combinations'])){
                $quantity = 0;
                // if(isset($request['combinations'][0]['quantity'])){
                //     $product->quantity = $request['combinations'][0]['quantity'];
                // }
                if(isset($request['combinations'][0]['product_attribute_id'])){
                    $prod_attribute = ProductAttribute::find($request['combinations'][0]['product_attribute_id']);
                    if(isset($prod_attribute)){
                        $product->minimum_quantity = $prod_attribute->minimum_quantity;
                    }
                }
                foreach ($request['combinations'] as $value) {
                    $quantity += $value['quantity'];
                    $prod_attribute = ProductAttribute::find($value['product_attribute_id']);
                    if(isset($prod_attribute)){
                        $prod_attribute->update(['product_id'=>$product->id,'quantity'=>$value['quantity'], 'out_of_stock'=>$request['out_of_stock']]);
                    }
                }
                $product->quantity = $quantity;
            }
            if (isset($request['out_of_stock'])) {
                $product->advanced_stock_management = $request['out_of_stock'];
            }
            if (isset($request['categories'])) {
                $product->categories()->attach($request['categories']);
            }
            if (isset($request['dropoff_locations'])) {
                $product->dropoff_locations()->attach($request['dropoff_locations']);
            }
            if (isset($request['pickup_locations'])) {
                $product->pickup_locations()->attach($request['pickup_locations']);
            }
            if (isset($request['persons'])) {
                $product->persons()->attach($request['persons']);
            }
            if (isset($request['features'])) {
                $product->features()->attach($request['features']);
            }
            if (isset($request['product_features'])) {
                foreach($request['product_features'] as $feature){
                    //check customized feature value & store feature value
                    if($feature['is_custom'] == true){
                       $featureValue =  $this->featureValueRepository->storeFeatureValue($feature);
                       $feature['product_id'] = $product->id;
                       $feature['feature_value_id'] = $featureValue->id;
                    }
                    //find pre-defined feature value id
                    else{
                        $featureValue =  $this->featureValueRepository->where(['feature_id'=>$feature['feature_id'],'value'=>$feature['value']])->first();
                        if(isset($featureValue)){
                            $feature['feature_value_id'] = $featureValue->id;
                        }
                    }
                    //store product feature
                    $data = $product->productFeatures()->create(['feature_value_id'=>$feature['feature_value_id']]);
                }
            }
            if (isset($request['product_specific_prices'])) {
                foreach($request['product_specific_prices'] as $specific_price){
                    $specific_price['product_id'] = $product->id;
                    if(is_array($specific_price['customer_id'])){
                        foreach($specific_price['customer_id'] as $customer){
                            $specific_price['customer_id'] = $customer;
                            $sp_price = new SpecificPrice();
                            $sp_price->create($specific_price);
                        }
                    }else{
                        $sp_price = new SpecificPrice();
                        $sp_price->create($specific_price);
                    }

                }
            }
            if (isset($request['deposits'])) {
                $product->deposits()->attach($request['deposits']);
            }
            if (isset($request['tags'])) {
                $product->tags()->attach($request['tags']);
            }
            if (isset($request['variations'])) {
                $product->variations()->attach($request['variations']);
            }
            // if (isset($request['images'])) {
            //     $product->variations()->attach($request['images']);
            // }
            // if (isset($request['variation_options'])) {
            //     foreach ($request['variation_options']['upsert'] as $variation_option) {
            //         if (isset($variation_option['is_digital']) && $variation_option['is_digital']) {
            //             $file = $variation_option['digital_file'];
            //             unset($variation_option['digital_file']);
            //         }
            //         $new_variation_option = $product->variation_options()->create($variation_option);
            //         if (isset($variation_option['is_digital']) && $variation_option['is_digital']) {
            //             $new_variation_option->digital_file()->create($file);
            //         }
            //     }
            // }
            if (isset($request['is_digital']) && $request['is_digital'] === true && isset($request['digital_file'])) {
                $product->digital_file()->create($request['digital_file']);
            }
            $product->save();
            return $product;
        } catch (ValidatorException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * updateProduct
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function updateProduct($request, $id)
    {
        try {
            $product = $this->findOrFail($id);
            if (is_array($request['metas'])) {
                foreach ($request['metas'] as $key => $value) {
                    $metas[$value['key']] = $value['value'];
                    $product->setMeta($metas);
                }
            }
            $language = $request->language ? $request->language : config('shop.default_language');
            if($request->language == 'en'){
                $language = config('shop.default_language');
            }

            $product->language = $language;
            if (isset($request['categories'])) {
                $product->categories()->sync($request['categories']);
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
            if (isset($request['variations'])) {
                $product->variations()->sync($request['variations']);
            }
            if (isset($request['persons'])) {
                $product->persons()->sync($request['persons']);
            }
            if (isset($request['features'])) {
                $product->features()->sync($request['features']);
            }
            if (isset($request['product_features'])) {
                $new_ids = [];
                foreach($request['product_features'] as $feature){
                    //check for customized feature value
                    if($feature['is_custom'] == true){
                        $featureValue =  $this->featureValueRepository->where(['feature_id'=>$feature['feature_id'],'is_custom'=>true,'value'=>$feature['value']])->first();
                        if(!isset($featureValue)){
                            $featureValue =  $this->featureValueRepository->storeFeatureValue($feature);
                        }
                        $feature['product_id'] = $product->id;
                        $feature['feature_value_id'] = $featureValue->id;
                    }
                    //find pre defined feature value id
                    else{
                        $featureValue =  $this->featureValueRepository->where(['feature_id'=>$feature['feature_id'],'value'=>$feature['value']])->first();
                        $feature['feature_value_id'] = $featureValue->id;
                    }
                    //find product feature
                    $prod_feature = $product->productFeatures()->where('feature_value_id',$feature['feature_value_id'])->first();

                    //store new product feature if not exists.
                    if(!isset($prod_feature)){
                        $prod_feature = $product->productFeatures()->create(['feature_value_id'=>$feature['feature_value_id']]);
                    }
                    $new_ids[] =$prod_feature->id;
                }
                //delete extra records
                ProductFeature::where('product_id',$product->id)->whereNotIn('id',$new_ids)->delete();
            }
            if(isset($request['combinations'])){
                $quantity = 0;
                // if(isset($request['combinations'][0]['quantity'])){
                //     $product->quantity = $request['combinations'][0]['quantity'];
                // }
                if(isset($request['combinations'][0]['product_attribute_id'])){
                    $prod_attribute = ProductAttribute::find($request['combinations'][0]['product_attribute_id']);
                    if(isset($prod_attribute)){
                        $product->minimum_quantity = $prod_attribute->minimum_quantity;
                    }
                }
                foreach ($request['combinations'] as $value) {
                    $quantity += $value['quantity'];
                    $prod_attribute = ProductAttribute::find($value['product_attribute_id']);
                    if(isset($prod_attribute)){
                        $prod_attribute->update(['product_id'=>$product->id,'quantity'=>$value['quantity'], 'out_of_stock'=>$request['out_of_stock']]);
                    }
                }
                $product->quantity = $quantity;
            }
            if (isset($request['deposits'])) {
                $product->deposits()->sync($request['deposits']);
            }
            if (isset($request['digital_file'])) {
                $file = $request['digital_file'];
                if (isset($file['id'])) {
                    $product->digital_file()->where('id', $file['id'])->update($file);
                } else {
                    $product->digital_file()->create($file);
                }
            }
            if (isset($request['product_specific_prices'])) {
                $old_ids = [];
                foreach($request['product_specific_prices'] as $specific_price){
                    $specific_price['product_id'] = $product->id;
                    if(isset($specific_price['id'])){
                        $old_ids[] = $specific_price['id'];
                    }
                    else{
                        if(is_array($specific_price['customer_id'])){
                            foreach($specific_price['customer_id'] as $customer){
                                $specific_price['customer_id'] = $customer;
                                $sp_price = new SpecificPrice();
                                $data = $sp_price->create($specific_price);
                                $old_ids[] =$data->id;
                            }
                        }
                        else{
                            $sp_price = new SpecificPrice();
                            $data = $sp_price->create($specific_price);
                            $old_ids[] =$data->id;;
                        }
                    }
                }
                //delete old specific prices
                SpecificPrice::where(['product_id'=>$product->id])->whereNotIn('id',$old_ids)->delete();
            }
            // if(isset($request['combinations'])){
            //     foreach ($request['combinations'] as $value) {
            //         $prod_attribute = ProductAttribute::find($value['product_attribute_id']);
            //         if(isset($prod_attribute)){
            //             $prod_attribute->update(['product_id'=>$product->id,'quantity'=>$value['quantity'], 'out_of_stock'=>$request['out_of_stock']]);
            //         }
            //     }
            // }
            if (isset($request['variation_options'])) {
                if (isset($request['variation_options']['upsert'])) {
                    foreach ($request['variation_options']['upsert'] as $key => $variation) {
                        if (isset($variation['is_digital']) && $variation['is_digital']) {
                            $file = $variation['digital_file'];
                            unset($variation['digital_file']);
                            if (isset($variation['id'])) {
                                $product->variation_options()->where('id', $variation['id'])->update($variation);
                                try {
                                    $updated_variation = Variation::findOrFail($variation['id']);
                                } catch (Exception $e) {
                                    throw new MarvelException(NOT_FOUND);
                                }
                                if (TRANSLATION_ENABLED) {
                                    Variation::where('sku', $updated_variation->sku)->where('id', '!=', $updated_variation->id)->update([
                                        'price' => $updated_variation->price,
                                        'sale_price' => $updated_variation->sale_price,
                                        'quantity' => $updated_variation->quantity,
                                    ]);
                                }
                                if (isset($file['id'])) {
                                    $updated_variation->digital_file()->where('id', $file['id'])->update($file);
                                } else {
                                    $updated_variation->digital_file()->create($file);
                                }
                            } else {
                                $new_variation = $product->variation_options()->create($variation);
                                $new_variation->digital_file()->create($file);
                            }
                        } else {
                            if (isset($variation['id'])) {
                                $product->variation_options()->where('id', $variation['id'])->update($variation);
                            } else {
                                $product->variation_options()->create($variation);
                            }
                        }
                    }
                }
                if (isset($request['variation_options']['delete'])) {
                    foreach ($request['variation_options']['delete'] as $key => $id) {
                        try {
                            $product->variation_options()->where('id', $id)->delete();
                        } catch (Exception $e) {
                            //
                        }
                    }
                }
            }
            $data = $request->only($this->dataArray);
            if ($request->product_type == ProductType::VARIABLE) {
                // $data['price'] = NULL;
                $data['sale_price'] = NULL;
                $data['sku'] = NULL;
            }
            if ($request->product_type == ProductType::SIMPLE) {
                $data['max_price'] = $data['price'];
                $data['min_price'] = $data['price'];
            }

            if (!empty($request->slug) &&  $request->slug != $product->slug) {
                $stringifySlug = $this->customSlugify($request->slug);
                $data['slug'] = $stringifySlug;

                if (TRANSLATION_ENABLED) {
                    $this->where('slug', $product->slug)->where('id', '!=', $product->id)->update([
                        'slug' => $stringifySlug
                    ]);
                }
            }

            if(isset($request->retail_price)){
                  $data['price'] =  $request->retail_price;
            }
            $product->update($data);
            if ($product->product_type === ProductType::SIMPLE) {
                $product->variations()->delete();
                $product->variation_options()->delete();
            }
            $product->save();

            if (TRANSLATION_ENABLED) {
                $this->where('sku', $product->sku)->where('id', '!=',  $product->id)->update([
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'max_price' => $product->max_price,
                    'min_price' => $product->min_price,
                    'unit' => $product->unit,
                    'quantity' => $product->quantity,
                ]);
            }
            return $product;
        } catch (ValidatorException $e) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }

    public function fetchRelated($slug, $limit = 10, $language = DEFAULT_LANGUAGE)
    {
        try {
            $product    = $this->findOneByFieldOrFail('id', $slug);
            $categories = $product->categories->pluck('id');
            return $this->where('language', $language)->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('categories.id', $categories);
            })->with('type')->limit($limit)->get()->filter(function ($product) {
                return (count($product->product_combination_short) > 0);
            })->values();
        } catch (Exception $e) {
            return [];
        }
    }

    public function getUnavailableProducts($from, $to)
    {
        $_blockedDates = Availability::whereDate('from', '<=', $from)
            ->whereDate('to', '>=', $to)
            ->get()->groupBy('product_id');

        $unavailableProducts = [];

        foreach ($_blockedDates as $productId =>  $date) {
            if (!$this->isProductAvailableAt($from, $to, $productId, $date)) {
                $unavailableProducts[] = $productId;
            }
        }
        return $unavailableProducts;
    }

    public function isProductAvailableAt($from, $to, $productId, $_blockedDates, $requestedQuantity = 1)
    {
        $quantity = 0;
        try {
            $product = Product::findOrFail($productId);
        } catch (\Throwable $th) {
            throw new MarvelException(NOT_FOUND);
        }

        foreach ($_blockedDates as $singleDate) {
            $period = Period::make($singleDate['from'], $singleDate['to'], Precision::DAY, Boundaries::EXCLUDE_END);
            $range = Period::make($from, $to, Precision::DAY, Boundaries::EXCLUDE_END);
            if ($period->overlapsWith($range)) {
                $quantity += $singleDate->order_quantity;
            }
        }
        return $product->quantity - $quantity > $requestedQuantity;
    }


    public function fetchBlockedDatesForAProductInRange($from, $to, $productId)
    {
        return  Availability::where('product_id', $productId)->whereDate('from', '>=', $from)->whereDate('to', '<=', $to)->get();
    }

    public function fetchBlockedDatesForAVariationInRange($from, $to, $variation_id)
    {
        return  Availability::where('bookable_id', $variation_id)->where('bookable_type', 'App\Database\Models\Variation')->whereDate('from', '>=', $from)->whereDate('to', '<=', $to)->get();
    }

    public function isVariationAvailableAt($from, $to, $variationId, $_blockedDates, $requestedQuantity)
    {
        $quantity = 0;
        try {
            $variation = Variation::findOrFail($variationId);
        } catch (\Throwable $th) {
            throw new MarvelException(NOT_FOUND);
        }

        foreach ($_blockedDates as $singleDate) {
            $period = Period::make($singleDate['from'], $singleDate['to'], Precision::DAY, Boundaries::EXCLUDE_END);
            $range = Period::make($from, $to, Precision::DAY, Boundaries::EXCLUDE_END);
            if ($period->overlapsWith($range)) {
                $quantity += $singleDate->order_quantity;
            }
        }
        return $variation->quantity - $quantity >= $requestedQuantity;
    }


    public function calculatePrice($bookedDay, $product_id, $variation_id, $quantity, $persons, $dropoff_location_id, $pickup_location_id, $deposits, $features)
    {
        $price = 0;
        $person_price = 0;
        $deposit_price = 0;
        $feature_price = 0;
        $dropoff_location_price = 0;
        $pickup_location_price = 0;

        if ($variation_id) {
            $variation_price = $this->calculateVariationPrice($variation_id);
            $price += $variation_price * $bookedDay * $quantity;
        } else {
            $product_price = $this->calculateProductPrice($product_id);
            $price += $product_price * $bookedDay * $quantity;
        }
        if ($dropoff_location_id) {
            $dropoff_location_price = $this->calculateLocationPrice($dropoff_location_id);
        }
        if ($pickup_location_id) {
            $pickup_location_price = $this->calculateLocationPrice($pickup_location_id);
        }
        if ($features) {
            $feature_price = $this->calculateResourcePrice($features);
        }
        if ($persons) {
            $person_price = $this->calculateResourcePrice($persons);
        }
        if ($deposits) {
            $deposit_price = $this->calculateResourcePrice($deposits);
        }

        return [
            'totalPrice' => $price + $person_price + $deposit_price + $feature_price + $dropoff_location_price, $pickup_location_price,
            'personPrice' => $person_price,
            'depositPrice' => $deposit_price,
            'featurePrice' => $feature_price,
            'dropoffLocationPrice' => $dropoff_location_price,
            'pickupLocationPrice' => $pickup_location_price
        ];
    }

    public function calculateProductPrice($product_id)
    {
        try {
            $product = Product::findOrFail($product_id);
        } catch (\Throwable $th) {
            throw new MarvelException(NOT_FOUND);
        }
        return $product->sale_price ? $product->sale_price : $product->price;
    }

    public function calculateVariationPrice($variation_id)
    {
        try {
            $variation = Variation::findOrFail($variation_id);
        } catch (\Throwable $th) {
            throw new MarvelException(NOT_FOUND);
        }
        return $variation->sale_price ? $variation->sale_price : $variation->price;
    }

    public function calculateLocationPrice($location_id)
    {
        try {
            $location = Resource::findOrFail($location_id);
        } catch (\Throwable $th) {
            throw new MarvelException(NOT_FOUND);
        }
        return $location->price;
    }

    public function calculateResourcePrice($resources)
    {
        $price = 0;
        foreach ($resources as $resource_id) {
            try {
                $resource = Resource::findOrFail($resource_id);
            } catch (\Throwable $th) {
                throw new MarvelException(NOT_FOUND);
            }
            if ($resource->price) {
                $price += $resource->price;
            }
        }
        return $price;
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
    public function fetchProducts( $request)
    {
        $unavailableProducts = [];
        $products =  $this;
        $language = $request->language ? $request->language : config('shop.default_language');
        if($request->language == 'en'){
            $language = config('shop.default_language');
        }
        //date filter
        if (isset($request->date_range)) {
            $dateRange = explode('//', $request->date_range);
            $unavailableProducts = $this->getUnavailableProducts($dateRange[0], $dateRange[1]);
        }
        //category filter
        if (isset($request->category)) {
            $categories = explode(',', $request->category);
            foreach($categories as $category_id){
                $category = Category::find($category_id);
                if(isset($category) && $category->parent_id == 6){
                    $categories = array_merge($categories, $category->children->pluck('id')->toArray());
                }
            }
            $products = $this->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('categories.id', $categories);
            });
        }
        //attribute filter
        if (isset($request->attribute)) {
            $attributes = explode(',', $request->attribute);
            $products = $this->whereHas('attributeCombinations', function ($query) use ($attributes) {
                $query->whereHas('attributeCombinations' ,function ($query) use ($attributes) {
                    $query->whereIn('attribute_value_id', $attributes);
                });
            })->with('attributeCombinations');
        }
        //search filter
        switch($request->search_filter)
        {
            case 'featured' :
                $products = $products->where('isFeatured',true);
                break;
            case 'new_arrival' :
                $products->where('isNew',true);
                break;
            case 'name_asc' :
                $products->orderBy('name', 'ASC');
                break;
            case 'name_desc' :
                $products->orderBy('name', 'DESC');
                break;
            case 'price_asc' :
                $products->orderBy('retail_price', 'ASC');
                break;
            case 'price_desc' :
                $products->orderBy('retail_price', 'DESC');
                break;

            default :
                break;
        }
        if (in_array('variation_options.digital_files', explode(';', $request->with)) || in_array('digital_files', explode(';', $request->with))) {
            throw new MarvelException(config('shop.app_notice_domain') . 'ERROR.NOT_AUTHORIZED');
        }

        return $products->where('language', $language)->whereNotIn('id', $unavailableProducts);
    }
    public function fetchProductList($request)
    {
        $unavailableProducts = [];
        $products =  $this;

        $language = $request->language ? $request->language : config('shop.default_language');
        if (isset($request->product_search)) {
            $products = $products->where('name', 'LIKE', '%'. $request->product_search. '%')
            ->orWhere('description', 'LIKE', '%'. $request->product_search. '%')
            ->orWhereHas('tags', function ($query) use ($request) {
                $query->where('tags.name','like','%'. $request->product_search. '%');
            })
            ->orWhereHas('categories', function ($query) use ($request) {
                    $query->where('categories.name','LIKE', '%' . $request->product_search . '%');
            });
        }
        $products = $products
        ->where('language', $language)
        ->where('is_active', true)
        ->select(['products.id', 'old_id', 'name', 'slug', 'meta_description', 'description', 'price', 'minimum_quantity', 'is_active', 'retail_price', 'language', 'in_stock', 'gallery', 'unit_price', 'unity', 'default_category', 'advanced_stock_management', 'available_now', 'available_later', 'isNew', 'isFeatured', 'quantity', 'out_of_stock','available_for_order','created_at']);
        //date filter
        if (isset($request->date_range)) {
            $dateRange = explode('//', $request->date_range);
            $unavailableProducts = $this->getUnavailableProducts($dateRange[0], $dateRange[1]);
        }
        // if (isset($request->product_search)) {
        //    $products = $products->where('name', 'LIKE', '%'. $request->product_search. '%');
        // }

        //category filter
        if (isset($request->category)) {
            $categories = explode(',', $request->category);
            // foreach($categories as $category){
            //     // dd($category);
            //     $new_category = \DB::table('categories')->where('old_id',$category)->orWhere(function ($q) use ($category) {
            //         $q->where('old_id', '=', null)
            //           ->where('id', '>=', $category);
            //     })->select('id','old_id')->first();
            //     if($new_category){
            //         $category = $new_category->id;
            //     }
            // }
            $products = $products->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('categories.id', $categories)
                    ->orWhereIn('parent', $categories);
            });
        }
        //attribute filter
        if (isset($request->attribute)) {
            $attributes = explode(',', $request->attribute);
            $products = $products->whereHas('attributeCombinations', function ($query) use ($attributes) {
                $query->whereHas('attributeCombinations' ,function ($query) use ($attributes) {
                    $query->whereIn('attribute_value_id', $attributes);
                });
            })->with('attributeCombinations');
        }
        return $products;
    }
    public function addLastViewedProduct($request,$product){
        try{
            $user = auth()->guard('api')->user();
            $user_id =  isset($user->id) ? $user->id : 0;
            // Attach the last viewed product for the user
            $lastViewedProduct = LastViewedProduct::updateOrCreate(
                ['customer_id' => $user_id, 'product_id' => $product->id]
            );
            return true;
        }catch(Exception $e){
            throw new MarvelException(config('shop.app_notice_domain') . 'ERROR.NOT_AUTHORIZED');
        }

    }

    public function lastViewedProducts($request){
        $user = auth()->guard('api')->user();
        $limit = $request->limit ? $request->limit : 10;
        //$last_viewed_products = LastViewedProduct::select('products.*')->with(['product'])->where('customer_id',$user->id)->latest()->paginate($limit);

        $last_viewed_products = Product::select('products.*')->join('last_viewed_products', 'products.id', '=', 'last_viewed_products.product_id')
        ->where('last_viewed_products.customer_id', $user->id)
        ->orderByDesc('last_viewed_products.updated_at')
        ->paginate($limit);
        return $last_viewed_products;
    }
}
