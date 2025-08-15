<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Menuitem extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $table='menuitems';
    // public $append ='banner_image';
    // protected $casts = [
    //     'banner_image'    =>'json'
    // ];
    protected $fillable = ['title','banner_image','is_mega_menu','active','category_id','color_code','name','slug','type','target','menu_id','is_external','created_at','updated_at'];
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getUrlAttribute(){
        if(!empty($this->category_id)){
            $this->slug = '';
            if(isset($this->category->parent) && $this->category->parent == 0){
                $this->slug.= config('constants.CATEGORY_SLUG_NAME').'/'.$this->category->id.'-'.$this->category->slug;

            }
            if(isset($this->category->parent) && $this->category->parent > 0){
                $categoryParent = Category::where('id',$this->category->parent)->first();
                if(!empty($categoryParent)){
                    $this->slug.= config('constants.CATEGORY_SLUG_NAME').'/'.$categoryParent->id.'-'.$categoryParent->slug.'/'.$this->category->id.'-'.$this->category->slug;
                }

            }
            if((isset($this->category->parent) && $this->category->parent > 0) || (isset($this->category->children) && count($this->category->children) <= 0)){
                $this->slug.= '/'.config('constants.PRODUCT_SLUG_NAME');
            }
        }

        // if($this->type == 'post'){
        //     $this->slug = config('constants.BLOG_SLUG_NAME').'/'.$this->slug;
        // }
        return $this->slug;
    }
}
