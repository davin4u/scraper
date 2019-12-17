<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'city_id',
        'store_id',
        'price',
        'old_price',
        'currency'
    ];
}
