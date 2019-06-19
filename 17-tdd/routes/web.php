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

Route::get('/', function() {
    return redirect()->route('products.index');
});

Route::middleware('auth')->group(function () {
    Route::get('products', 'ProductController@index')->name('products.index');

    Route::middleware('is_admin')->group(function () {
        Route::get('products/create', 'ProductController@create')->name('products.create');
        Route::post('products', 'ProductController@store')->name('products.store');
        Route::get('products/{product}/edit', 'ProductController@edit')->name('products.edit');
        Route::put('products/{product}', 'ProductController@update')->name('products.update');
        Route::delete('products/{product}', 'ProductController@destroy')->name('products.destroy');
        Route::get('products/cart/{product_id}', 'ProductController@cart')->name('products.cart');
    });

});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
