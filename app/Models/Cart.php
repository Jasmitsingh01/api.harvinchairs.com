<?php

namespace App\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Cart extends Model
{
    use HasFactory, SoftDeletes;
    public $guarded = [];
    public $table = 'carts';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'old_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function cartProducts(): hasMany
    {
        return $this->hasMany(CartProduct::class,'cart_id')->with('productAttribute');
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function orderShippingAddress()
    {
        return $this->belongsTo(Address::class,'delivery_address_id');
    }
    public function orderBillingAddress()
    {
        return $this->belongsTo(Address::class,'billing_address_id');
    }
}

