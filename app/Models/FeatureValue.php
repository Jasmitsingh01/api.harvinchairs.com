<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Database\Models\Feature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeatureValue extends Model
{
    use HasFactory, SoftDeletes,Searchable;
    public $guarded = [];
    public $appends = [
        'feature_title'
    ];
    public function feature(): BelongsTo
    {
        return $this->BelongsTo(Feature::class, 'feature_id','id');
    }
    public function getFeatureTitleAttribute(){
        return $this->feature()->pluck('title')->first();
    }
}
