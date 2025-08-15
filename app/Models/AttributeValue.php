<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class AttributeValue extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory,SoftDeletes;
    protected static function boot()
    {
        parent::boot();

        AttributeValue::creating(function ($model) {
            $model->position = AttributeValue::max('position') + 1;
        });
    }


    public $table = 'attribute_values';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'slug',
        'attribute_id',
        'value',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'cover_image',
        'color_code',
        'is_color',
        'fabric_image',
        'description',
        'language',
        'position',
        'meta',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'cover_image',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 70, 70);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getCoverImageAttribute()
    {
        $file = $this->getMedia('cover_image')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }
    public function getFabricImageAttribute()
    {
        $file = $this->getMedia('fabric_image')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }
}
