<?php

namespace App\Observers;

use App\Category;
use Illuminate\Support\Facades\Log;

class CategoryObserver
{
    /**
     * Handle the category "created" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        try {
            api()->store('categories', [
                'name' => $category->name
            ]);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the category "updated" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function updated(Category $category)
    {
        try {
            api()->update('categories', $category->id, [
                'name' => $category->name
            ]);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the category "deleted" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        try {
            api()->destroy('categories', $category->id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Handle the category "restored" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the category "force deleted" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        try {
            api()->destroy('categories', $category->id);
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
