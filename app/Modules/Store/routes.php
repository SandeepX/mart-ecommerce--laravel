<?php
use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'Store',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Store\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    //Route::get('stores/warehouses', 'StoreWarehouseController@getStoreWarehouses')->name('stores.warehouses.index');
    Route::get('stores/{store}/warehouses', 'StoreWarehouseController@showStoreWarehouses')->name('stores.warehouses.show');
    //Route::get('store/create-warehouses', 'StoreWarehouseController@storeWarehousePage')->name('stores.warehouses.create');
    Route::post('store/store-warehouses', 'StoreWarehouseController@syncStoreWarehouses')->name('stores.warehouses.sync');
    Route::get('store-warehouses/{store}/toggle-connection/{warehouse}', 'StoreWarehouseController@toggleConnectionStatus')->name('stores.warehouses.toggle-connection');
    Route::get('stores/{store}/edit-warehouses', 'StoreWarehouseController@editStoreWarehouses')->name('stores.warehouses.edit');


    Route::get('stores-kyc/individuals', 'StoreKycController@getAllIndividualsKyc')->name('stores-kyc.individuals');
    Route::get('stores-kyc/individuals/{kyc_code}', 'StoreKycController@showIndividualKyc')->name('stores-kyc.individuals.show');
    Route::post('stores-kyc/individuals/{kyc_code}/respond', 'StoreKycController@respondToIndividualKyc')->name('stores-kyc.individuals.respond');
    Route::get('stores-kyc/firms', 'StoreKycController@getAllFirmsKyc')->name('stores-kyc.firms');
    Route::get('stores-kyc/firms/{kyc_code}', 'StoreKycController@showFirmKyc')->name('stores-kyc.firms.show');
    Route::post('stores-kyc/firms/{kyc_code}/respond', 'StoreKycController@respondToFirmKyc')->name('stores-kyc.firms.respond');

    Route::post('stores-kyc/individual/{kyc_code}/allow-update-request', 'StoreKycController@allowIndividualKycUpdateRequest')->name('stores-kyc.individual.allow-update-request');
    Route::post('stores-kyc/firm/{kyc_code}/allow-update-request', 'StoreKycController@allowFirmKycUpdateRequest')->name('stores-kyc.firm.allow-update-request');

    /** Store kyc listing */

    Route::get('stores-kyc/listings','StoreKycListingController@newIndex')->name('stores-kyc.listings');

    /** Store kyc listing end here */

   Route::get('stores/miscellaneous-payments', 'StoreMiscellaneousPaymentController@getAllStoreMiscPayments')->name('stores.misc-payments.index');
    //Route::get('stores/miscellaneous-payments', 'StoreMiscellaneousPaymentController@getAllStoreMiscPaymentsByUsingGroupBy')->name('stores.misc-payments.index');

    Route::get('stores/miscellaneous-payments/{user_code}/{payment_for}', 'StoreMiscellaneousPaymentController@showMiscPaymentsByStoreAndPaymentFor')->name('stores.misc-payments-detaillog.show');
    Route::get('stores/miscellaneous-payments/{user_code}/{payment_for}/matched', 'StoreMiscellaneousPaymentController@showMatchedMiscPaymentsByStoreAndPaymentFor')->name('stores.misc-payments-detaillog.matched.show');
    Route::get('stores/miscellaneous-payments/{user_code}/{payment_for}/unmatched', 'StoreMiscellaneousPaymentController@showUnMatchedMiscPaymentsByStoreAndPaymentFor')->name('stores.misc-payments-detaillog.unmatched.show');

    Route::get('stores/miscellaneous-payments/{payment_code}', 'StoreMiscellaneousPaymentController@showStoreMiscPayment')->name('stores.misc-payments.show');
    Route::get('stores/miscellaneous-payments/{payment_code}/edit/payments', 'StoreMiscellaneousPaymentController@editStoreMiscPayment')->name('stores.misc-payments.edit.payments');
    Route::post('stores/miscellaneous-payments/{payment_code}/update/payments', 'StoreMiscellaneousPaymentController@updateStoreMiscPayment')->name('stores.misc-payments.update.payments');

    ///miscellaneous payment remarks
    Route::get('stores/miscellaneous-payments/{payment_code}/remarks/list', 'MiscellaneousPaymentRemarkController@viewRemarksByMiscPaymentCode')->name('stores.misc-payments.remarks.list');
    Route::get('stores/miscellaneous-payments/{payment_code}/remarks/create', 'MiscellaneousPaymentRemarkController@createRemarks')->name('stores.misc-payments.remarks.create');
    Route::post('stores/miscellaneous-payments/{payment_code}/remarks/save', 'MiscellaneousPaymentRemarkController@saveRemarks')->name('stores.misc-payments.remarks.save');

    /****route****/

    Route::post('stores/miscellaneous-payments/{payment_code}/respond', 'StoreMiscellaneousPaymentController@respondToStoreMiscPayment')->name('stores.misc-payments.respond');
    /**** */
