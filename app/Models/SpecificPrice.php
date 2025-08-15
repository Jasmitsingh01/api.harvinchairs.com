<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;
class SpecificPrice extends Model
{
    use HasFactory;
    protected $table = 'specific_prices';
    protected $guarded = [];
    protected $dates = [
        'from',
        'to',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $appends = [
    'discounted_amount'
    ];
    public function getDiscountedAmountAttribute(){
        if ($this->reduction_type == 'percentage') {
            return($this->price * $this->reduction)/100;
        }
        else if($this->reduction_type == "dollar"){
            return ($this->price) - ($this->reduction);
        }
        return false;
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->with('attributeCombinations');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function product_attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    public function getFromAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    // public function setFromAttribute($value)
    // {
    //     $this->attributes['from'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    // }

    public function getToAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

}
