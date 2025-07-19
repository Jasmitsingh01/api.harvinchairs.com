<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdvertisementBanner extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    protected $appends = [
        'banner',
        'banner_image',
        'link_url'
    ];

    public $table = 'advertisement_banners';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'category_id',
        'active',
        'link',
        'link_open',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'media',
        'banner',
        'link',
        'created_at',
        'updated_at',
        'deleted_at'
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

    public function getBannerAttribute()
    {
        $file = $this->getMedia('banner')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getBannerImageAttribute($value){
        $file = $this->getMedia('banner')->last();

        $urls = new \stdClass();

        if ($file) {
            $urls->url       = $file->getUrl();
            $urls->thumbnail = $file->getUrl('thumb');
            $urls->preview   = $file->getUrl('preview');
        }

        return $urls;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getLinkUrlAttribute()
    {
        return $this->link;
    }
}
