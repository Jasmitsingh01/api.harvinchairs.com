<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductFeature extends Model
{
    use HasFactory, SoftDeletes;
    public $appends = [
        'feature_position'
    ];
    protected $fillable = ['product_id','feature_value_id'];
    public function featureValue(): BelongsTo
    {
        return $this->BelongsTo(FeatureValue::class, 'feature_value_id','id');
    }
    public function getFeaturePositionAttribute(){
        if($this->featureValue){
            return $this->featureValue->feature()->pluck('position')->first();
        }
        return null;
    }
}
