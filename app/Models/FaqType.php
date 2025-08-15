<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqType extends Model
{
    protected $appends=['selected_icon'];
    protected $hidden=['status','created_at','updated_at'];
    use HasFactory;

    public function getIconAttribute($value){
        return asset('images/help_icon/'.$value);
    }

    public function getSelectedIconAttribute(){
        // get original value of icon
        return asset('images/help_icon/Selected_'.$this->getRawOriginal('icon'));
    }
}
