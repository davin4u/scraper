<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreProductDetails
 * @package App
 */
class StoreProductDetails extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'store_product_id',
        'name',
        'sku',
        'price',
        'old_price',
        'is_available',

        /* delivery information */
        'delivery_text',

        /* if we can determine how many days store needs to deliver a product */
        'delivery_days',
        'delivery_price',

        /* concatenated benefits for a product,
        like how much bonuses customer receive after purchasing,
        or credit available,
        or guaranty duration etc
        */
        'benefits',

        /* seo related things */
        'meta_title',
        'meta_description',
        'meta_keywords',
        'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storeProduct()
    {
        return $this->belongsTo(StoreProduct::class);
    }
}
