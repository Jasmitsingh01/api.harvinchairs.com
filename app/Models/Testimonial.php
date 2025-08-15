<?php

namespace App\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;

class Testimonial extends Model implements HasMedia
{
    use HasFactory, SoftDeletes,InteractsWithMedia;
    protected $guarded = [];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'customer_id',
        'author_name',
        'author_info',
        'author_url',
        'author_email',
        'rating',
        'content',
        'is_featured',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'author_image_url',
    ];

    protected $hidden = [
        'media',
        'author_image',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getAuthorImageAttribute()
    {
        $file = $this->getMedia('author_image')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getAuthorImageUrlAttribute()
    {
        $file = $this->getMedia('author_image')->last();

        $urls = new \stdClass();

        if ($file) {
            $urls->url       = $file->getUrl();
            $urls->thumbnail = $file->getUrl('thumb');
            $urls->preview   = $file->getUrl('preview');
        }

        return $urls;
    }
}
