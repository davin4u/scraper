<?php

namespace App\Providers;

use App\ApiCRUDProvider;
use App\Brand;
use App\Category;
use App\Domain;
use App\Observers\BrandObserver;
use App\Observers\CategoryObserver;
use App\Observers\DomainObserver;
use App\Parsers\Helpers\BrandMatcher;
use App\Parsers\Helpers\SimpleBrandMatcher;
use App\Parsers\Helpers\SimpleCategoryMatcher;
use App\Parsers\Helpers\CategoryMatcher;
use App\ProductsStorage\Interfaces\MongoDBClientInterface;
use App\ProductsStorage\Interfaces\ProductsStorageInterface;
use App\ProductsStorage\MongoDB\Mongo;
use App\ProductsStorage\MongoDBProductsStorage;
use App\Repositories\ProductAttributesRepository;
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

        $this->app->singleton(BrandMatcher::class, function ($app) {
            return $app->make(SimpleBrandMatcher::class);
        });

        $this->app->singleton(ProductAttributesRepository::class, function ($app) {
            return new ProductAttributesRepository();
        });

        $this->app->singleton(ApiCRUDProvider::class, function ($app) {
            return new ApiCRUDProvider();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Brand::observe(BrandObserver::class);
        Category::observe(CategoryObserver::class);
        Domain::observe(DomainObserver::class);
    }
}
