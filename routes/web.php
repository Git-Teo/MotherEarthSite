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

//Authentication routes
Auth::routes();

//Product Related
Route::get('/add-to-basket/{sku}', 'ProductController@getAddToBasket')->name('addToBasket');
Route::get('/basket', 'ProductController@getBasket')->name('basket');
Route::get('/products/{sku}', 'ProductController@getProduct');

//Other Pages
Route::get('/', 'PageController@getIndex')->name('index');
Route::get('/contacts' , 'PageController@getContacts')->name('contacts');
