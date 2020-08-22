<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class StoreProducts
 * @package App\Http\Resources
 */
class StoreProducts extends ResourceCollection
{
    /**
     * @var string
     */
    public $collects = StoreProductResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
