<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
    $attributes = ['middleware' => 'admin.permission:allow,administrator'];
     $router->group($attributes, function ($router) {
        $router->resource('sales/log', SalesLogController::class);
        $router->resource('sales/assign', SalesAssignController::class);
        $router->resource('sales/collect', SalesCollectController::class);
        $router->resource('sales/refund', SalesRefundController::class);
        $router->resource('sales/notes', SalesNoteController::class);
        $router->resource('sales', SalesController::class);
        
        
        $router->resource('product/category', ProductCategoryController::class);
        $router->resource('product/series', ProductSeriesController::class);
        $router->resource('supplier', ProductSupplierController::class);
        $router->resource('product', ProductIndexController::class);
        $router->resource('web/location', WebLocationController::class);    
        $router->resource('web/area', WebAreaController::class);
        $router->resource('warehouse', WarehouseController::class);     

    });

    $router->get('/', 'HomeController@index');

});