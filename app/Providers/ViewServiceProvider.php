<?php

namespace App\Providers;

use App\ProductMatch;
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
            $autoMatches = Cache::remember('matchesCount', 600, function () {
                return ProductMatch::notResolved()->count();
            });

            $view->with('matchesCount', $autoMatches);

            if (Auth::user()) {
                $userMatches = Cache::remember('userMatchesCount', 600, function () {
                    $response = api()->index('products', [], 'matches/user-matches');

                    $meta = $response->meta();

                    return isset($meta['total']) ? (int) $meta['total'] : 0;
                });

                $view->with('userMatchesCount', $userMatches);
            }
        });
    }
}
