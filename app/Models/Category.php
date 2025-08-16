<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Database\Models\Type;

class Category extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;
    protected static function boot()
    {
        parent::boot();

        Category::creating(function ($model) {
            $model->position = Category::max('position') + 1;
        });
    }


    public $table = 'categories';

    protected $appends = [
        'cover_image',
        'thumbnail_image',
        'collection_image',
    ];
    protected $hidden = ['media','cover_image','thumbnail_image',];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'details',
        'slug',
        'language',
        'position',
        'parent',
        'type_id',
        'cgst_rate',
        'sgst_rate',
        'image',
        'icon',
        'enabled',
        'is_home',
        'is_showcase',
        'meta_description',
        'meta_title',
        'meta_keywords',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
     /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent', 'id')->where('language',config('shop.default_language'))->orderBy('position')->with('children');
    }
    public function type(): BelongsTo
    {
        return $this->belongsTo(\App\Database\Models\Type::class, 'type_id');
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    /**
     * @return HasMany
     */
    // public function children()
    // {
    //     return $this->hasMany('App\Database\Models\Category', 'parent', 'id')->where('language',config('shop.default_language'))->orderBy('position')->with('children')->withCount('products');
    // }

    /**
     * @return HasMany
     */
    public function subCategories()
    {
        return $this->hasMany('App\Database\Models\Category', 'parent', 'id')->with('subCategories', 'parent')->withCount('products');
    }

    /**
     * @return HasOne
     */
    public function parent()
    {
        return $this->hasOne('App\Database\Models\Category', 'id', 'parent')->with('parent');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
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

    public function getThumbnailImageAttribute()
    {
        // if(isset($this->old_id) && $this->old_id != 0 ){
        //     $original =  "https://ecomapi.foodnextdoor.shop".'/image/c/'.$this->old_id.'.jpg';
        //     $thumbnail =  "https://ecomapi.foodnextdoor.shop/"."/image/c/category_".$this->old_id."-thumb.jpg";
        //     $img_array = ['url'=>$original,'thumbnail'=>$thumbnail,'preview'=>$thumbnail];
        //     return ($img_array);
        // }
        // else{
            try {
                $file = $this->getMedia('thumbnail_image')->last();
                if ($file) {
                    $file->url       = $file->getUrl();
                    $file->thumbnail = $file->getUrl('thumb');
                    $file->preview   = $file->getUrl('preview');
                }
                return $file;
            } catch (\Exception $e) {
                // Return null if there's an error accessing media
                return null;
            }
        // }
        // dd('out');

    }
    public function getImageAttribute($value)
    {
        try {
            $file = $this->getMedia('cover_image')->last();

            $urls = new \stdClass();

            if ($file) {
                $urls->url       = $file->getUrl();
                $urls->thumbnail = $file->getUrl('thumb');
                $urls->preview   = $file->getUrl('preview');
            }

            return $urls;
        } catch (\Exception $e) {
            // Return empty object if there's an error accessing media
            $urls = new \stdClass();
            $urls->url = null;
            $urls->thumbnail = null;
            $urls->preview = null;
            return $urls;
        }
    }

    public function getIconAttribute($value){
        try {
            $file = $this->getMedia('thumbnail_image')->last();
            $urls = new \stdClass();

            if ($file) {
                $urls->url       = $file->getUrl();
                $urls->thumbnail = $file->getUrl('thumb');
                $urls->preview   = $file->getUrl('preview');
            }

            return $urls;
        } catch (\Exception $e) {
            // Return empty object if there's an error accessing media
            $urls = new \stdClass();
            $urls->url = null;
            $urls->thumbnail = null;
            $urls->preview = null;
            return $urls;
        }
    }
    public function getCollectionImageAttribute($value){
        try {
            $file = $this->getMedia('collection_image')->last();
            $urls = new \stdClass();

            if ($file) {
                $urls->url       = $file->getUrl();
                $urls->thumbnail = $file->getUrl('thumb');
                $urls->preview   = $file->getUrl('preview');
            }

            return $urls;
        } catch (\Exception $e) {
            // Return empty object if there's an error accessing media
            $urls = new \stdClass();
            $urls->url = null;
            $urls->thumbnail = null;
            $urls->preview = null;
            return $urls;
        }
    }

    public function getCollectionImageUrlAttribute()
    {
        try {
            $file = $this->getMedia('collection_image')->last();
            if ($file) {
                $file->url       = $file->getUrl();
                $file->thumbnail = $file->getUrl('thumb');
                $file->preview   = $file->getUrl('preview');
            }
            return $file;
        } catch (\Exception $e) {
            // Return null if there's an error accessing media
            return null;
        }
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_categories');
    }

    public function getUrlAttribute(){
        $url = '';
        if($this->parent == 0){
            $url.= config('constants.CATEGORY_SLUG_NAME').'/'.$this->id.'-'.$this->slug;

        }
        if($this->parent > 0){
            $categoryParent = Category::where('id',$this->parent)->first();
            if(!empty($categoryParent)){
                $url.= config('constants.CATEGORY_SLUG_NAME').'/'.$categoryParent->id.'-'.$categoryParent->slug.'/'.$this->id.'-'.$this->slug;
            }

        }
        if($this->parent > 0 || count($this->children) <= 0){
            $url.= '/'.config('constants.PRODUCT_SLUG_NAME');
        }
        return $url;
    }

    public function getDescriptionAttribute(){
        return strip_tags($this->details);
    }
}
