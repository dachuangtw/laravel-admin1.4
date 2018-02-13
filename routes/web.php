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
    // 公告
	Route::get('bulletin', 'BulletinController@index')->name('home');

    // 商品顯示
	Route::get('product', 'ProductController@index');
    // 商品顯示(分類)
	Route::get('product/{id}', 'ProductController@categories');
    // 商品顯示(詳細)
	Route::get('product/detail/{number}', 'ProductController@detail');

	// 領貨記錄查詢
	Route::get('record', function () {
		return view('sales.record');
	});

	// 非領貨時間
	Route::get('picking/nottime', function () {
		return view('sales.picking-nottime');
	});
});

Route::group([
	'middleware' => ['auth', 'web', 'picking'],
	'namespace' => 'Sales'
], function()
{
	// 領貨作業
	Route::get('picking', 'PickingController@index');
	// 領貨作業(分類)
	Route::get('picking/{id}', 'PickingController@categories');
    // 領貨作業(詳細)
	Route::get('picking/detail/{number}', 'PickingController@detail');

	// 領貨車
	Route::get('cart', 'PickingController@index');
	// 領貨車(新增)
	Route::post('cart/add', 'PickingController@add');
	// 領貨車(修改)
	Route::post('cart/update', 'PickingController@update');
	// 領貨車(刪除)
	Route::post('cart/remove', 'PickingController@remove');
	// 領貨車(提交)
	Route::post('cart/submit', 'PickingController@submit');
});
