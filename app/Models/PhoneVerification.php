<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'email',
        'phone_number',
        'country_code',
        'otp',
        'is_verified'
    ];
    
    protected $hidden = ['created_at','updated_at'];
}
