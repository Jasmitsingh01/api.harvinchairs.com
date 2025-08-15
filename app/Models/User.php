<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use App\Models\Address;
use App\Models\Wishlist;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class User extends Authenticatable
{
    use  Notifiable, HasFactory;

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'is_admin',
        'notification',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    // public function getIsAdminAttribute()
    // {
    //     return $this->roles()->where('id', 1)->exists();
    // }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'customer_id');
    }
    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class, 'owner_id');
    }
    public function managed_shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
    public function address(): HasMany
    {
        return $this->hasMany(Address::class, 'customer_id');
    }
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    public function wishLists(): HasOne
    {
        return $this->hasOne(Wishlist::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'admin_role_user');
    }
    public function getFullNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }
}
