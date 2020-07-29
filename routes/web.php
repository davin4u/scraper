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

    //Media
    Route::resource('media', 'MediaController')->only([
        'edit', 'update', 'destroy'
    ]);
    Route::get('media/upload/{product}', 'MediaController@upload')->name('media.upload');

    //Users
    Route::get('/users', 'UsersController@index')->name('users.index');
    Route::get('/users/create', 'UsersController@create')->name('users.create');
    Route::post('/users', 'UsersController@store')->name('users.store');
    Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
    Route::put('/users/{user}', 'UsersController@update')->name('users.update');
    Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');

    // Routes with admin permissions
    Route::middleware(['isAdmin'])->group(function () {
        Route::resource('categories', 'CategoriesController')->only(['destroy']);
        Route::resource('brands', 'BrandsController')->only(['destroy']);
    });
});
