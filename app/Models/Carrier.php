<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrier extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'carriers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'reference_id',
        'name',
        'url',
        'active',
        'shipping_handling',
        'range_behavior',
        'is_module',
        'is_free',
        'shipping_external',
        'need_range',
        'external_module_name',
        'shipping_method',
        'position',
        'max_width',
        'max_height',
        'max_depth',
        'max_weight',
        'grade',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
