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

Route::get('/', 'ProductController@index')->name('product.index');

Route::prefix('ajax')->group(function () {

    Route::get('products', 'Ajax\ProductController@index')->name('ajax.product.index');
    Route::post('products', 'Ajax\ProductController@store')->name('ajax.product.store');
    Route::put('products/{id}/update', 'Ajax\ProductController@update')->name('ajax.product.update');
    Route::delete('products/{id}/delete', 'Ajax\ProductController@delete')->name('ajax.product.delete');

});