//    Route::get('stores/offline-order-payments', 'StoreOrderOfflinePaymentController@getAllStoreOfflinePayments')->name('stores.offline-order-payments.index');
//    Route::get('stores/offline-order-payments/{payment_code}', 'StoreOrderOfflinePaymentController@showStoreOfflinePayment')->name('stores.offline-order-payments.show');
//    Route::post('stores/offline-order-payments/{payment_code}/respond', 'StoreOrderOfflinePaymentController@respondToStoreOfflinePayment')->name('stores.offline-order-payments.respond');

    Route::get('stores/unapproved','StoreController@getUnapprovedStores')->name('stores.store-registration.unapproved');
    Route::resource('stores', 'StoreController');
    Route::get('/store/change-status/{storeCode}/{status}', 'StoreController@changeStatus')
        ->name('store.toggle-status');

    Route::get('stores/{store}/documents', 'StoreDocumentController@create')->name('stores.documents.create');
    Route::post('stores/{store}/documents', 'StoreDocumentController@store')->name('stores.documents.store');
    Route::delete('stores/{store}/documents/{banner}', 'StoreDocumentController@destroy')->name('stores.documents.destroy');


    Route::post('store/orders/{storeOrderCode}/update-delivery-status','StoreOrderController@updateStoreOrderDeliveryStatus')->name('store.orders.update-delivery-status');
    Route::get('store/orders', 'StoreOrderController@index')->name('store.orders.index');
    Route::get('store/orders/pdf/{storeOrderCode}', 'StoreOrderController@generateStoreOrderPDF')->name('store.orders.pdf');
    Route::get('store/orders/filter', 'StoreOrderController@filter')->name('store.orders.filter');
    Route::get('store/orders/{order}', 'StoreOrderController@show')->name('store.orders.show');
    Route::put('store/orders/{order}', 'StoreOrderController@update')->name('store.orders.update');
    Route::get('export-excel-order', 'StoreOrderController@exportExcelStoreOrder')->name('store.orders.exportExcelStoreOrder');

    /* toggle purchase power of store */
    Route::get('store/{storeCode}/purchase-power/toggle-status','StoreController@togglePurchasePowerStatus')->name('store.purchase-power.toggle-status');
    /* ends here */

    Route::get('warehouse-pre-orders/{warehousePreOrderListingCode}/store/preorders','StorePreOrderController@index')->name('warehouse.listings.store.pre-orders.index');
    Route::get('store/preorders/{storePreOrderCode}','StorePreOrderController@show')->name('store.pre-orders.show');

    /** Admin Balance Control Route end here  */
});

