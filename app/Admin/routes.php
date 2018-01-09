<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Input;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
    // $attributes = ['middleware' => 'admin.permission:allow,administrator'];
    //  $router->group($attributes, function ($router) {

        $router->resource('sales/endwork', SalesEndWorkController::class);
        $router->resource('sales/log', SalesLogController::class);
        $router->resource('sales/assign', SalesAssignController::class);
        $router->resource('sales/collect', SalesCollectController::class);
        $router->resource('sales/refund', SalesRefundController::class);
        $router->resource('sales/notes', SalesNoteController::class);
        $router->resource('sales', SalesController::class);

        $router->resource('product/category', ProductCategoryController::class);
        $router->resource('product/series', ProductSeriesController::class);
        $router->resource('product/receipt', ProductReceiptController::class);
        $router->resource('product', ProductIndexController::class);
        $router->resource('supplier', ProductSupplierController::class);
        $router->resource('web/location', WebLocationController::class);
        $router->resource('web/area', WebAreaController::class);
        $router->resource('warehouse', WarehouseController::class);
        $router->resource('stock/category', StockCategoryController::class);

    // });

    $router->get('sales/endwork/{id}/view', 'SalesEndWorkController@view');
    $router->get('sales/log/{id}/view', 'SalesLogController@view');
    $router->get('sales/assign/{id}/view', 'SalesAssignController@view');
    $router->get('sales/collect/{id}/view', 'SalesCollectController@view');
    $router->get('sales/refund/{id}/view', 'SalesRefundController@view');
    $router->get('sales/notes/{id}/view', 'SalesNoteController@view');
    $router->get('sales/{id}/view', 'SalesController@view');

    $router->get('product/category/{id}/view', 'ProductCategoryController@view');
    $router->get('product/series/{id}/view', 'ProductSeriesController@view');
    $router->get('product/receipt/{id}/view', 'ProductReceiptController@view');
    $router->post('product/import', 'ProductIndexController@import');
    $router->get('product/{id}/view', 'ProductIndexController@view');
    $router->get('supplier/{id}/view', 'ProductSupplierController@view');
    $router->get('web/location/{id}/view', 'WebLocationController@view');
    $router->get('web/area/{id}/view', 'WebAreaController@view');
    $router->get('warehouse/{id}/view', 'WarehouseController@view');

    $router->get('api/tw/district', 'WebLocationController@district');

    //使用參數
    // $router->get('modal', function () {
    //     $target = Input::get('t');
    //     return view('admin.modal',['target'=>$target]);
    // });

    //使用路徑
    $router->get('modal/{target}', function ($target) {
        return view('admin.modal',['target'=>$target]);
    });

    //使用Controller
    // $router->get('modal/{target}', 'ProductIndexController@modal');
    $router->post('product/search', 'ProductIndexController@modalsearch');
    $router->post('product/receiptdetails', 'ProductIndexController@receiptdetails');
    $router->get('product/receiptdetails/{id}', 'ProductReceiptController@receiptdetails');

    $router->get('/', 'HomeController@index');
});