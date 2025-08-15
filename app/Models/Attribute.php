<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory,SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        Attribute::creating(function ($model) {
            $model->position = Attribute::max('position') + 1;
        });
    }

    public $table = 'attributes';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'slug',
        'language',
        'position',
        'is_fabric',
        'name',
        'public_name',
        'group_type',
        'shop_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }
}
