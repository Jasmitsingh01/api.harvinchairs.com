<?php

namespace App\Models;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'order_product';
    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // public function getGstPercentageAttribute($value)
    // {
    //     if($value != null){
    //         return $value;
    //     }
    //     return 0;
    // }

    // public function getPriceWithoutGstAttribute($value)
    // {
    //     if($value != null){
    //         return $value;
    //     }
    //     return $this->unit_price;
    // }
}
