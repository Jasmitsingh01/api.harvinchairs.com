<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class OurStore extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'our_stores';

    protected $appends = [
        'gallery',
        'store_images',
    ];
    protected $hidden = ['media',  'gallery'];
    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'InActive',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'short_line',
        'address',
        'opening_hours',
        'contact_number',
        'city',
        'pincode',
        'latitude',
        'longitude',
        'status',
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

    public function getGalleryAttribute()
    {
        $files = $this->getMedia('gallery');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }
    public function getStoreImagesAttribute()
    {
        $files = $this->getMedia('gallery');
        $imageArray = [];

        $files->each(function ($item) use (&$imageArray) {
            $imageDetails = [
                'url'       => $item->getUrl(),
                'thumbnail' => $item->getUrl('thumb'),
                'preview'   => $item->getUrl('preview'),
            ];

            $imageArray[] = $imageDetails;
        });

        return $imageArray;
    }

}
