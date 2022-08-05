<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'Store',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Store\Controllers\Web\Admin\Balance',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    /**store Withdraw  route starts here**/
    Route::get('balance-management/withdraw', 'StoreBalanceWithdrawController@getAllWithdrawRequest')->name('balance.withdraw');
    Route::get('balance-management/withdraw-lists/{storeCOde}', 'StoreBalanceWithdrawController@getAllWithdrawRequestOfStore')->name('balance.withdraw-lists');
    Route::get('balance-management/withdraw/{withdraw_request_code}','StoreBalanceWithdrawController@getwithdrawdetailById')->name('stores.balance-withdrawRequest.show');
    Route::post('balance-management/withdraw/{store_code}/verify','StoreBalanceWithdrawController@respondToWithdrawRequest')->name('stores.balance-withdrawRequest.verify');
    Route::get('balance-management/withdraw-verification/add-verification-detail/{withdraw_request_code}','StoreBalanceWithdrawController@VerificationDetailForm')->name('stores.balance-withdrawRequest.add-verification-detail-form');
    Route::post('balance-management/withdraw-verification/add-verification-detail/store/{withdraw_request_code}','StoreBalanceWithdrawController@storeVerificationDetail')->name('stores.balance-withdrawRequest.store-verification-detail');
    Route::get('balance-management/withdraw-verification/change-verification-status/{verification_detail_code}','StoreBalanceWithdrawController@changeWithdrawVerificationDetailStatus')->name('balance.withdraw-verification-detail.change-status');
    /** ends here */

    /** Store Balance Management routes Starts Here */
    Route::get('balance-management/balance', 'StoreBalanceManagementController@getStoreBalances')->name('store.balance.list');
    Route::get('store/balance-management/balance/{store_code}', 'StoreBalanceManagementController@getStoreBalanceDetail')->name('store.balance.detail');
    Route::get('balance-management/balance/export', 'StoreBalanceManagementController@exportBalance')->name('store.balance.export');
    /** ends here */

    /***reconciliation routes here***/
    Route::get('balance-management/reconcilition','StoreBalanceReconciliationController@index')->name('balance.reconciliation');
    Route::get('balance-management/reconcilition/create','StoreBalanceReconciliationController@create')->name('balance.reconciliation.create');
    Route::post('balance-management/reconcilition/store','StoreBalanceReconciliationController@store')->name('balance.reconciliation.store');
    Route::post('balance-management/reconcilition/getpayment-body','StoreBalanceReconciliationController@getPaymentBody')->name('balance.reconciliation.getpayment-body');
    Route::post('balance-management/reconcilition/getpayment-body/update','StoreBalanceReconciliationController@getPaymentBodyForUpdate')->name('balance.reconciliation.getpayment-body.update');

//    Route::delete('balance-management/reconcilition/{balance_reconciliation_code}','StoreBalanceReconciliationController@destroy')->name('balance.reconciliation.destroy');
    Route::get('balance-management/reconcilition/get-detail/{balance_reconciliation_code}','StoreBalanceReconciliationController@show')->name('balance.reconciliation.show');
    Route::get('balance-management/reconcilition/edit/{balance_reconciliation_code}','StoreBalanceReconciliationController@edit')->name('balance.reconciliation.edit');
    Route::put('balance-management/reconcilition/update/{balance_reconciliation_code}','StoreBalanceReconciliationController@update')->name('balance.reconciliation.update');


    Route::post('balance-management/reconcilition/change-status','StoreBalanceReconciliationController@changeStatus')->name('balance.reconciliation.change-status');
    Route::get('balance-management/reconcilition/import', 'StoreBalanceReconciliationController@getImportPage')->name('balance.reconciliation.get-import-page');
    Route::post('balance-management/reconcilition/import', 'StoreBalanceReconciliationController@importReconciliation')->name('balance.reconciliation.import');

    /****ends here****/

    /*** Admin Balance Control  Route */
    Route::get('/store-balance-control/create/{storeCode}','AdminStoreBalanceControlController@create')->name('store-balance-control.create');
    Route::post('/store-balance-control/store/{storeCode}','AdminStoreBalanceControlController@store')->name('store-balance-control.store');
    /** ends here */

    /***** Admin Update Remarks ***/

    Route::put('balance-management/reconciliation/{balanceReconciliationCode}/usages/create-remarks','BalanceReconciliationUsagesRemarkController@updateRemarks')->name('balance.reconciliation.usages.create.remarks');

});
