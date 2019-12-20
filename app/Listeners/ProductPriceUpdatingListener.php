<?php

namespace App\Listeners;

use App\Events\ProductPriceUpdatingEvent;
use App\Product;
use App\ProductPrice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class ProductPriceUpdatingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ProductPriceUpdatingEvent $event
     * @return void
     */
    public function handle(ProductPriceUpdatingEvent $event)
    {
        /** @var Product $product */
        $product = $event->product;

        /** @var ProductPrice $price */
        $price   = $event->productPrice;

        $data    = $event->data;

        $newPrice = Arr::get($data, 'price', null);
        $newOldPrice = Arr::get($data, 'old_price', null);

        if ((!is_null($newPrice) && $newPrice !== $price->price) || $price->old_price !== $newOldPrice) {
            $product->pricesHistory()->create([
                'city_id' => $price->city_id,
                'store_id' => $price->store_id,
                'price' => $newPrice,
                'old_price' => $newOldPrice,
                'currency' => $price->currency,
                'price_date' => Carbon::now()->toDateTimeString()
            ]);
        }
    }
}
