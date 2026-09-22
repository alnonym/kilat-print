<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finishing extends Model
{
    protected $fillable = ['product_id', 'name', 'price_modifier'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
