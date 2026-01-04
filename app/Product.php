<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'product_category_id',
        'sku',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'stock',
        'is_deleted',
        'is_active',
    ];
}
