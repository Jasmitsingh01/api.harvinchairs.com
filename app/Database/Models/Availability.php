<?php

namespace App\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\TranslationTrait;

class Availability extends Model
{
    use TranslationTrait;

    protected $table = 'availabilities';

    public $guarded = [];
}
