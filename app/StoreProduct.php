<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreProduct
 * @package App
 */
class StoreProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['store_id', 'product_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(StoreProductDetails::class);
    }
}
