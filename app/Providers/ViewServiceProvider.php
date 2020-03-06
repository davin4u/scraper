<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
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
            if (Auth::user()) {
                // auto matches counter
                $response = api()->index('products', [], 'matches/auto-matches');

                $meta = $response->meta();

                $autoMatches = isset($meta['total']) ? (int) $meta['total'] : 0;

                $view->with('matchesCount', $autoMatches);

                // user matches counter
                $response = api()->index('products', [], 'matches/user-matches');

                $meta = $response->meta();

                $userMatches = isset($meta['total']) ? (int) $meta['total'] : 0;

                $view->with('userMatchesCount', $userMatches);
            }
        });
    }
}
