<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = ['order_id', 'operator_id', 'status', 'notes'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
