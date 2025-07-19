<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportFileDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_file_name',
        'import_filename',
        'status',
    ];
}
