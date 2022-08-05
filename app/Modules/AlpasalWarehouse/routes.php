<?php

Route::group([
    'module'=>'AlpasalWarehouse',
    'prefix'=>'admin',
    'as'=>'admin.',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {
    Route::group([
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Admin',
    ], function() {
        Route::resource('warehouses', 'WarehouseController');
    });

    Route::group([
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Admin\PurchaseOrder',
    ], function() {
      //  dd(1);
        Route::get('warehouse-purchase-orders', 'WarehousePurchaseOrderController@index')->name('warehouse-purchase-orders.index');
    });

    Route::group([
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Admin\StockTransfer',
    ], function() {
        //  dd(1);
        Route::get('warehouses/stock-transfer/form', 'WarehouseStockTransferAdminController@stockTransferForm')->name('warehouses.stock-transfer.form');
        Route::post('warehouses/stock-transfer/save', 'WarehouseStockTransferAdminController@saveTransfer')->name('warehouses.stock-transfer.save');
    });



});

Route::group([
    'module'=>'AlpasalWarehouse',
    'prefix'=>'warehouse',
    'as'=>'warehouse.',
    'middleware' => ['web','warehouse.auth','isWarehouseUser']
], function() {

    Route::group([
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PurchaseOrder',
    ], function() {
        Route::post('warehouse-purchase-orders/return-order/{orderDetailCode}',
            'WarehousePurchaseOrderController@returnPurchaseOrder')->name('warehouse-purchase-orders.return-order');
        Route::get('warehouse-purchase-orders/generate-bill/{orderCode}',
            'WarehousePurchaseOrderController@generateWarehousePurchaseOrderBill')->name('warehouse-purchase-orders.generate-bill');
        Route::post('warehouse-purchase-orders/update-received-quantity/{orderCode}',
            'WarehousePurchaseOrderController@updatePurchaseOrderReceivedQuantity')->name('warehouse-purchase-orders.update-received-quantity');
        Route::resource('warehouse-purchase-orders', 'WarehousePurchaseOrderController');
        Route::get('warehouse-purchase-orders-list',
            'WarehousePurchaseOrderController@warehousePurchaseOrderList')->name('warehouse-purchase-orders.list');
    });

    Route::group([
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder',
    ], function() {

        Route::get('warehouse-pre-orders/stores','StoreHavingPreOrderController@getStoresHavingPreOrders')->name('warehouse-pre-orders.stores');
        Route::get('warehouse-pre-orders/stores/{storeCode}','StoreHavingPreOrderController@getStorePreOrdersListing')->name('warehouse-pre-orders.stores.detail');


        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/vendors-list','WarehousePurchasePreOrderController@listVendorsForPreOrders')->name('warehouse-pre-orders.vendors-list');
        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/vendors-list/{vendorCode}','WarehousePurchasePreOrderController@getPlaceOrderPage')->name('warehouse-pre-orders.place-order.get');
        Route::post('warehouse-pre-orders/{warehousePreOrderCode}/vendors-list/{vendorCode}','WarehousePurchasePreOrderController@storePreOrderPurchaseOrder')->name('warehouse-pre-orders.place-order.store');
        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/vendors-list/{vendorCode}/export','WarehousePurchasePreOrderController@exportPreOrderPurchaseOrder')->name('warehouse-pre-orders.place-order.export');

        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/store-orders','StorePreOrderController@getStorePreOrders')->name('warehouse-pre-orders.store-orders');
        Route::get('warehouse-pre-orders/store-orders/{storePreOrder}/show', 'StorePreOrderController@showPreOrderDetail')->name('warehouse-pre-orders.store-orders.show');
        Route::post('warehouse-pre-orders/store-orders/{storePreOrder}/update-status', 'StorePreOrderController@updatePreOrderStatus')->name('warehouse-pre-orders.store-orders.update-status');
        Route::post('warehouse-pre-orders/store-orders/{storePreOrder}/update-detail/{preOrderDetail}', 'StorePreOrderController@updatePreOrderDetail')->name('warehouse-pre-orders.store-orders.detail.update');
        Route::get('warehouse-pre-orders/store-orders/{storePreOrder}/generate-excel-bill', 'StorePreOrderController@generatePreOrderExcelBill')->name('warehouse-pre-orders.store-orders.generate-excel');
        Route::get('warehouse-pre-orders/store-orders/{storePreOrder}/generate-pdf-bill', 'StorePreOrderController@generatePreOrderPdfBill')->name('warehouse-pre-orders.store-orders.generate-pdf');

         Route::get('warehouse-pre-orders/{warehousePreOrderCode}/add-products', 'WarehousePreOrderProductController@addProductsPage')->name('warehouse-pre-orders.add-products');
         Route::get('warehouse-pre-orders/{warehousePreOrderCode}/products-list/{vendorCode}', 'WarehousePreOrderProductController@getPreOrderProductsList')->name('warehouse-pre-orders.products.index');
         Route::get('warehouse-pre-orders/{warehousePreOrderCode}/set-price/{productCode}', 'WarehousePreOrderProductController@getProductPriceSettingFormForPreOrder')->name('warehouse-pre-orders.set-price.create');
         Route::post('warehouse-pre-orders/{warehousePreOrderCode}/set-price/{productCode}', 'WarehousePreOrderProductController@setProductPriceSettingForPreOrder')->name('warehouse-pre-orders.set-price.store');

        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/view-price/{productCode}', 'WarehousePreOrderProductController@viewProductPriceSettingForPreOrder')->name('warehouse-pre-orders.view-price');
        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/edit-price/{productCode}', 'WarehousePreOrderProductController@editProductPriceSettingForPreOrder')->name('warehouse-pre-orders.edit-price');
        Route::post('warehouse-pre-orders/{warehousePreOrderCode}/update-price/{productCode}', 'WarehousePreOrderProductController@updateProductPriceSettingForPreOrder')->name('warehouse-pre-orders.update-price');

        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/edit-packaging/{productCode}', 'WarehousePreOrderProductPackagingController@editProductPackagingForPreOrder')->name('warehouse-pre-orders.edit-packaging');
        Route::post('warehouse-pre-orders/{warehousePreOrderCode}/update-packaging/{productCode}', 'WarehousePreOrderProductPackagingController@updateProductPackagingForPreOrder')->name('warehouse-pre-orders.update-packaging');

        Route::post('warehouse-pre-orders/{warehousePreOrderListingCode}/update-micro-packaging', 'WarehousePreOrderProductPackagingController@updatePreOrderProductsMicroPackaging')->name('warehouse-pre-orders.update-micro-packaging');


        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/toggle-status/{preOrderProductCode}', 'WarehousePreOrderProductController@togglePreOrderProductStatus')->name('warehouse-pre-orders.product.toggle-status');
        Route::delete('warehouse-pre-orders/{warehousePreOrderCode}/destroy/{preOrderProductCode}', 'WarehousePreOrderProductController@deletePreOrderProduct')->name('warehouse-pre-orders.product.destroy');
        Route::delete('warehouse-pre-orders/destroy/{warehousePreOrderProductCode}', 'WarehousePreOrderController@destroy')->name('warehouse-pre-orders.destroy');

        Route::delete('warehouse-pre-orders/{warehousePreOrderListingCode}/product/{preOrderProductCode}/delete', 'WarehousePreOrderProductController@deletePreOrderProductByProductCode')->name('warehouse-pre-orders.product.delete-by-product-code');
        Route::delete('warehouse-pre-orders/bulk-destroy/{preOrderProductListingCode}', 'WarehousePreOrderProductController@deleteAllProductsOfPreOrder')->name('warehouse-pre-orders.product.bulk-destroy');
        Route::post('warehouse-pre-orders/warehouse-preorder-product-change-status', 'WarehousePreOrderProductController@changeStatusOfPreOrderProducts')->name('warehouse-pre-order.All-products-status.changeStatus');


        Route::get('warehouse-pre-orders/toggle-status/{warehousePreOrderCode}', 'WarehousePreOrderController@togglePreOrderStatus')->name('warehouse-pre-orders.toggle-status');

       // Route::get('warehouse-pre-orders/finalize', 'WarehousePreOrderController@finalizePreOrders')->name('warehouse-pre-orders.finalize');
        Route::get('warehouse-pre-orders/{warehousePreOrderCode}/finalize', 'WarehousePreOrderController@finalizePreOrder')->name('warehouse-pre-orders.single.finalize');
        Route::post('warehouse-pre-orders/{whPreOrderListingCode}/clone-preorder-listing', 'WarehousePreOrderController@cloneWHPreOrderListing')->name('warehouse-pre-orders.single.clone');
        Route::post('warehouse-pre-orders/{warehousePreOrderCode}/cancel', 'WarehousePreOrderController@cancelPreOrder')->name('warehouse-pre-orders.single.cancel');
        Route::resource('warehouse-pre-orders', 'WarehousePreOrderController');

        Route::get('warehouse-pre-orders/clone-products/{preOrderListingCode}','WarehousePreOrderProductController@cloneWarehouseProductsByListingCode')
            ->name('warehouse-pre-orders.clone-products');

        Route::post('warehouse-pre-orders/clone-products/vendor-code/{preOrderListingCode}','WarehousePreOrderProductController@cloneVendorProductsByListingCode')
            ->name('warehouse-pre-orders.clone-products.vendor-code');


        Route::get('warehouse-pre-orders/{warehousePreOrderListingCode}/products/vendors-list', 'WarehousePreOrderProductController@listVendorsInPreOrders')->name('warehouse-pre-orders.products.vendors-list');
        Route::get('warehouse-pre-orders/{warehousePreOrderListingCode}/products/{vendorCode}/toggle-status/{status}', 'WarehousePreOrderProductController@toggleStatusVendorsProductsInPreOrders')->name('warehouse-pre-orders.products.vendors.toggle-status');
        Route::get('warehouse-pre-orders/{warehousePreOrderListingCode}/product/{productCode}/toggle-status/{status}', 'WarehousePreOrderProductController@changeStatusOfallVariantsinProduct')->name('warehouse-pre-orders.products.variants.toggle-status');


//        set preOrder Target
        Route::get('warehouse-pre-orders/target/{preOrderListingCode}','WarehousePreOrderTargetController@create')
            ->name('warehouse-pre-order-target.pre-order-target');
        Route::post('warehouse-pre-orders/target/update/{preOrderListingCode}','WarehousePreOrderTargetController@store')
            ->name('warehouse-pre-order-target.pre-order-target.update');
        Route::get('warehouse-pre-orders/target/{preOrderListingCode}/show','WarehousePreOrderTargetController@show')
            ->name('warehouse-pre-order-target.show');

        // rollback store-preorder-status-to-pending and unfinalized preorder
     //   Route::get('/warehouse-preorder/rollback/{warehousePreOrderListingCode}','WarehousePreOrderRollbackController@rollback');
        //ends here

        //early store pre order finalization
        Route::get('warehouse-pre-orders/store-pre-order/{storePreOrderCode}/early-finalize','StorePreOrderEarlyFinalizeController@earlyFinalizeCreate')->name('warehouse-pre-orders.store-pre-order.early-finalize.create');
        Route::post('warehouse-pre-orders/store-pre-order/{storePreOrderCode}/early-finalize','StorePreOrderEarlyFinalizeController@earlyFinalizeSave')->name('warehouse-pre-orders.store-pre-order.early-finalize.save');

        //early store pre order Cancellation
        Route::get('warehouse-pre-orders/store-pre-order/{storePreOrderCode}/early-cancel','StorePreOrderEarlyCancellationController@earlyCancelCreate')->name('warehouse-pre-orders.store-pre-order.early-cancel.create');
        Route::post('warehouse-pre-orders/store-pre-order/{storePreOrderCode}/early-cancel','StorePreOrderEarlyCancellationController@earlyCancelSave')->name('warehouse-pre-orders.store-pre-order.early-cancel.save');

    });

    Route::group([
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\ProductCollection',
    ], function() {
        Route::resource('warehouse-product-collections', 'WarehouseProductCollectionController');
        Route::get('warehouse-product-collections/{product_collection_code}/products','WarehouseProductCollectionController@showProductAdditionInCollection')->name('product-collection.show.add-products');
        Route::post('warehouse-product-collections/{product_collection_code}/products','WarehouseProductCollectionController@addProductsToCollection')->name('product-collection.add-products');
        Route::delete('warehouse-product-collections/{product_collection_code}/remove/product/{warehouse_product_master_code}','WarehouseProductCollectionController@removeProductFromCollection')->name('product-collection.remove-product');
    });

    Route::group([
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\StockTransfer',
    ], function () {
        Route::get('warehouse-stock-transfer', 'WarehouseStockTransferController@index')->name('stock-transfer.index');
        Route::get('warehouse-stock-transfer/received/stocks', 'WarehouseStockTransferController@warehouseReceivedStocks')->name('stock-transfer.received-stocks');
        Route::get('warehouse-stock-transfer/create', 'WarehouseStockTransferController@create')->name('stock-transfer.create');
        Route::post('warehouse-stock-transfer', 'WarehouseStockTransferController@store')->name('stock-transfer.store');
        Route::get('warehouse-stock-transfer/{stockTransferCode}/products', 'WarehouseStockTransferController@getProductsByStockTransferCode')->name('stock-transfer.products-stock-transfer-code');
        Route::get('warehouse-stock-transfer/{stockTransferCode}/received-products', 'WarehouseStockTransferController@getReceivedProductsByStockTransferCode')->name('stock-transfer.received-products-stock-transfer-code');
        Route::post('warehouse-stock-transfer/{stockTransferCode}/update-received-products-quantity', 'WarehouseStockTransferController@updateReceivedProductsQuantity')->name('stock-transfer.update-received-products-quantity');
        Route::get('warehouse-stock-transfer/{stockTransferCode}/add-products', 'WarehouseStockTransferController@addProductsPage')->name('stock-transfer.add-products');
        Route::get('warehouse-stock-transfer/{stockTransferCode}/get-product-lists', 'WarehouseStockTransferController@getProductLists')->name('stock-transfer.get-product-lists');
        //Route::post('warehouse-stock-transfer/{stockTransferCode}/add-products', 'WarehouseStockTransferController@addProductsStockTransferDetails')->name('stock-transfer.add-products-stock-transfer-details');
       // Route::post('warehouse-stock-transfer/{stockTransferCode}/add-products-draft', 'WarehouseStockTransferController@addProductsStockTransferDetailsDraft')->name('stock-transfer.add-products-stock-transfer-details-draft');
        Route::post('warehouse-stock-transfer/{stockTransferCode}/add-products-table', 'WarehouseStockTransferController@addProductsToTable')->name('stock-transfer.add-products-table');
        Route::post('warehouse-stock-transfer/{stockTransferCode}/delete-stock-details', 'WarehouseStockTransferController@deleteStockDetails')->name('stock-transfer.delete-stock-details');
        Route::post('warehouse-stock-transfer/{stockTransferCode}/add-delivery-detail', 'WarehouseStockTransferController@addDeliveryDetail')->name('stock-transfer.add-delivery-detail');
        Route::get('warehouse-stock-transfer/{stockTransferCode}/get-delivery-detail', 'WarehouseStockTransferController@getDeliveryDetail')->name('stock-transfer.get-delivery-detail');
        Route::get('warehouse-stock-transfer/{stockTransferCode}/get-received-delivery-detail', 'WarehouseStockTransferController@getReceivedDeliveryDetail')->name('stock-transfer.get-received-delivery-detail');
        Route::post('warehouse-stock-transfer/{stockTransferCode}/add-products', 'WarehouseStockTransferController@addProductToStockTransfer')->name('stock-transfer.add-products-stock-transfer-details');
        Route::post('warehouse-stock-transfer/{stockTransferCode}/add-products-draft', 'WarehouseStockTransferController@addProductToStockTransferDraft')->name('stock-transfer.add-products-stock-transfer-details-draft');

    });

});
Route::group([
    'module' => 'AlpasalWarehouse',
    'prefix' => 'warehouse',
    'as' => 'warehouse.',
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\ProductCollection',
    'middleware' => ['web','warehouse.auth','isWarehouseUser']

], function () {
    Route::get('/products/toggle-status/{productCollectionCode}/{productMasterCode}', 'WarehouseProductCollectionController@updateWHProductStatusOfCollection')->name('products.toggle-status');
    Route::get('/wh-product-collection/toggle-status/{productCollectionCode}', 'WarehouseProductCollectionController@updateWHProductCollectionStatus')->name('whproduct.toggle-status');

});

Route::group([
    'module'=>'AlpasalWarehouse',
    'prefix'=>'warehouse',
    'as'=>'warehouse.',
    'middleware' => ['web','warehouse.auth','isWarehouseUser']
], function() {

    Route::group([
        'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse',
    ], function() {
        Route::get('warehouse-products', 'WarehouseProductController@index')->name('warehouse-products.index');
        Route::get('warehouse-products/{productCode}', 'WarehouseProductController@show')->name('warehouse-products.show');
        Route::post('warehouse-products/update-price-setting/{wpmCode}', 'WarehouseProductController@updatePriceSetting')->name('warehouse-products.update-price-setting');

        /**status Is active toggle route**/
        Route::get('warehouse-products/toggle-status/{wpmCode}','WarehouseProductController@toggleWPMStatus')->name('warehouse-products-status.toggle');

        //******* change status start here*****//

        Route::POST('warehouse-products/change-status','WarehouseProductController@productStatusChange')->name('warehouse-products-status.changeStatus');
        Route::POST('warehouse-products/all-products/change-status','WarehouseProductController@warehouseAllProductsChangeStatus')->name('warehouse-all-products-status.changeWarehouseProductStatus');

        //***end herer****//

        //*** update micro packaging disable list**//
        Route::post('warehouse-products/update-micro-packaging', 'WarehouseProductPackagingController@updateProductsMicroPackaging')->name('warehouse-products.update-micro-packaging');


//        bill merge
        Route::get('bill-merge/merge-form','WarehouseBillMergeController@mergeForm')->name('bill-merge.merge-form');
        Route::get('bill-merge','WarehouseBillMergeController@index')->name('bill-merge.index');
        Route::get('merge-bill/{storeCode}','WarehouseBillMergeController@getOrder')->name('bill-merge');
        Route::post('merge-bill/merge','WarehouseBillMergeController@getMergedBill')->name('bill-merge.merge');
        Route::get('merge-bill/product-lists/{billMergeMasterCode}','WarehouseBillMergeController@getProductsByBillMergeMasterCode')->name('merge-bill.product-lists');
        Route::post('merge-bill/product-lists/{billMergeDetailCode}/details/{billMergeProductCode}/update','WarehouseBillMergeController@updateBillMergeProductDetail')->name('merge-bill.update.product-detail');
        Route::post('merge-bill/{billMergeMasterCode}/update-status','WarehouseBillMergeController@updateStatusOfBillMergeMaster')->name('merge-bill.update-status.master');

        Route::get('merge-bill/{billMergeMasterCode}/generate-bill','WarehouseBillMergeController@generateBill')->name('merge-bill.generate-bill.master');
        Route::get('merge-bill/{billMergeMasterCode}/details','WarehouseBillMergeController@getMergeOrderDetailsByMasterCode')->name('merge-bill.merge-order-details');



        /**order limit **/


        Route::put('warehouse-products/set-limit','WarehouseProductController@setWarehouseProductQtyOrderLimit')->name('warehouse-products.set-qty-limit-store');

        /**end here **/

        /**  Mass Update Product Price Setting **/

        Route::get('warehouse-products/create/{productCode}/mass-price-setting','WarehouseProductController@createMassPriceSettingOfProduct')->name('warehouse-products.create.mass-price-setting');
        Route::post('warehouse-products/store/{productCode}/mass-price-setting','WarehouseProductController@storeMassPriceSettingOfProduct')->name('warehouse-products.store.mass-price-setting');

        /** ends here */

        /**  Mass Update Product packaging disable list **/

        Route::get('warehouse-products/edit/{productCode}/mass-packaging-disable-list','WarehouseProductPackagingController@editWarehouseProductPackagingDisableList')->name('warehouse-products.edit.mass-packaging-disable-list');
        Route::post('warehouse-products/update/{productCode}/mass-packaging-disable-list','WarehouseProductPackagingController@updateWarehouseProductPackagingDisableList')->name('warehouse-products.update.mass-packaging-disable-list');

        /** ends here */


    });

});


Route::group([
    'module' => 'AlpasalWarehouse',
    'prefix' => 'warehouse',
    'as' => 'warehouse.',
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse',
    'middleware' => ['web', 'warehouse.auth', 'isWarehouseUser']
], function () {

    //Store Connection With WH
    Route::get('wh-store-connections', 'WHStoreConnectionController@getConnectedStores')->name('store.connections');
    Route::get('wh-store-connections/store/{storeCode}', 'WHStoreConnectionController@getStoreDetail')->name('store.connection-detail');
});

Route::group([
    'module' => 'AlpasalWarehouse',
    'prefix' => 'warehouse',
    'as' => 'warehouse.',
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\StoreOrder',
    'middleware' => ['web', 'warehouse.auth', 'isWarehouseUser']
], function () {

        Route::post('store/orders/{storeOrderCode}/update-delivery-status', 'WHStoreOrderController@updateStoreOrderDeliveryStatus')->name('store.orders.update-delivery-status');
//        Route::get('store/orders/all', 'WHStoreOrderController@index')->name('store.orders.index');
        Route::get('store/orders/{storeCode}/lists', 'WHStoreOrderController@getOrdersByStore')->name('store.orders.lists');
        Route::get('store/orders/pdf/{storeOrderCode}', 'WHStoreOrderController@generateStoreOrderPDF')->name('store.orders.pdf');
        Route::get('store/orders/{order}', 'WHStoreOrderController@show')->name('store.orders.show');
        Route::get('store/orders', 'WHStoreOrderController@index')->name('store.orders.index');

});

Route::group([
    'module'=>'AlpasalWarehouse',
    'prefix'=>'admin',
    'as'=>'admin.',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess'],
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Admin\PreOrder',
], function() {
    Route::get('warehouse-pre-orders', 'WarehousePreOrderProductController@getWarehousesHavingPreOrder')->name('warehouse-pre-orders.index');
    Route::get('warehouse-pre-orders/{warehouseCode}', 'WarehousePreOrderProductController@getPreOrdersInWarehouse')->name('warehouse-pre-orders.show');
    Route::get('warehouse-pre-orders/{warehousePreOrderListingCode}/vendors-list', 'WarehousePreOrderProductController@listVendorsInPreOrders')->name('warehouse-pre-orders.vendors-list');

    // ALL STATUS STORE PRE ORDERS
    Route::get('warehouse-pre-orders/store-orders/{vendorCode}/{warehousePreOrderListingCode}/vendor-list','WarehousePreOrderProductController@getStorePreOrderProductsByVendor')->name('warehouse-pre-orders.store-orders.in-vendor');
    Route::get('warehouse-pre-orders/finalized-store-orders/{vendorCode}/{warehousePreOrderListingCode}/vendor-list','WarehousePreOrderProductController@getFinalizedStorePreOrderProductsByVendor')->name('warehouse-pre-orders.finalized-store-orders.in-vendor');
    Route::get('warehouse-pre-order-detail/{vendorCode}/{warehousePreOrderListingCode}', 'WarehousePreOrderProductController@getProductsInPreOrder')->name('warehouse-pre-orders.pre-order-detail');

    //FINALIZED STORE PRE ORDER PRODUCTS
    Route::get('warehouse-pre-orders/store-orders/{vendorCode}/{warehousePreOrderListingCode}/vendor-list/export', 'WarehousePreOrderProductController@exportStorePreOrderProductsByVendor')->name('warehouse-pre-orders.store-orders.in-vendor.export');

    Route::get('warehouse-pre-orders/all-status-store-orders/{vendorCode}/{warehousePreOrderListingCode}/vendor-list/export', 'WarehousePreOrderProductController@exportallStatusStorePreOrderProductsByVendor')->name('warehouse-pre-orders.all-status-store-orders.in-vendor.export');

    Route::get('warehouse-pre-orders/store-orders-qty/{vendorCode}/{warehousePreOrderListingCode}/{productCode}', 'WarehousePreOrderProductController@getOrderQtyByStore')->name('store-order-qty');
    Route::get('warehouse-pre-orders/store-orders-qty-finalized/{vendorCode}/{warehousePreOrderListingCode}/{productCode}', 'WarehousePreOrderProductController@getFinalizedOrderQtyByStore')->name('store-order-qty-finalized');



//    for ajax request
    Route::post('products-of-vendor','WarehousePreOrderProductController@getProductsOfVendor')->name('products-of-vendor');
    Route::post('variants-of-product','WarehousePreOrderProductController@getVariantsOfProduct')->name('variants-of-product');

    Route::post('warehouse-pre-orders/clone-products/source-to-destination','WarehousePreOrderProductController@cloneProductsFromSourceToDestinationListingCode')
        ->name('warehouse-pre-orders.clone-products.source-to-destination');

//    preoredr reporting
    Route::get('pre-orders-reporting','PreorderReportingController@getPreordersReportingForm')
        ->name('pre-orders-reporting.getPreordersReporting');

//    ajax call
    Route::get('pre-orders-reporting/{storeCode}/{preorderCode}','PreorderReportingController@getPreordersReporting')
        ->name('pre-orders-reporting.search');

    Route::get('reporting','ReportingController@getReportingData')
        ->name('reporting.getReportingData');
    Route::get('reporting/excel-export/report','ReportingController@excelExportReport')
        ->name('reporting.excelExportReport');

});

Route::group([
    'module'=>'AlpasalWarehouse',
    'prefix'=>'admin',
    'as'=>'admin.',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess'],
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Admin\PreOrder',
], function() {
    Route::get('warehouse-pre-orders', 'WarehousePreOrderProductController@getWarehousesHavingPreOrder')->name('warehouse-pre-orders.index');

});

Route::group([
    'module'=>'AlpasalWarehouse',
    'prefix'=>'warehouse',
    'as'=>'warehouse.',
    'middleware' => ['web','admin.auth','ipAccess'],
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\Setting',
], function() {

    Route::get('settings','InvoiceSettingController@settings')->name('settings');
    Route::resource('warehouse-settings-invoice', 'InvoiceSettingController');

    Route::resource('min-order-settings', 'MinOrderSettingController');
    Route::get('min-order-settings/change-status/{minOrderCode}','MinOrderSettingController@changeMinOrderStatus')
           ->name('min-order-settings.changeMinOrderStatus');
//    Route::get('warehouse/settings/invoice/create', 'InvoiceSettingController@create')->name('warehouse.settings.invoice.create');
//    Route::post('warehouse/settings/invoice/store', 'InvoiceSettingController@store')->name('warehouse.settings.invoice.store');
//    Route::get('warehouse/settings/invoice/{settingWarehouseInvoiceCode}/edit', 'InvoiceSettingController@edit')->name('warehouse.settings.invoice.edit');
//    Route::post('warehouse/settings/invoice/{settingWarehouseInvoiceCode}/update', 'InvoiceSettingController@update')->name('warehouse.settings.invoice.update');
});

Route::group([
    'module' => 'AlpasalWarehouse',
    'prefix' => 'warehouse',
    'as' => 'warehouse.',
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\CurrentStock',
    'middleware' => ['web', 'warehouse.auth', 'isWarehouseUser']
], function () {

    Route::get('vendor-wise/current-stock', 'VendorWiseStockReportingController@index')
        ->name('vendor-wise.current-stock.index');
    Route::get('vendor-wise/current-stock/detail/{vendorCode}', 'VendorWiseStockReportingController@getVendorWiseProduct')
        ->name('vendor-wise.current-stock.detail');
    Route::get('vendor-wise/current-stock/detail/{vendorCode}/export', 'VendorWiseStockReportingController@exportExcellVendorWiseProductStockReport')
        ->name('vendor-wise.current-stock.exportExcellVendorWiseProductStockReport');

});

Route::group([
    'module' => 'AlpasalWarehouse',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Admin\AdminCurrentStock',
    'middleware' => ['web', 'admin.auth', 'isAdmin']
], function () {

    Route::get('warehouse-wise/current-stock', 'WarehouseWiseStockReportingController@index')
        ->name('warehouse-wise.current-stock.index');
    Route::get('warehouse-wise/current-stock/warehouse/{warehouseCode}', 'WarehouseWiseStockReportingController@getWarehouseWiseProduct')
        ->name('warehouse-wise.current-stock.warehouse.detail');
    Route::get('warehouse-wise/current-stock/detail/{warehouseCode}/{vendorCode}', 'WarehouseWiseStockReportingController@getVendorWiseProduct')
        ->name('warehouse-wise.current-stock.detail');
    Route::get('warehouse-wise/current-stock/detail/{warehouseCode}/{vendorCode}/export', 'WarehouseWiseStockReportingController@exportExcellVendorWiseProductStockReport')
        ->name('warehouse-wise.current-stock.exportExcellVendorWiseProductStockReport');

});
include app_path('Modules/AlpasalWarehouse/Routes/warehouse-filter-routes.php');
include app_path('Modules/AlpasalWarehouse/Routes/warehouse-store-group-routes.php');
include app_path('Modules/AlpasalWarehouse/Routes/warehouse-dispatch-route-routes.php');

