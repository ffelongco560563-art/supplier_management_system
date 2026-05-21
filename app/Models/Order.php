<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

        protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'phone_number',
        'address',
        'message_instructions',
        'total_price',
        'status',
        'decline_reason',
        'product_details',
        'truck_id',
        'payment_amount',
        'payment_status',
        'payment_status',
        'payment_method',
        'payment_date',
    ];

    protected $casts = [
        'product_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}