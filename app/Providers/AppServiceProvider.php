<?php

namespace App\Providers;

use App\Observers\AttributeObserver;
use App\Attribute;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Attribute::observe(AttributeObserver::class);
    }
}
