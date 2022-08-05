<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'InventoryManagement',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\InventoryManagement\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {

    // store inventory Purchase stock route
    Route::get('inventory/purchased-stock','InventoryPurchaseStockController@index')->name('inventory.purchased-stock.index');
    Route::get('inventory/purchased-stock/{siid_code}/recieved-quantity-detail/{pph_code}','InventoryPurchaseStockController@showInventoryStockRecievedQtyDetail')->name('inventory.purchased-stock.show-recieved-qty-detail');

    // store inventory sales route
    Route::get('inventory/sales-record/index','StoreInventoryStockSalesRecordController@index')->name('inventory.sales.index');
    Route::get('inventory/sales-record/{SIIDCode}/show-detail/{PPHCode}','StoreInventoryStockSalesRecordController@showStoreInventorySalesRecord')->name('inventory.sales-record.show-detail');

    //store inventory record export
    Route::get('inventory/sales-record-export','InventoryPurchaseSalesRecordExportController@salesRecordExcelExport')->name('inventory.sales-record.export');

    //store inventory Current Stock
    Route::get('inventory/store-current-stock','StoreInventoryCurrentStockController@index')->name('inventory.current-stock.index');

});





