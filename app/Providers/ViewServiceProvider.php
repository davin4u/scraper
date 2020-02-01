<?php

namespace App\Providers;

use App\ProductMatch;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.app', 'home'], function ($view) {
            $matchesCount = Cache::remember('matchesCount', 600, function () {
                return ProductMatch::notResolved()->count();
            });

            $view->with('matchesCount', $matchesCount);
        });
    }
}
