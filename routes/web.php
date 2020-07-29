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

    //Users
    Route::resource('users', 'UsersController')->only([
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]);

    // Routes with admin permissions
    Route::middleware(['isAdmin'])->group(function () {
        Route::resource('categories', 'CategoriesController')->only(['destroy']);
        Route::resource('brands', 'BrandsController')->only(['destroy']);
    });
});
