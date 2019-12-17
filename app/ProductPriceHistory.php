<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'price',
        'usd_price',
        'old_price',
        'scraped_at'
    ];
}
