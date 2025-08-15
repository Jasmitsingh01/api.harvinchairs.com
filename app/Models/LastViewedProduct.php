<?php

namespace App\Models;

use App\Database\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LastViewedProduct extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'last_viewed_products';
    public $guarded = [];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }

}
