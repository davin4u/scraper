<?php

namespace App\Observers;

use App\Brand;
use Illuminate\Support\Facades\Log;

class BrandObserver
{
    /**
     * @param Brand $brand
     */
    public function created(Brand $brand)
    {
        try {
            api()->store('brands', [
                'name' => $brand->name
            ]);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param Brand $brand
     */
    public function updated(Brand $brand)
    {
        try {
            api()->update('brands', $brand->id, [
                'name' => $brand->name
            ]);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param Brand $brand
     */
    public function deleted(Brand $brand)
    {
        try {
            api()->destroy('brands', $brand->id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the brand "restored" event.
     *
     * @param  \App\Brand  $brand
     * @return void
     */
    public function restored(Brand $brand)
    {
        //
    }

    /**
     * Handle the brand "force deleted" event.
     *
     * @param  \App\Brand  $brand
     * @return void
     */
    public function forceDeleted(Brand $brand)
    {
        try {
            api()->destroy('brands', $brand->id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
