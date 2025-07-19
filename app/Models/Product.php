<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Product extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'products';
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
    protected $appends = [
        'image',
        'video',
        'gallery',
        'category_title'
    ];
    protected $casts = [
        'cover_image' => 'json',
        // 'dimension' => 'json',
    ];

    public const STATUS_RADIO = [
        'publish' => 'Publish',
        'draft'   => 'Draft',
    ];
    public const ACTIVE_RADIO = [
        '1' => 'Active',
        '0'   => 'Inactive',
    ];

    public const PRODUCT_TYPE_RADIO = [
        'simple'   => 'Simple',
        'variable' => 'Variable',
    ];

    protected $dates = [
        'from_date',
        'to_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $guarded = [];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function getGalleryAttribute($value)
    {
        $gallery =  json_decode($value, true);
        if(is_array($gallery)){
            usort($gallery, function ($a, $b) {
                return $a['position'] - $b['position'];
            });
        }
        return $gallery;
    }

    public function getImageAttribute($value)
    {
        if (isset($this->gallery) && is_array($this->gallery)) {
            if (isset($this->gallery[0]['thumbnail'])) {
                return $this->gallery[0]['thumbnail'];
            }
        }
        return $value;
    }

    public function getVideoAttribute()
    {
        $file = $this->getMedia('video')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }
    public function getDimensionImageAttribute()
    {
        $file = $this->getMedia('dimension_image')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    // public function getGalleryAttribute()
    // {
    //     $files = $this->getMedia('gallery');
    //     $files->each(function ($item) {
    //         $item->url       = $item->getUrl();
    //         $item->thumbnail = $item->getUrl('thumb');
    //         $item->preview   = $item->getUrl('preview');
    //     });

    //     return $files;
    // }

    public function getFromDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFromDateAttribute($value)
    {
        $this->attributes['from_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getToDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setToDateAttribute($value)
    {
        $this->attributes['to_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function default_category()
    {
        return $this->belongsTo(Category::class, 'default_category');
    }

    public function default_product_category()
    {
        return $this->belongsTo(Category::class, 'default_category');
    }

    public function getCategoryTitleAttribute()
    {
        return $this->default_category()->pluck('name')->first();
    }
    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
    public function attributeCombinations(): hasMany
    {
        return $this->hasMany(ProductAttribute::class, 'product_id')->with('attributeCombinations');
    }
    public function attributeCombinationsShort()
    {
        $productAttributes = DB::table('product_attributes')->where('product_id', $this->id)->select('product_attributes.id', 'product_attributes.product_id')->pluck('product_attributes.id');
        $cobinations = DB::table('product_attribute_combinations')->whereIn('product_attribute_id', $productAttributes);
        return $cobinations;
    }
    public function getProductCombinationsAttribute()
    {
        return $this->attributeCombinations()->orderBy('position')->get();
    }
    public function getCombinationsAttribute()
    {
        return $this->attributeCombinationsShort()->groupby('attribute_value_id')->distinct()->get(['attribute_value_id', 'product_attribute_id']);
    }
    public function specificPrices(): hasMany
    {
        return $this->hasMany(SpecificPrice::class, 'product_id')->with(['customer', 'product_attribute']);
    }
    /**
     * @return BelongsToMany
     */
    public function productFeatures(): hasMany
    {
        return $this->hasMany(ProductFeature::class, 'product_id', 'id')->with('featureValue');
    }
    public function getProductFeaturesAttribute()
    {
        return $this->productFeatures()->get(['id', 'feature_value_id']);
    }
    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
        ->withPivot('order_quantity', 'unit_price', 'subtotal')
        ->withTimestamps();
    }

    // public function lastViewedProducts(): belongsToMany
    // {
    //     return $this->belongsToMany(LastViewedProduct::class,'product_id','id');
    // }

    // public function lastViewedProducts(): hasMany
    // {
    //     return $this->hasMany(LastViewedProduct::class,'product_id');
    // }

    public function lastViewed()
    {
        return $this->hasMany(LastViewedProduct::class, 'product_id');
    }

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class,'cart_products')
        ->withPivot('product_id')
        ->withTimestamps();
    }
    public function getPostcodesAttribute($value)
    {
        return json_decode($value,true);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'product_id');
    }


}
