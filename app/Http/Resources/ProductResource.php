<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductResource
 * @package App\Http\Resources
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description
        ];

        if ($this->brand) {
            $data = array_merge($data, [
                'brand' => [
                    'id' => $this->brand->id,
                    'name' => $this->brand->name
                ]
            ]);
        }

        if ($this->category) {
            $data = array_merge($data, [
                'category' => [
                    'id' => $this->category->id,
                    'name' => $this->category->name
                ]
            ]);
        }

        return $data;
    }
}
