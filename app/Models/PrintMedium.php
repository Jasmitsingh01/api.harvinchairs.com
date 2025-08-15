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

class PrintMedium extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'print_media';

    protected $appends = [
        'image',
        'print_image',
    ];
    protected $hidden = ['media',  'image'];

    public const IS_PRINT_MEDIA_RADIO = [
        '1' => 'Yes',
        '0' => 'No',
    ];

    public const STATUS_RADIO = [
        '1' => 'Active',
        '0' => 'InActive',
    ];

    protected $dates = [
        'publish_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'publish_date',
        'is_print_media',
        'page_link',
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

    public function getPublishDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setPublishDateAttribute($value)
    {
        $this->attributes['publish_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
    public function getPrintImageAttribute($value){
        $file = $this->getMedia('image')->last();
        $urls = new \stdClass();

        if ($file) {
            $urls->url       = $file->getUrl();
            $urls->thumbnail = $file->getUrl('thumb');
            $urls->preview   = $file->getUrl('preview');
        }

        return $urls;
    }
}
