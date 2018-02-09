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
	Route::get('detail', 'ProductController@detail');

	// 領貨內容顯示
	Route::get('picking', 'PickingController@index');
	// 領貨車(新增)
	Route::post('pickingadd', 'PickingController@add');
	// 領貨車(修改)
	Route::post('pickingupdate', 'PickingController@update');
	// 領貨車(刪除)
	Route::post('pickingremove', 'PickingController@remove');
	// 領貨車(提交)
	Route::post('pickingsubmit', 'PickingController@submit');

    // 記錄查詢
	Route::get('record', function () {
		return view('sales.picking-record');
	});
});
