<?php

namespace App\Database\Models;

use App\Models\Zone;
use App\Models\Country;
use App\Models\Zipcode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use SoftDeletes;
    protected $table = 'address';

    public $guarded = [];

    protected $casts = [
        'address' => 'json',
    ];

    protected $hidden= [
        'title',
        'address',
        'country',
        'zipcode',
        'zone_id'
    ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
     /**
     * @return BelongsTo
     */
    public function zipcode(): BelongsTo
    {
        return $this->belongsTo(Zipcode::class ,'zipcode','zip_code');
    }
     /**
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country', 'name');
    }
     /**
     * @return BelongsTo
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class ,'zone_id','id');
    }

    public function getFullAddressAttribute(){
        $address = $this->society.','.$this->area;
        if(!empty($this->landmark)){
            $address.= ', '.$this->landmark;
        }
        if(!empty($this->city)){
            $address.= ', '.$this->city;
        }
        if(!empty($this->postal_code)){
            $address.= ' - '.$this->postal_code;
        }
        if(!empty($this->state)){
            $address.= ', '.$this->state;
        }

        return $address;
    }
}
