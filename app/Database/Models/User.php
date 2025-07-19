<?php

namespace App\Database\Models;

use App\Models\Cart;
use App\Models\Role;
use App\Enums\RoleType;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HasApiTokens;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password', 'is_active', 'shop_id','newsletter','birthdate','gender', 'two_factor_expires_at',
        'two_factor_code','phone','phone_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = [
        'is_cart',
    ];

    protected static function boot()
    {
        parent::boot();
        // Order by updated_at desc
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('updated_at', 'desc');
        });
        User::creating(function ($model) {
            $model->notification = true;
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * @return HasMany
     */
    public function address(): HasMany
    {
        return $this->hasMany(Address::class, 'customer_id');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id')->with(['products.variation_options', 'status']);
    }
    public function generateTwoFactorCode()
    {
        $this->timestamps            = false;

        $this->two_factor_code       = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(15)->format('Y-m-d H:i:s');
        $this->save();
    }

    public function resetTwoFactorCode()
    {
        $this->timestamps            = false;
        $this->two_factor_code       = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'customer_id');
    }

    /**
     * @return HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'customer_id');
    }

    /**
     * @return HasMany
     */
    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class, 'owner_id');
    }

    /**
     * @return HasMany
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Shop::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function managed_shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * @return HasMany
     */
    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function ordered_files(): HasMany
    {
        return $this->hasMany(OrderedFiles::class, 'customer_id');
    }

    /**
     * Follow shop
     *
     * @return BelongsToMany
     */
    public function follow_shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'user_shop');
    }


    /**
     * Follow shop
     *
     * @return HasMany
     */
    public function payment_gateways(): HasMany
    {
        return $this->HasMany(PaymentGateway::class, 'user_id');
    }
    public function getIsCartAttribute(){
        $cart = Cart::where('user_id',$this->id)->where('is_confirm',false)->first();
        if(isset($cart) && !empty($cart)){
            return $cart->id;
        }
        return false;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'admin_role_user');
    }
}
