<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'domain_id',
        'category_id',
        'brand_id',
        'name',
        'sku',
        'url'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pricesHistory()
    {
        return $this->hasMany(ProductPriceHistory::class);
    }
}
