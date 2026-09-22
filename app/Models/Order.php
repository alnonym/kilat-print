<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'total_price', 'payment_status',
        'payment_proof', 'shipping_method', 'delivery_address', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function production()
    {
        return $this->hasOne(Production::class);
    }
}
