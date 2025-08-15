<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class OrderCarrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'carrier_id',
        'shipping_date',
        'shipping_cost',
        'tracking_number',
        'url',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function carrierName()
    {
        return $this->hasOne(Carrier::class,'id','carrier_id');
    }
}
