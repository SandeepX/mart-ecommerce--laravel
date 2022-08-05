<?php

use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'InventoryManagement',
    'prefix'=>'api',
    'namespace' => 'App\Modules\InventoryManagement\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn','auth:api']
], function() {

    //store inventory current stock
    Route::get('inventory/store-purchased-products','InventoryPurchaseStockController@getStorePurchasedProducts');
    Route::get('inventory/store-purchased-products/variant/{product_code}','InventoryPurchaseStockController@getProductVariantByProductCode');
    Route::post('inventory/store-current-stock/packaging-history','InventoryPurchaseStockController@getProductPackagingContains');
    Route::get('inventory/store-current-stock/package-types/{pph_code}','InventoryPurchaseStockController@getProductPackagingTypeByPPHCode');
    Route::post('inventory/current-stock/store','InventoryPurchaseStockController@saveStoreCurrentStockProductDetail');
    Route::get('inventory/store-current-stock','InventoryPurchaseStockController@getStoreInventoryProductCurrentStockDetail');

    //store inventory Current Stock Quantity Detail
    Route::get('inventory/store-current-stock/{siid_code}/qty-detail/{pph_code}','InventoryCurrentStockQtyDetailController@getCurrentStockQtyRecievedDetail');
    Route::put('inventory/store-current-stock/update-quantity-detail/{siid_code}','InventoryCurrentStockQtyDetailController@updateCurrentStockQtyDetail');

    //store inventory sales
    Route::get('inventory/store-products','StoreInventoryStockSalesRecordController@getStoreInventoryProduct');
    Route::get('inventory/store-product-variants/{productCode}','StoreInventoryStockSalesRecordController@getStoreProductVariantFromInventoryByProductCode');
    Route::get('inventory/product-batch-details/{productCode}/{productVariantCode}','StoreInventoryStockSalesRecordController@getBatchDetailOfStoreInventoryProduct');
    Route::get('inventory/product-packaging-detail/{siid_code}','StoreInventoryStockSalesRecordController@getPackingDetailOfStoreInventoryProductBySIIDCode');
    Route::get('inventory/product-package-type/{siid_code}/{pph_code}','StoreInventoryStockSalesRecordController@getInventoryProductPackingTypeWithQuantityByPPHCodeAndSIIDCode');
    Route::post('inventory/store-inventory-sales/store','StoreInventoryStockSalesRecordController@saveStoreInventoryStockSaleRecord');
    Route::get('inventory/store-sales-detail','StoreInventoryStockSalesRecordController@getStoreInventoryProductSalesRecordDetail');
    Route::get('inventory/stock-sales-detail/{SIIDCode}/{PPHCode}','StoreInventoryStockSalesRecordController@showStoreInventorySalesRecord');
    Route::put('inventory/stock-sales-detail/{SIIDQDCode}/update','StoreInventoryStockSalesRecordController@updateStoreInventoryStockSaleRecord');
    Route::delete('inventory/stock-sales-detail/{SIIDQDCode}/delete','StoreInventoryStockSalesRecordController@deleteStoreInventoryStockSaleRecord');

    //store inventory current stock record
    Route::get('inventory/store-current-stock-detail','StoreInventoryCurrentStockRecord@getStoreInventoryCurrentStock');


});

