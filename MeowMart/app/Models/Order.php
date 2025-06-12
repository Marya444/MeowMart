<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'items',
        'subtotal',
        'discount',
        'total',
        'discount_type',
        'customer_email',
        'payment_method',
        'user_id',
    ];
        protected $casts = [
        'items' => 'array', 
        'created_at' => 'datetime', 
        'updated_at' => 'datetime', 
    ];

    public function user()
    {
        // Assuming your User model is in App\Models\User
        return $this->belongsTo(User::class);
    }
}
