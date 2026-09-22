<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'base_price', 'image', 'mockup_template_image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function finishings()
    {
        return $this->hasMany(Finishing::class);
    }
}
