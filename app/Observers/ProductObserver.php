<?php

namespace App\Observers;

use App\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the product "created" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        try {
            api()->store('products', [
                'domain_id' => $product->domain_id,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'name' => $product->name,
                'sku' => $product->sku,
                'manufacturer_id' => $product->manufacturer_id,
                'url' => $product->url,
                'store_rating' => $product->store_rating,
                'votes_count' => $product->votes_count,
                'in_stock' => $product->in_stock,
                'meta_title' => $product->meta_title,
                'meta_description' => $product->meta_description,
                'storable_id' => $product->storable_id,
            ]);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        try {
            api()->update('products', $product->id, [
                'domain_id' => $product->domain_id,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'name' => $product->name,
                'sku' => $product->sku,
                'manufacturer_id' => $product->manufacturer_id,
                'url' => $product->url,
                'store_rating' => $product->store_rating,
                'votes_count' => $product->votes_count,
                'in_stock' => $product->in_stock,
                'meta_title' => $product->meta_title,
                'meta_description' => $product->meta_description,
                'storable_id' => $product->storable_id,
            ]);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        try {
            api()->destroy('products', $product->id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        try {
            api()->destroy('products', $product->id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
