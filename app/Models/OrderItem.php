<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'material_id', 'finishing_id',
        'quantity', 'custom_width', 'custom_height', 'raw_design_file',
        'preview_mockup_file', 'subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function finishing()
    {
        return $this->belongsTo(Finishing::class);
    }
}
