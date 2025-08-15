<?php

namespace App\Database\Models;

use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use App\Traits\TranslationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\ProductAttributeCombination;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class AttributeValue extends Model
{
    use TranslationTrait, Sluggable,SoftDeletes;
    // use Searchable;

    protected $table = 'attribute_values';

    public $guarded = [];

    protected $appends = [
        'product_count'
        // 'translated_languages'
    ];
    // protected $casts = [
    //     'meta_keywords' => 'json',
    // ];
    protected $casts = [
        'cover_image' => 'json',
        'faric_image' => 'json',
    ];


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
                'source' => 'value'
            ]
        ];
    }

    /**
     * @return BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }


    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'attribute_product');
    }
    /**
     * @return HasMany
     */
    public function combinations(): HasMany
    {
        return $this->hasMany(ProductAttributeCombination::class,'attribute_value_id','id')->with('productAttribute');
    }
    public function getMetaKeywordsAttribute($value){
        return explode(',',$value);
    }
    // public function getCoverImageAttribute($value){
    //     return json_decode($value,true);
    // }
    public function getCoverImageAttribute($value){
        return json_decode($value,true);
    }

    public function getFabricImageAttribute($value){
        return json_decode($value,true);
    }

    public function productAttributes()
    {
        return $this->hasManyThrough(ProductAttribute::class, ProductAttributeCombination::class, 'attribute_value_id', 'id', 'id', 'product_attribute_id');
    }

    public function getProductCountAttribute()
    {
        return $this->productAttributes()
            ->selectRaw('COUNT(DISTINCT product_attributes.product_id) as product_count')
            ->pluck('product_count')
            ->first();
    }
}