//change store status as approved or rejected
Route::group([
    'module'=>'Store',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Store\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {
    Route::post('/stores/update-status/{storeCode}', 'StoreController@updateStatus')->name('store.update.status');
});

Route::group([
    'module'=>'Store',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Store\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {
    Route::get('/stores/{storeCode}/complete-detail', 'StoreCompleteDetailController@getStoreCompleteDetail')->name('store.complete.detail');

    Route::get('/stores/{storeCode}/kyc', 'StoreCompleteDetailController@getStoreKYC')->name('store.kyc');

    Route::get('/stores/{storeCode}/order', 'StoreCompleteDetailController@getStoreOrder')->name('store.order');
    Route::get('/stores/{storeOrderCode}/order/details', 'StoreCompleteDetailController@showStoreOrderDetail')->name('store.order.details');

    Route::get('/stores/{storeCode}/miscellaneous-payment', 'StoreCompleteDetailController@getStoreMiscellaneousPayment')->name('store.miscellaneous');
    Route::get('/stores/{paymentCode}/miscellaneous/details', 'StoreCompleteDetailController@showStoreMiscellaneousPaymentDetail')->name('store.miscellaneous.details');

    Route::get('/stores/{storeCode}/general-detail', 'StoreCompleteDetailController@getStoreGeneralDetail')->name('store.general.detail');

    Route::get('/stores/{storeCode}/balance', 'StoreCompleteDetailController@getStoreBalance')->name('store.balance');
    Route::get('/stores/{storeCode}/balance/withdraw-request', 'StoreCompleteDetailController@getStoreWithdrawRequest')->name('store.balance.withdraw');
    Route::get('/stores/{withRequestCode}/withdraw/detail', 'StoreCompleteDetailController@showStoreWithdrawDetail')->name('store.withdraw.detail');
    Route::get('/stores/{storeCode}/balance-transaction', 'StoreCompleteDetailController@getStoreBalanceTransaction')->name('store.balance.transaction');

    Route::get('/stores/{storeCode}/pre-order', 'StoreCompleteDetailController@getStorePreorder')->name('store.pre-order');
    Route::get('/stores/preorder/{storePreorderCode}/details', 'StoreCompleteDetailController@showStorePreorderDetail')->name('store.pre-order.detail');


    Route::get('/stores/{userCode}/enquiry-message', 'StoreCompleteDetailController@getStoreEnquiryMessage')->name('store.enquiry-message');
});

Route::group([
    'module'=>'Store',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Store\Controllers\Web\Admin\StorePackageTypes',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('/store-type-packages/{storeTypeCode}', 'StoreTypePackageMasterController@index')
        ->name('store-type-packages.index');
    Route::post('/store-type-packages/store', 'StoreTypePackageMasterController@store')
        ->name('store-type-packages.store');
    Route::get('/store-type-packages/edit/{storeTPCode}', 'StoreTypePackageMasterController@edit')
        ->name('store-type-packages.edit');
    Route::get('/store-type-packages/show/{storeTPCode}', 'StoreTypePackageMasterController@show')
        ->name('store-type-packages.show');
    Route::put('/store-type-packages/update/{storeTPCode}', 'StoreTypePackageMasterController@update')
        ->name('store-type-packages.update');
    Route::delete('/store-type-packages/destroy/{storeTPCode}', 'StoreTypePackageMasterController@destroy')
        ->name('store-type-packages.destroy');
    Route::get('/store-type-packages/change-status/{storeTPCode}/{status}', 'StoreTypePackageMasterController@changeStatus')
        ->name('store-type-packages.toggle-status');


    Route::post('store-type-packages/change-display-order/{storeTPCode}', 'StoreTypePackageMasterController@changePackageDisplayOrder')
        ->name('store-type-packages.change-display-order');

    // store update package routes starts here
    Route::get('store-package/{storeCode}/update/form','StorePackageController@updateForm')->name('store.package.update.form');
    Route::post('store-package/{storeCode}/update','StorePackageController@update')->name('store.package.update');
    //ends here

});


include app_path('Modules/Store/Routes/balance-routes.php');


