<?php

namespace App\Models;

use DateTimeInterface;
use App\Database\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;



class Menus extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia, HasFactory;

    public $table = 'menu';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'parent',
        'position',
        'active',
        'is_cms',
        'is_category',
        'is_mega_menu',
        'banner',
        'text_bold',
        'page_link',
        'color_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_menu', 'menu_id', 'category_id')->orderBy('category_menu.position');
    }
    public function children()
    {
        return $this->hasMany('App\Models\Menus', 'parent_id', 'id')->with('children');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }


    public function getBannersAttribute()
    {
        $file = $this->getMedia('banner')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

}
