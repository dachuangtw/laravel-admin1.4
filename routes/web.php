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
Route::group(['namespace' => 'Sales'], function()
{
	Route::get('/bulletin', 'BulletinController@index');
	
	Route::get('/product', function () {
		return view('sales.product');
	});

	Route::get('/product-detail', function () {
		return view('sales.product-detail');
	});

	Route::get('/picking', function () {
		return view('sales.picking');
	});

	Route::get('/cart', function () {
		return view('sales.cart');
	});

	Route::get('/record', function () {
		return view('sales.picking-record');
	});
});
