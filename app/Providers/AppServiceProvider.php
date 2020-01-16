<?php

namespace App\Providers;

use App\Parsers\Helpers\SimpleCategoryMatcher;
use App\Parsers\Helpers\CategoryMatcher;
use App\ProductsStorage\Interfaces\MongoDBClientInterface;
use App\ProductsStorage\Interfaces\ProductsStorageInterface;
use App\ProductsStorage\MongoDB\Mongo;
use App\ProductsStorage\MongoDBProductsStorage;
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
        $this->app->bind(MongoDBClientInterface::class, function ($app) {
            return Mongo::client();
        });

        $this->app->bind(ProductsStorageInterface::class, function ($app) {
            return new MongoDBProductsStorage($app->make(MongoDBClientInterface::class));
        });

        $this->app->singleton(CategoryMatcher::class, function ($app) {
            return $app->make(SimpleCategoryMatcher::class);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
