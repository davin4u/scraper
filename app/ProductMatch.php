<?php

namespace App;

use Illuminate\Support\Facades\Log;

/**
 * Class ProductMatch
 * @package App
 */
class ProductMatch
{
    /**
     * @param int $id
     * @return mixed|null
     */
    public static function find(int $id)
    {
        try {
            $response = api()->index('products', [], 'matches/' . $id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());

            return null;
        }

        return !is_null($response) ? $response->data() : null;
    }
}