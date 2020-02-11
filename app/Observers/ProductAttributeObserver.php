<?php

namespace App\Observers;

use App\ProductAttributes;
use Illuminate\Support\Facades\Log;

class ProductAttributeObserver
{
    /**
     * @param ProductAttributes $productAttribute
     */
    public function created(ProductAttributes $productAttribute)
    {
        try {
            api()->store('product-attributes', [
                'name' => $productAttribute->name,
                'category_id' => $productAttribute->category_id,
                'attribute_key' => $productAttribute->attribute_key
            ]);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
