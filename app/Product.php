<?php

namespace App;

use App\Events\ProductPriceUpdatingEvent;
use App\Traits\Storable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use Storable;

    /**
     * @var string
     */
    protected $storableKey = 'storable_id';

    /**
     * @var array
     */
    protected $fillable = [
        'domain_id',
        'category_id',
        'brand_id',
        'name',
        'sku',
        'manufacturer_id',
        'url',
        'store_rating',
        'votes_count',
        'in_stock',
        'meta_title',
        'meta_description',
        'storable_id',
        'scraped_at'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
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

            $this->pricesHistory()->create([
                'price'      => Arr::get($data, 'price'),
                'currency'   => Arr::get($data, 'currency'),
                'old_price'  => Arr::get($data, 'old_price', null),
                'city_id'    => Arr::get($data, 'city_id', null),
                'store_id'   => Arr::get($data, 'store_id', null),
                'price_date' => Carbon::now()->toDateTimeString()
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

        event(new ProductPriceUpdatingEvent($this, $price, Arr::only($data, ['price', 'old_price'])));

        $price->update(Arr::only($data, ['price', 'old_price']));
    }

    /**
     * @return array
     */
    public function toStorableDocument()
    {
        return [
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id
        ];
    }

    /**
     * @return mixed
     */
    public function matches()
    {
        return $this->hasMany(ProductMatch::class)->notResolved();
    }
}
