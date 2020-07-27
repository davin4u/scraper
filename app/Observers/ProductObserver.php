<?php

namespace App\Observers;

use App\Product;

class ProductObserver
{
    /**
     * Handle the product "deleted" event.
     *
     * @param \App\Product $product
     * @return void
     * @throws \Exception
     */
    public function deleted(Product $product)
    {
        if ($product->media()->count() > 0) {
            foreach ($product->media()->get() as $media) {
                $product->deleteFile($media->id);
            }
        }
    }
}
