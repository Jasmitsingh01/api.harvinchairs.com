<?php

namespace App\Database\Models;

use App\Models\Coupon;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Traits\Excludable;
use Kodeine\Metable\Metable;
use App\Models\SpecificPrice;
use Laravel\Scout\Searchable;
use App\Models\ProductFeature;
use App\Models\ProductAttribute;
use App\Traits\TranslationTrait;
use App\Models\LastViewedProduct;
use Illuminate\Support\Facades\DB;
use App\Exceptions\MarvelException;
use App\Models\Faq;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use Sluggable, SoftDeletes, Excludable, Metable, TranslationTrait;
    // use Searchable;
    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            // Flush the cache when a product is created
            Cache::flush();
        });

        static::updated(function ($product) {
            // Flush the cache when a product is updated
            Cache::flush();
        });

        static::deleted(function ($product) {
            // Flush the cache when a product is deleted
            Cache::flush();
        });
    }
    public $guarded = [];

    protected $table = 'products';
    protected $metaTable = 'products_meta'; //optional.
    public $hideMeta = true;


    protected $casts = [
        'image' => 'json',
        'gallery' => 'json',
        'video' => 'json',
        'cover_image' => 'json',
        'postcodes' => 'json',
    ];

    protected $appends = [
        'ratings',
        'total_reviews',
        'rating_count',
        'my_review',
        'in_wishlist',
        // 'blocked_dates',
        // 'translated_languages',
        'product_features',
        // 'categories',
        'product_combinations',
        'reviews',
        // 'product_specific_prices'
    ];

    protected $hidden = [
        'old_id',
        'type_id',
        'sale_price',
        'min_price',
        'max_price',
        'quantity',
        'minimum_quantity',
        'product_type',
        'unit',
        'author_id',
        'manufacturer_id',
        'is_digital',
        'is_external',
        'external_product_url',
        'external_product_button_text',
        'redirect_when_disabled',
        'options',
        'Stock_for_barcode',
        'creative_cuts',
        "author_id",
        "manufacturer_id",
        "is_digital",
        "is_external",
        "external_product_url",
        "external_product_button_text",
        "redirect_when_disabled",
        "options",
        "online_only",
        "show_price",
        "conditions",
        "deleted_at",
        "created_at",
        "updated_at",
        "retail_price",
        "unit_price",
        "unity",
        "shipping_class_id",
        "additional_shipping_fees",
        "price",
        "height",
        "width",
        "length",
        "depth",
        "weight",
    ];
    // public function getPriceAttribute($value)
    // {
    //     return (float)number_format($value, 2); // Format to 2 decimal places
    // }
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    public function scopeWithUniqueSlugConstraints(Builder $query, Model $model): Builder
    {
        return $query->where('language', $model->language);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getBlockedDatesAttribute()
    {
        return $this->getBlockedDates();
    }

    function getBlockedDates()
    {
        $_blockedDates = $this->fetchBlockedDatesForAProduct();
        $_flatBlockedDates = [];
        foreach ($_blockedDates as $date) {
            $from = Carbon::parse($date->from);
            $to = Carbon::parse($date->to);
            $dateRange = CarbonPeriod::create($from, $to);
            $_blockedDates = $dateRange->toArray();
            unset($_blockedDates[count($_blockedDates) - 1]);
            $_flatBlockedDates =  array_unique(array_merge($_flatBlockedDates, $_blockedDates));
        }
        return $_flatBlockedDates;
    }

    public function fetchBlockedDatesForAProduct()
    {
        return  Availability::where('product_id', $this->id)->where('bookable_type', 'App\Database\Models\Product')->whereDate('to', '>=', Carbon::now())->get();
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    /**
     * @return BelongsTo
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    /**
     * @return BelongsTo
     */
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    /**
     * @return BelongsTo
     */
    public function shipping(): BelongsTo
    {
        return $this->belongsTo(Shipping::class, 'shipping_class_id');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    /**
     * @return HasMany
     */
    public function variation_options(): HasMany
    {
        return $this->hasMany(Variation::class, 'product_id');
    }

    /**
     * @return belongsToMany
     */
    public function orders(): belongsToMany
    {
        return $this->belongsToMany(Order::class)->withTimestamps();
    }

     /**
     * @return hasMany
     */
    public function lastViewedProducts(): hasMany
    {
        return $this->hasMany(LastViewedProduct::class,'product_id','id');
    }

    /**
     * @return BelongsToMany
     */
    public function variations(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_product');
    }

    /**
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    /**
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'product_id');
    }

    /**
     * @return HasMany
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    public function getRatingsAttribute()
    {
        return round($this->reviews()->where('is_active',true)->avg('rating'), 2);
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->where('is_active',true)->count();
    }
    public function getReviewsAttribute()
    {
        return $this->reviews()->where('is_active',true)->get();
    }

    public function getRatingCountAttribute()
    {
        return $this->reviews()->orderBy('rating', 'DESC')->groupBy('rating')->select('rating', DB::raw('count(*) as total'))->get();
    }

    public function getMyReviewAttribute()
    {
        if (auth()->guard('api')->user() && !empty($this->reviews()->where('user_id', auth()->guard('api')->user()->id)->first())) {
            return $this->reviews()->where('user_id', auth()->guard('api')->user()->id)->get();
        }
        return null;
    }

    public function getInWishlistAttribute()
    {
        if (auth()->guard('api')->user() && !empty($this->wishlists()->where('user_id', auth()->guard('api')->user()->id)->first())) {
            return true;
        }
        return false;
    }
    public function digital_file()
    {
        return $this->morphOne(DigitalFile::class, 'fileable');
    }

    public function availabilities()
    {
        return $this->morphMany(Availability::class, 'bookable');
    }


    /**
     * @return BelongsToMany
     */
    public function dropoff_locations(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'dropoff_location_product', 'product_id', 'resource_id');
    }
    /**
     * @return BelongsToMany
     */
    public function pickup_locations(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'pickup_location_product', 'product_id', 'resource_id');
    }
    /**
     * @return BelongsToMany
     */
    public function deposits(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'deposit_product', 'product_id', 'resource_id');
    }
    /**
     * @return BelongsToMany
     */
    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'person_product', 'product_id', 'resource_id');
    }
    /**
     * @return BelongsToMany
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'feature_product', 'product_id', 'resource_id');
    }
     /**
     * @return BelongsToMany
     */
    public function productFeatures(): hasMany
    {
        return $this->hasMany(ProductFeature::class, 'product_id','id')->with('featureValue');
    }
    public function getProductFeaturesAttribute()
    {
        return $this->productFeatures()->get(['id','feature_value_id']);
    }
    public function getCategoriesAttribute()
    {
        return $this->categories()->get();
    }
    public function getCategoryListAttribute()
    {
        return $this->categories()->where('language',config('shop.default_language'))->get(['categories.id','name','description','slug','categories.language'])->makeHidden(['translated_languages']);
    }
    public function attributeCombinations(): hasMany
    {
        return $this->hasMany(ProductAttribute::class,'product_id')->with(['attributeCombinations','units:id,title']);
    }
    public function attributeShortCombinations(): hasMany
    {
        return $this->hasMany(ProductAttribute::class,'product_id');
    }
    public function attributeCombinationsShort()
    {
        $productAttributes = DB::table('product_attributes')->where('product_id',$this->id)->select('product_attributes.id','product_attributes.product_id')->pluck('product_attributes.id');
        $cobinations = DB::table('product_attribute_combinations')->whereIn('product_attribute_id',$productAttributes);
        return $cobinations;
    }
    public function attributeShort()
    {
        $productAttributes = DB::table('product_attributes')->where('product_id',$this->id)->select('id','reference_code','quantity','price','price_without_gst','minimum_quantity')->orderBy('product_attributes.price','desc');
        return $productAttributes;
    }
    public function getProductCombinationsAttribute()
    {
        $inStockCombinations = $this->attributeCombinations()
        ->whereHas('attributeCombinations',function($q){
            $q->whereHas('attribute',function($q1){
                $q1->where('deleted_at',null);
            })->whereHas('attributeValue',function($q2){
                $q2->where('deleted_at',null);
            });
        })
        ->where('product_attributes.out_of_stock', false)
        ->whereColumn('product_attributes.minimum_quantity', '<=', 'product_attributes.quantity')
        ->where('product_attributes.quantity', '>', 0)
        ->where('enabled', true)
        ->orderBy('price')
        ->get();

        $outOfStockCombinations = $this->attributeCombinations()
            ->whereHas('attributeCombinations',function($q){
                $q->whereHas('attribute',function($q1){
                    $q1->where('deleted_at',null);
                })->whereHas('attributeValue',function($q2){
                    $q2->where('deleted_at',null);
                });
            })
            ->where(function ($query) {
                $query->where('product_attributes.out_of_stock', true)
                    ->orWhere(function ($query) {
                        $query->where('product_attributes.out_of_stock', false)
                            ->whereColumn('product_attributes.minimum_quantity', '>', 'product_attributes.quantity');
                    });
            })
            ->where('enabled', true)
            ->orderBy('price')
            ->get();
        return $inStockCombinations->concat($outOfStockCombinations)->append('all_combination');
    }
    public function getProductCombinationShortAttribute()
    {
        return $this->attributeShortCombinations()
        ->whereHas('attributeCombinations',function($q){
            $q->whereHas('attribute',function($q1){
                $q1->where('deleted_at',null);
            })->whereHas('attributeValue',function($q2){
                $q2->where('deleted_at',null);
            });
        }) // Additional condition
        ->orderBy('price')
        ->where('enabled', true)
        ->where(function ($query) {
            $query->where('product_attributes.out_of_stock', true)
                ->orWhere(function ($query) {
                    $query->where('product_attributes.out_of_stock', false)
                        ->whereColumn('product_attributes.minimum_quantity', '<=', 'product_attributes.quantity')
                        ->where('product_attributes.quantity', '>', 0);
                });
        })
        ->select(['id', 'product_id', 'reference_code', 'price','price_without_gst', 'images', 'enabled', 'quantity', 'out_of_stock', 'minimum_quantity'])
        ->get()
        ->take(1);
        // ->makeHidden(['combinations']);
    }
    public function getCombinationsAttribute()
    {
        return $this->attributeCombinationsShort()->groupby('attribute_value_id')->distinct()->get(['attribute_value_id','product_attribute_id']);
    }
    public function getProductCombinationsShortAttribute()
    {
       return $this->attributeShort()->first();
    }
    public function specificPrices(): hasMany
    {
        return $this->hasMany(SpecificPrice::class,'product_id');
    }
    public function getProductSpecificPricesAttribute()
    {
        //get all specific prices
            $specific_prices = $this->specificPrices()->get();
            foreach($specific_prices as $price){
                if(isset(auth()->guard('api')->user()->id) && in_array('super_admin',auth()->guard('api')->user()->getPermissionNames()->toArray())){
                    return $specific_prices;
                }
                //check from date and to date of specific price to apply on product
                elseif($price->from <= date('Y-m-d H:i:s') && $price->to >= date('Y-m-d H:i:s')){
                    if( $price->customer_id == null){
                        return $specific_prices;
                    }

                    //check specific customer price
                    elseif (isset(auth()->guard('api')->user()->id) && $price->customer_id == auth()->guard('api')->user()->id){
                        return $specific_prices;
                    }
                    else{
                    return null;
                    }
                }
            }
        return null;
    }
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'product_id');
    }

    public function getProductFaqsAttribute ()
    {
        $product_faq = $this->faqs()->where('status', true)->get();
        if(!empty($product_faq) && count($product_faq) > 0){
            return $product_faq;
        }
        return Faq::whereNull('product_id')->where('status', true)->get();
    }

    public function getGalleryAttribute($value){
        $gallery =  json_decode($value, true);
        if(is_array($gallery)){
            usort($gallery, function ($a, $b) {
                return $a['position'] - $b['position'];
            });
        }

        if(!$gallery){
            $thumbnail = url('images/placeholder.png');
            $original = url('images/placeholder.jpg');
            $gallery[0]['original'] = $original;
            $gallery[0]['thumbnail'] = $thumbnail;
            $gallery[0]['id'] = 1;
        }
        return $gallery;
    }
    public function getImageAttribute($value){
        if(isset($this->gallery) && is_array($this->gallery)){
            if(isset( $this->gallery[0]['thumbnail'])){
                return $this->gallery[0]['thumbnail'];
            }
        }
        return $value;
    }
    public function getDefaultCategoryDetailAttribute(){
        $category= DB::table('categories')->where('id',$this->default_category)->select('id','name','slug')->first();
        return $category;
    }
    public function getInStockAttribute($value){
        if($this->old_id != 0  && $value == 0){
            return 0;
        }
        $totalQtyInStock = $this->attributeCombinations()
            ->where('quantity', '>', 0)
            ->count();

        if($this->quantity > 0){
            return 1;
        }
        return $totalQtyInStock > 0 ? 1 : 0;
    }

    public function getDimensionAttribute($value){
        return json_decode($value);
    }

    public function getDimensionImageAttribute($value){
        return json_decode($value);
    }

    public function getProductColorCombinationAttribute(){
        $attribute_id = 4;
        $attributeCombination = $this->attributeCombinations->where('product_id',$this->id);
        $arr = [];
        foreach($attributeCombination as $att_comb){
            // if($att_comb->combinations->attribute_id == $attribute_id){
                foreach($att_comb->combinations as $comb){
                    if($comb->attribute->name == 'Color'){
                        $arr[] = $comb->attributeValue;
                    }
                }
        }
        return array_values(array_unique($arr));
    }

    public function getCouponAttribute(){
        $coupon = Coupon::where('active_from', '<=', now())
            ->where('expire_at', '>=', now())
            ->where('product_id', $this->id)
            ->first();

        // If no coupon is found with the specified product_id, try to find a common coupon
        if (!$coupon) {
            $coupon = Coupon::where('active_from', '<=', now())
            ->where('expire_at', '>=', now())
            ->where('category_id', $this->default_category)
            ->where('product_id',null)
            ->first();

            if(!$coupon){
                $coupon = Coupon::where('active_from', '<=', now())
                ->where('expire_at', '>=', now())
                ->whereNull('product_id')
                ->whereNull('category_id')
                ->first();
            }
        }
        return $coupon;
    }

    public function getUrlAttribute(){
        return 'product/'.$this->id.'-'.$this->slug;
    }

}
