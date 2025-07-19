<?php

namespace App\Models;

use App\Database\Models\Attribute;
use App\Database\Models\AttributeValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAttributeCombination extends Model
{

    protected $fillable = [
        'product_attribute_id',
        'attribute_id',
        'attribute_value_id'
    ];
    use HasFactory;
    protected $table = 'product_attribute_combinations';
    /**
     * @return BelongsTo
     */
    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }
    public function product_attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
    public function getAttributeValueTitleAttribute()
    {
        return $this->attributeValue()->pluck('value')->first();
    }
    public function getAttributeTitleAttribute()
    {
        return $this->attribute()->pluck('name')->first();
    }

}
