<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductEnquire extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'enquiries';

    protected $appends = [
        'product_img',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'customer_name',
        'customer_email',
        'subject',
        'message',
        'product_title',
        'product_price',
        'product_id',
        'product_attributes_id',
        'url',
        'notification',
        'created_at',
        'updated_at',
        'deleted_at',
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

    public function getProductImgAttribute()
    {
        return $this->getMedia('product_img')->last();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_attributes()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attributes_id');
    }
}
