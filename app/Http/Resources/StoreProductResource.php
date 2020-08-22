<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class StoreProductResource
 * @package App\Http\Resources
 */
class StoreProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $details = $this->details->first();
        $store = $this->store;

        $data = [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'store_id' => $this->store_id
        ];

        if (!is_null($details)) {
            $data = array_merge($data, [
                'name' => $details->name,
                'url' => $details->url,
                'price' => $details->price,
                'old_price' => $details->old_price,
                'currency' => $details->currency,
                'sku' => $details->sku,
                'description' => $details->description
            ]);
        }

        if (!is_null($store)) {
            $data = array_merge($data, [
                'domain' => [
                    'id' => $store->domain_id,
                    'name' => $store->domain->name
                ]
            ]);
        }

        return $data;
    }
}
