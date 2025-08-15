<?php

namespace App\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'countries';
    public $guarded = [];
    public $timestamps = false;
    protected $dates = [
        'created_at',
        'updated_at',
       'deleted_at',
    ];

    protected $fillable = [
       'shortname',
       'name',
       'zone_id',
       'phonecode',
       'zip_code_format',
       'need_zip_code',
       'need_identification_number',
       'contains_states',
       'active',
       'created_at',
       'updated_at',
       'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
