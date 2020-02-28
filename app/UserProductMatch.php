<?php

namespace App;

use Illuminate\Support\Facades\Log;

class UserProductMatch
{
    /**
     * @param int $id
     * @return mixed|null
     */
    public static function find(int $id)
    {
        try {
            $response = api()->index('products', [], 'matches/user-matches/' . $id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());

            return null;
        }

        return !is_null($response) ? $response->data() : null;
    }
}