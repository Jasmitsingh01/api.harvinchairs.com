<?php

namespace App\Database\Models;

use Laravel\Scout\Searchable;
use App\Traits\TranslationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Category extends Model
{
    use TranslationTrait, Sluggable, SoftDeletes;
    // use Searchable;

    protected $table = 'categories';

    public $guarded = [];

    protected $casts = [
        'image' => 'json',
        'icon' => 'json',
        // 'meta_keywords' => 'json',

    ];

    protected $appends = [
        'parent_id',
        // 'translated_languages'
    ];

    protected $hidden = [
        'old_id',
        'type_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_home'
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getParentIdAttribute()
    {
        if (isset($this->attributes['parent'])) {
            return $this->parent;
        }
    }


    public function scopeWithUniqueSlugConstraints(Builder $query, Model $model): Builder
    {
        return $query->where('language', $model->language);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Database\Models\Category', 'parent', 'id')->where('language',config('shop.default_language'))->where('enabled',true)->orderBy('position')->with('children')->withCount('products');
    }

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
    public function getIconAttribute($value){
        if(isset($this->old_id) && $this->old_id != 0 ){
            $original =  config('app.url').'/image/c/'.$this->old_id.'.jpg';
            $thumbnail =   config('app.url')."/image/c/category_".$this->old_id."-thumb.jpg";
            $img_array = ['original'=>$original,'thumbnail'=>$thumbnail];
            return ($img_array);
        }
        return json_decode($value,true);
    }
    public function getImageAttribute($value){
        if(isset($this->old_id) && $this->old_id != 0 ){
            $original =  config('app.url').'/image/c/'.$this->old_id.'.jpg';
            $thumbnail =   config('app.url')."/image/c/category_".$this->old_id."-thumb.jpg";
            $img_array = ['original'=>$original,'thumbnail'=>$thumbnail];
            return ($img_array);
        }
        return json_decode($value,true);
    }
    public function getMetaKeywordsAttribute($value){
        return explode(',',$value);
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
