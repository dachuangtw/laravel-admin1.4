<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Input;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
    /**
     * 一般後台管理頁面，resource()自動生成router：
     * POST      | store
     * GET|HEAD  | index
     * GET|HEAD  | create
     * DELETE    | destroy
     * GET|HEAD  | show
     * PUT|PATCH | update
     * GET|HEAD  | edit
     */
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
    $router->resource('web/area', WebAreaController::class);
    $router->resource('web/location', WebLocationController::class);
    $router->resource('warehouse', WarehouseController::class);
    $router->resource('stock/category', StockCategoryController::class);
    $router->resource('transfer', TransferController::class);
    $router->resource('inventory', InventoryController::class);
    $router->resource('customer', CustomerController::class);
    $router->resource('records', RecordsController::class);
    /**
     * 眼睛查看
     */
    //倉庫
    $router->get('warehouse/{id}/view', 'WarehouseController@view');
    //廠商
    $router->get('supplier/{id}/view', 'ProductSupplierController@view');
    //商品
    $router->get('product/{id}/view', 'ProductIndexController@view');
    //調撥單
    $router->get('transfer/{id}/view', 'TransferController@view');
    //進貨單
    $router->get('product/receipt/{id}/view', 'ProductReceiptController@view');
    //盤點作業
    $router->get('inventory/{id}/view', 'InventoryController@view');
    //業務配貨
    $router->get('sales/assign/{id}/view', 'SalesAssignController@view');
    //業務領貨
    $router->get('sales/collect/{id}/view', 'SalesCollectController@view');
    //業務退貨 (未完成)
    $router->get('sales/refund/{id}/view', 'SalesRefundController@view');
    //店鋪據點
    $router->get('web/location/{id}/view', 'WebLocationController@view');
    //會員資訊
    $router->get('customer/{id}/view', 'CustomerController@view');
    //交易紀錄
    $router->get('records/{id}/view', 'RecordsController@view');   
    
    /**
     * 商品搜尋(彈出視窗)
     */
    //使用參數(方法1)
    // $router->get('modal', function () {
    //     $target = Input::get('t');
    //     return view('admin.modal',['target'=>$target]);
    // });

    //使用Controller(方法2)
    // $router->get('modal/{target}', 'ProductIndexController@modal');

    //使用路徑(方法3)
    $router->get('modal/{target}', function ($target) {
        return view('admin.modal',['target'=>$target]);
    });

    //商品搜尋的彈出/互動視窗modal
    $router->post('product/search', 'ProductIndexController@modalsearch');
    $router->post('product/searchstock', 'ProductIndexController@modalsearchstock');

    //進貨單
    $router->post('product/receiptdetails', 'ProductIndexController@selectedproduct');
    $router->get('product/receiptdetails/{id}', 'ProductReceiptController@receiptdetails');
    
    //調貨單
    $router->post('transfer/transferdetails', 'ProductIndexController@selectedproduct');
    $router->get('transfer/transferdetails/{id}', 'TransferController@transferdetails');

    //業務配貨
    $router->post('sales/assigndetails', 'ProductIndexController@selectedproduct');
    $router->get('sales/assigndetails/{id}', 'SalesAssignController@salesassigndetails');

    //業務領貨
    $router->post('sales/collectdetails', 'ProductIndexController@selectedproduct');
    $router->get('sales/collectdetails/{id}', 'SalesCollectController@salescollectdetails');

    //業務退貨
    $router->post('sales/refunddetails', 'ProductIndexController@selectedproduct');
    $router->get('sales/refunddetails/{id}', 'SalesRedundCollectController@salesrefunddetails');
    
    //交易紀錄---購買清單
    $router->post('sales/recorddetails', 'ProductIndexController@selectedproduct');
    $router->get('sales/recorddetails/{id}', 'RecordsController@recorddetails');

    /**
     * 其他
     */
    //盤點作業
    $router->get('inventorydetails/getdata/{id}', 'InventoryDetailsController@getdata');
    $router->put('inventorydetails/{id}', 'InventoryDetailsController@update');
    $router->get('inventory/{id}/details', 'InventoryDetailsController@index');
    $router->post('inventory/{id}/search', 'InventoryDetailsController@search');
    $router->post('inventory/checked', 'InventoryController@checked');

    //鄉鎮市區選擇
    $router->get('api/tw/district', 'WebLocationController@district');

    //商品匯入功能
    $router->post('product/import', 'ProductIndexController@import');

    //後台管理首頁儀板表
    $router->get('/', 'HomeController@index');

    

});