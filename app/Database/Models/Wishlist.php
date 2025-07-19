<?php

namespace App\Database\Models;

use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
class Wishlist extends Model
{
    protected $table = 'wishlists';

    protected $hidden = ['updated_at','created_at'];

    public $guarded = [];

    protected $data_array = ['product_id'];
    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            // Flush the cache when a product is created
            Cache::flush();
        });

        static::updated(function ($product) {
            // Flush the cache when a product is updated
            Cache::flush();
        });

        static::deleted(function ($product) {
            // Flush the cache when a product is deleted
            Cache::flush();
        });
    }
    /**
     * Get the product that owns the wishlist.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function combination()
    {
        return $this->belongsTo(ProductAttribute::class,'product_attribute_id','id');
    }

    /**
     * Get the user that owns the comment.
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
