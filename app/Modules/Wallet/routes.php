<?php


use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'Wallet',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Wallet\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

      Route::get('wallets','WalletController@index')->name('wallets.index');

});

Route::group([
    'module' => 'Wallet',
    'prefix' => 'admin/wallets',
    'as' => 'admin.wallets.',
    'namespace' => 'App\Modules\Wallet\Controllers\Web\Admin',
    'middleware' => ['web', 'admin.auth', 'isAdmin', 'ipAccess']
], function () {
    Route::get('transaction/{walletTransactionCode}/extra-remarks','WalletTransactionController@viewRemarks')->name('transaction.extra-remarks.view');
    Route::get('transaction/{walletTransactionCode}/extra-remarks/create','WalletTransactionController@createRemarks')->name('transaction.extra-remarks.create');
    Route::post('transaction/{walletTransactionCode}/extra-remarks/save','WalletTransactionController@saveRemarks')->name('transaction.extra-remarks.save');
    Route::resource('transactions-purpose','WalletTransactionPurposeController');
    Route::get('transactions-purpose/toggleStatus/{wallettransactionPurposeCode}','WalletTransactionPurposeController@toggleStatus')->name('transactions-purpose.toggleStatus');

});

Route::group([
    'module' => 'Wallet',
    'prefix' => 'admin/wallets/transactions',
    'as' => 'admin.wallet.transactions.',
    'namespace' => 'App\Modules\Wallet\Controllers\Web\Admin\Store',
    'middleware' => ['web', 'admin.auth', 'isAdmin', 'ipAccess']
], function () {

    Route::get('store/{walletCode}/list','AdminWalletStoreTransactionControlController@storeWalletTransactionDetail')->name('store.details');
    Route::get('store/dispatch/{walletCode}/transaction-list','AdminWalletStoreTransactionControlController@storeWalletDispatchTransactionLists')->name('store.dispatch.details');
    Route::get('store/excel-export/{walletCode}','AdminWalletStoreTransactionControlController@excelExport')->name('excel-export');

    /*** Admin Store Transaction Control  Route */
    Route::get('control/store/{walletCode}/create','AdminWalletStoreTransactionControlController@create')->name('control.store.create');
    Route::post('control/store/{walletCode}/save','AdminWalletStoreTransactionControlController@store')->name('control.store.save');
    /** ends here */

});

Route::group([
    'module' => 'Wallet',
    'prefix' => 'admin/wallets/transactions',
    'as' => 'admin.wallet.transactions.',
    'namespace' => 'App\Modules\Wallet\Controllers\Web\Admin\Manager',
    'middleware' => ['web', 'admin.auth', 'isAdmin', 'ipAccess']
], function () {

    Route::get('manager/{walletCode}/list','AdminWalletManagerTransactionControlController@managerWalletTransactionDetail')->name('manager.details');


});

Route::group([
    'module' => 'Wallet',
    'prefix' => 'admin/wallets/transactions',
    'as' => 'admin.wallet.transactions.',
    'namespace' => 'App\Modules\Wallet\Controllers\Web\Admin\Vendor',
    'middleware' => ['web', 'admin.auth', 'isAdmin', 'ipAccess']
], function () {

    Route::get('vendor/{walletCode}/list','AdminWalletVendorTransactionControlController@vendorWalletTransactionDetail')->name('vendor.details');

});

/******Day Book route******/
Route::group([
    'module' => 'Wallet',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Wallet\Controllers\Web\Admin',
    'middleware' => ['web', 'admin.auth', 'isAdmin', 'ipAccess']
], function () {

    //load balance routes
    Route::get('wallet/load-balance/offline-payment/{offlinePaymentCode}/show','WalletLoadBalanceController@showOfflinePaymentDetails')
                                ->name('wallet.offline-payment.load-balance.show');
    Route::post('wallet/load-balance/offline-payment/{offlinePaymentCode}/respond','WalletLoadBalanceController@respondToOfflinePayment')
                                ->name('wallet.offline-payment.load-balance.respond');
    Route::get('wallet/load-balance/online-payment/{onlinePaymentCode}/respond','WalletLoadBalanceController@respondToOnlinePayment')
                                        ->name('wallet.online-payment.load-balance.respond');

    //daybook routes
    Route::get('daybook', 'DaybookController@index')->name('daybook.index');
    Route::get('daybook/transaction-purposes', 'DaybookController@getTransactionPurposeByFlow')->name('daybook.transaction-purpose');

    //extra-remark
    Route::get('daybook/extra-remark/{walletTransactionCode}/view', 'DaybookController@viewRemarks')->name('daybook.view-extra-remark');
    Route::get('daybook/extra-remark/{walletTransactionCode}/create', 'DaybookController@createRemarks')->name('daybook.create-extra-remark');
});







