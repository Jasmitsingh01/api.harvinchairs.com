<?php

namespace App\Models;

use App\Database\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartProduct extends Model
{
    use HasFactory, SoftDeletes;
    public $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $appends = ['all_combination','product','discount_price','select_quantity','assembly_charges'];
    public function product(): hasOne
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
    public function getProductAttribute()
    {
        return $this->product()->first()->makeHidden(['product_combinations','product_features','reviews','rating_count','my_review','postcodes']);
    }
    public function productAttribute(): hasOne
    {
        return $this->hasOne(ProductAttribute::class,'id','product_attribute_id');
    }

    public function getAllCombinationAttribute()
    {
        return $this->productAttribute()->first()->all_combination;
    }
    public function getProductImageAttribute()
    {
        return $this->productAttribute()->first()->images == null ? $this->product()->first()->gallery : $this->productAttribute()->first()->images;
    }

    public function getDiscountPriceAttribute(){
        return $this->base_price - $this->unit_price;
    }

    public function getSelectQuantityAttribute(){
        return $this->quantity;
    }

    public function getAssemblyChargesAttribute(){
        return $this->product->assembly_charges;
    }
}
