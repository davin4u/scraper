<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

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
        'url',
        'store_rating'
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

    /**
     * @param array $data
     */
    public function syncPrice(array $data)
    {
        // if there are no prices for the product we create a new price
        if ($this->prices->count() === 0) {
            $this->prices()->create([
                'price'     => Arr::get($data, 'price'),
                'currency'  => Arr::get($data, 'currency'),
                'old_price' => Arr::get($data, 'old_price', null),
                'city_id'   => Arr::get($data, 'city_id', null),
                'store_id'  => Arr::get($data, 'store_id', null)
            ]);

            return;
        }

        $cityId       = Arr::get($data, 'city_id', null);
        $storeId      = Arr::get($data, 'store_id', null);

        /** @var Collection $prices */
        $prices = $this->prices->filter(function ($price) use ($cityId, $storeId) {
            return $price->city_id === $cityId && $price->store_id === $storeId;
        });

        // if there are more than one price with the same city_id and store_id, we create a new price
        // and in admin dashboard we should show such products so admin can resolve this situation
        if ($prices->count() > 1) {
            $this->prices()->create([
                'price'     => Arr::get($data, 'price'),
                'currency'  => Arr::get($data, 'currency'),
                'old_price' => Arr::get($data, 'old_price', null),
                'city_id'   => Arr::get($data, 'city_id', null),
                'store_id'  => Arr::get($data, 'store_id', null)
            ]);

            return;
        }

        /** @var ProductPrice $price */
        $price = $prices->first();

        $price->update(Arr::only($data, ['price', 'old_price']));
    }
}
