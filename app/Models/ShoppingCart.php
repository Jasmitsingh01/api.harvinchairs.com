<?php

// namespace App\Models;

// use DateTimeInterface;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

// class ShoppingCart extends Model
// {
//     use SoftDeletes, HasFactory;

//     public $table = 'carts';

//     protected $dates = [
//         'created_at',
//         'updated_at',
//         'deleted_at',
//     ];

//     protected $fillable = [
//         'user_id',
//         'delivery_address',
//         'billing_address',
//         'total',
//         'language',
//         'is_empty',
//         'is_confirm',
//         'created_at',
//         'updated_at',
//         'deleted_at',
//     ];

//     protected function serializeDate(DateTimeInterface $date)
//     {
//         return $date->format('Y-m-d H:i:s');
//     }

//     public function user()
//     {
//         return $this->belongsTo(User::class, 'user_id');
//     }
// }
