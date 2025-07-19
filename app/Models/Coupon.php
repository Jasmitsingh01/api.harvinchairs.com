<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Coupon extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'coupons';

    protected $appends = [
        'image',
        'is_valid',
        'image_url'
    ];
    // protected $casts = [
    //     'image'   => 'json',
    // ];

    public const DISCOUNT_TYPE_SELECT = [
        'percentage' => 'Percentage',
        'amount'     => 'Amount',
    ];

    protected $dates = [
        'active_from',
        'expire_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'coupon_title',
        //'customer_id',
        'language',
        'description',
        'max_redemption_per_user',
        'discount',
        'discount_type',
        'type',
        'active_from',
        'expire_at',
        'min_amount',
        // 'image',
        'max_usage',
        'free_shipping',
        'free_shipping_min_amount',
        'is_used',
        'created_at',
        'updated_at',
        'deleted_at',
        'category_id',
        'product_id'
    ];

    protected $hidden = [
        'image','media'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function getImageAttribute()
    {
        $file = $this->getMedia('image')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }
        return $file;
    }

    public function getImageUrlAttribute()
    {
        $file = $this->getMedia('image')->last();

        $urls = new \stdClass();

        if ($file) {
            $urls->url       = $file->getUrl();
            $urls->thumbnail = $file->getUrl('thumb');
            $urls->preview   = $file->getUrl('preview');
            return $urls;
        }
        return null;

    }

    // public function getActiveFromAttribute($value)
    // {
    //     return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    // }

    public function setActiveFromAttribute($value)
    {
        $this->attributes['active_from'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    // public function getExpireAtAttribute($value)
    // {
    //     return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    // }

    public function setExpireAtAttribute($value)
    {
        $this->attributes['expire_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getIsValidAttribute()
    {
        return Carbon::now()->between($this->active_from, $this->expire_at);
    }
}
