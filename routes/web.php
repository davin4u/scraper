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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::prefix('scraper')->name('scraper.')->group(function () {
        Route::resource('categories', 'ScraperCategoryController')->only([
            'index', 'create', 'store'
        ]);
    });

    // Domains
    Route::resource('domains', 'DomainsController')->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);

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
        'index', 'edit', 'update'
    ]);

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('resolve/user-match/{matchId}', 'ProductsController@resolveUserMatch')->name('resolveUserMatch');
        Route::get('resolve/{source}/{match}', 'ProductsController@resolve')->name('resolve');
        Route::post('merge/{match}', 'ProductsController@merge')->name('merge');
    });

    // Matches
    Route::resource('matches', 'ProductMatchesController')->only([
        'index'
    ]);

    Route::prefix('matches')->group(function () {
        Route::get('user-matches', 'ProductMatchesController@userMatches')->name('matches.userMatches');
    });

    // Routes with admin permissions
    Route::middleware(['isAdmin'])->group(function () {
        Route::resource('domains', 'DomainsController')->only(['destroy']);
        Route::resource('categories', 'CategoriesController')->only(['destroy']);
        Route::resource('brands', 'BrandsController')->only(['destroy']);
    });
});
