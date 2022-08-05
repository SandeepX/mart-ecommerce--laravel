<?php

use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'Reporting',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Reporting\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {

    Route::get('demand-projection','DemandProjectionController@warehouseDemandProjectionReport')->name('demand-projection.index');
    Route::get('rejected-item-reporting','RejectedItemReportingController@warehouseRejectedItemReporting')->name('wh-rejected-item-reporting.index');
    Route::get('rejected-item-report-statement','RejectedItemReportingController@warehouseRejectedItemStatement')->name('wh-rejected-item-report-statement.index');
    Route::get('rejected-item-reporting/{warehouseCode}/product/{productCode}/stores-lists','RejectedItemReportingController@warehouseRejectedItemReportWithStoreLists')->name('rejected-item-report.product.stores-lists');
    Route::get('rejected-item-reporting/{warehouseCode}/store/{storeCode}/product/{productCode}/statement','RejectedItemReportingController@warehouseRejectedItemDetailReport')->name('wh-rejected-item-report.detail-report');

    //generate Excel-bill
    Route::get('rejected-item-reporting/generate-excel-bill','RejectedItemReportExcelExportController@excelExportRejectedItemByWarehouse')->name('wh-rejected-item-excel-export');
    Route::get('rejected-item-reporting/generate-excel-bill/daybook','RejectedItemReportExcelExportController@excelExportRejectedItemDayBook')->name('wh-rejected-item-daybook-excel-export');
    Route::get('rejected-item-reporting/{warehouseCode}/generate-excel-bill/{productCode}/store-wise','RejectedItemReportExcelExportController@excelExportRejectedItemStoreWise')->name('wh-rejected-item-store-wise-excel-export');
    Route::get('rejected-item-reporting/{warehouseCode}/generate-excel-bill/{storeCode}/product-wise/{productCode}','RejectedItemReportExcelExportController@excelExportRejectedItemProductWise')->name('wh-rejected-item-product-wise-excel-export');


    Route::get('wh-dispatch-report','WarehouseDispatchReportController@warehouseDispatchReport')->name('wh-dispatch-report.index');
    Route::get('wh-dispatch-report-statement','WarehouseDispatchReportController@warehouseDispatchStatement')->name('wh-dispatch-statement.index');
    Route::get('wh-dispatch-report/{warehouseCode}/product/{productCode}/stores-lists','WarehouseDispatchReportController@warehouseDispatchReportOfProductWithStoreLists')->name('wh-dispatch-report.product.stores-lists');
    Route::get('wh-dispatch-report/{warehouseCode}/store/{storeCode}/product/{productCode}/statement','WarehouseDispatchReportController@getDispatchStatementByWarehouseStoreAndProduct')->name('wh-dispatch-report.product.store.statement');

    Route::get('wh-stock-report','WarehouseStockReportController@warehouseStockReportIndex')->name('wh-stock-report.index');
    Route::get('wh-stock-report/{warehouseCode}/product/{warehouseProductMasterCode}','WarehouseStockReportController@getWarehouseStockReportOfWarehouseProductMaster')->name('wh-stock-report.warehouse-product-master.detail');

    //sync logs
    Route::get('wh-dispatch-sync-logs','WarehouseDispatchSyncLogsController@index')->name('wh-dispatch-sync-logs.index');
    Route::get('wh-rejection-sync-logs','WarehouseRejectionSyncLogsController@index')->name('wh-rejection-sync-logs.index');

});


