<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {

    //for warehouse admin
    Route::group([
        'module' => 'AlpasalWarehouse',
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse',
        'prefix'=>'warehouse',
        'as'=>'warehouse.',
        'middleware' => ['web','isMaintenanceModeOn','warehouse.auth','isWarehouseUser']
    ], function () {
        Route::get('warehouse-products/price-histories/{warehouseProductMasterCode}',
            'WarehouseProductPriceControllerApi@getWarehouseProductPriceHistories')
            ->name('warehouse-products.price-histories');
        Route::get('warehouse-products/price-info/{warehouseProductMasterCode}',
            'WarehouseProductPriceControllerApi@getWarehouseProductPriceInfo')
            ->name('warehouse-products.price-info');

    });

});

Route::group(['prefix' => 'api'], function () {

    //for warehouse admin
    Route::group([
        'module' => 'AlpasalWarehouse',
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse',
        'prefix'=>'warehouse',
        'as'=>'warehouse.',
        'middleware' => ['web','isMaintenanceModeOn','warehouse.auth','isWarehouseUser']
    ], function () {
        Route::get('warehouse-products/stock-histories/{warehouseProductMasterCode}',
            'WarehouseProductStockControllerApi@getWarehouseProductStockHistories')
            ->name('warehouse-products.stock-histories');
    });

    //for warehouse admin
    Route::group([
        'module' => 'AlpasalWarehouse',
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse\PreOrder',
        'prefix'=>'warehouse',
        'as'=>'api.warehouse.',
        'middleware' => ['web','isMaintenanceModeOn','warehouse.auth','isWarehouseUser']
    ], function () {
        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/products',
            'WarehousePreOrderProductControllerApi@getWarehousePreOrderProducts')
            ->name('warehouse-pre-orders.products');
    });

//    for  frontend
    Route::group([
        'module'=>'AlpasalWarehouse',
        'namespace'=>'App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse\ProductCollection',
        'prefix'=>'warehouse',
        'as'=>'warehouse',
        'middleware'=>['auth:api','isMaintenanceModeOn','isStoreUser']
    ],function(){
        Route::get('warehouse-product-collections','ProductCollectionApiController@index')->name('warehouse-product-collections.index');
        Route::get('warehouse-product-collection-details/{product_collection_code}','ProductCollectionApiController@warehouseProductCollectionDetail')->name('warehouse-product-collections.detail');
        Route::get('warehouse-product-collection-details/products/{product_collection_code}','ProductCollectionApiController@warehouseProductsInCollection')->name('warehouse-product-collections.products.detail');
    });

    Route::group([
        'module' => 'AlpasalWarehouse',
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse\Product',
        'prefix'=>'warehouse',
        'middleware' => ['web','isMaintenanceModeOn','warehouse.auth','isWarehouseUser']
    ], function () {
        Route::post('warehouse-products/{stockTransferCode}',
            'WPMFilterControllerApi@getWhProducts')
            ->name('warehouse.wh-product-lists');
    });

});


