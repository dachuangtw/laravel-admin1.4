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
    return view('sales.welcome');
});

Auth::routes();
Route::group([
	'middleware' => ['auth', 'web'],
	'namespace' => 'Sales'
], function()
{
	Route::get('bulletin', 'BulletinController@index')->name('home');

	Route::get('product', 'ProductController@index');
	Route::get('product/{id}', 'ProductController@categories');
	Route::get('product-detail', 'ProductDetailController@index');
	Route::get('cart', 'CartController@index');

	Route::get('record', function () {
		return view('sales.picking-record');
	});
});
