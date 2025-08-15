<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Feature extends Model
{
    use SoftDeletes, HasFactory;
    protected static function boot()
    {
        parent::boot();

        Feature::creating(function ($model) {
            $model->position = Feature::max('position') + 1;
        });
    }
    public $table = 'features';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'language',
        'position',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function featureValues(): hasMany
    {
        return $this->hasMany(FeatureValue::class,'feature_id');
    }
}
