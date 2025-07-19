<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class Menu extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;
    protected $table='menus';
    protected $fillable = ['title','is_mega_menu','location','content','created_at','updated_at'];
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
