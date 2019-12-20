<?php

namespace App\Events;

use App\Product;
use App\ProductPrice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductPriceUpdatingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var ProductPrice
     */
    public $productPrice;

    /**
     * @var array
     */
    public $data;

    /**
     * Create a new event instance.
     *
     * @param Product $product
     * @param ProductPrice $productPrice
     * @param array $data
     */
    public function __construct(Product $product, ProductPrice $productPrice, $data = [])
    {
        $this->product = $product;
        $this->productPrice = $productPrice;
        $this->data = $data;
    }
}
