<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::prefix('scraper')->name('scraper.')->group(function () {
        Route::resource('categories', 'ScraperCategoryController')->only([
            'index', 'create', 'store'
        ]);
    });

    // Categories
    Route::resource('categories', 'CategoriesController')->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);

    // Brands
    Route::resource('brands', 'BrandsController')->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);

    // Products
    Route::resource('products', 'ProductsController')->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);

    //Scraping jobs
    Route::resource('scraper-jobs', 'ScraperJobsController')->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);

    //Products Media
    Route::get('products/{product}/media', 'MediaController@index')->name('products.media.index');
    Route::get('products/{product}/media/upload', 'MediaController@upload')->name('products.media.upload');
    Route::post('products/{product}/media', 'MediaController@store')->name('products.media.store');
    Route::delete('products/{product}/media/{id}', 'MediaController@destroy')->name('products.media.delete');

    //Products Reviews
    Route::get('reviews', 'ProductReviewsController@index')->name('products.reviews.index');
    Route::get('products/{product}/reviews', 'ProductReviewsController@show')->name('products.reviews.show');
    Route::get('reviews/{productReview}/edit', 'ProductReviewsController@edit')->name('products.reviews.edit');
    Route::put('reviews/{productReview}', 'ProductReviewsController@update')->name('products.reviews.update');
    Route::delete('reviews/{productReview}', 'ProductReviewsController@destroy')->name('products.reviews.destroy');

    //Product Overviews
    Route::get('overviews', 'ProductOverviewsController@index')->name('products.overviews.index');
    Route::get('overviews/create', 'ProductOverviewsController@create')->name('products.overviews.create');
    Route::post('overviews', 'ProductOverviewsController@store')->name('products.overviews.store');
    Route::get('products/{product}/overviews', 'ProductOverviewsController@show')->name('products.overviews.show');
    Route::get('overviews/{productOverview}/edit', 'ProductOverviewsController@edit')->name('products.overviews.edit');
    Route::put('overviews/{productOverview}', 'ProductOverviewsController@update')->name('products.overviews.update');
    Route::delete('overviews/{productOverview}', 'ProductOverviewsController@destroy')->name('products.overviews.destroy');

    //Authors
    Route::get('authors', 'ReviewAuthorsController@index')->name('authors.index');
    Route::get('authors/{reviewAuthor}/edit', 'ReviewAuthorsController@edit')->name('authors.edit');
    Route::put('authors/{reviewAuthor}', 'ReviewAuthorsController@update')->name('authors.update');
    Route::delete('authors/{reviewAuthor}', 'ReviewAuthorsController@destroy')->name('authors.destroy');

    //Users
    Route::get('users', 'UsersController@index')->name('users.index');
    Route::get('users/create', 'UsersController@create')->name('users.create');
    Route::post('users', 'UsersController@store')->name('users.store');
    Route::get('users/{user}/edit', 'UsersController@edit')->name('users.edit');
    Route::put('users/{user}', 'UsersController@update')->name('users.update');
    Route::delete('users/{user}', 'UsersController@destroy')->name('users.destroy');

    //Domains
    Route::get('domains', 'DomainsController@domainsIndex')->name('domains.index');
    Route::get('domains/create', 'DomainsController@domainsCreate')->name('domains.create');
    Route::post('domains', 'DomainsController@domainsStore')->name('domains.store');
    Route::get('domains/{domain}/edit', 'DomainsController@domainsEdit')->name('domains.edit');
    Route::put('domains/{domain}', 'DomainsController@domainsUpdate')->name('domains.update');
    Route::delete('domains/{domain}', 'DomainsController@domainsDestroy')->name('domains.destroy');
    //Stores
    Route::get('domains/{domain}/stores/create', 'DomainsController@storesCreate')->name('stores.create');
    Route::post('domains/{domain}/stores', 'DomainsController@storesStore')->name('stores.store');
    Route::get('domains/{domain}/stores/{store}/edit', 'DomainsController@storesEdit')->name('stores.edit');
    Route::put('domains/stores/{store}', 'DomainsController@storesUpdate')->name('stores.update');
    Route::delete('domains/stores/{store}', 'DomainsController@storesDestroy')->name('stores.destroy');
    //StoreLocations
    Route::get('domains/{domain}/stores/{store}/store-locations/create', 'DomainsController@storeLocationsCreate')->name('store-locations.create');
    Route::post('domains/stores/{store}/store-locations', 'DomainsController@storeLocationsStore')->name('store-locations.store');
    Route::get('domains/{domain}/stores/{store}/store-locations/{storeLocation}/edit', 'DomainsController@storeLocationsEdit')->name('store-locations.edit');
    Route::put('domains/store-locations/{storeLocation}', 'DomainsController@storeLocationsUpdate')->name('store-locations.update');
    Route::delete('domains/store-locations/{storeLocation}', 'DomainsController@storeLocationsDestroy')->name('store-locations.destroy');

    //Search statistics
    Route::resource('search-statistics', 'SearchStatisticsController')->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);

    //Matching tool
    Route::get('matching-tool', 'MatchingController@index')->name('matching.index');
    Route::get('matching-tool/search', 'MatchingController@search')->name('matching.search');
    Route::post('matching-tool/match', 'MatchingController@match')->name('matching.match');

    //Yml import
    Route::get('yml-import', 'YmlDataImportController@index')->name('yml-import.index');
    Route::post('yml-import/upload', 'YmlDataImportController@upload')->name('yml-import.upload');
    Route::post('yml-import/import', 'YmlDataImportController@import')->name('yml-import.import');

    // Routes with admin permissions
    Route::middleware(['isAdmin'])->group(function () {
        Route::resource('categories', 'CategoriesController')->only(['destroy']);
        Route::resource('brands', 'BrandsController')->only(['destroy']);
        Route::resource('search-statistics', 'SearchStatisticsController')->only(['destroy']);
    });
});
