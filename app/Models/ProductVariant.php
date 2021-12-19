<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = [];

    public function product_variant_price()
    {
        return $this->hasMany(ProductVariantPrice::class, 'product_variant_one', 'id');
    }
}