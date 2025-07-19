<?php

namespace App\Database\Models;

use App\Models\FeatureValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Feature extends Model
{
    use SoftDeletes,Searchable;

    protected $table = 'features';
    protected $appends = [
        'feature_values'
    ];

    public $guarded = [];
    public function featureValues(): hasMany
    {
        return $this->hasMany(FeatureValue::class,'feature_id');
    }
    public function getFeatureValuesAttribute()
    {
        return $this->featureValues()->where('language',$this->language)->where('is_custom',0)->get();
    }
}
