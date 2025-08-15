<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Banner extends Model
{
    use SoftDeletes;
    protected $table = 'banners';

    public $guarded = [];

    protected $casts = [
        'image'   => 'json',
        'banner'    =>'json'
    ];
    public function getBannerAttribute($value){

        return json_decode($value,true);
    }
}
