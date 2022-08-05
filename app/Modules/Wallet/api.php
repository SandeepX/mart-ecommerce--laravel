<?php

use Illuminate\Support\Facades\Route;

//Front End Routes
Route::group([
    'module' => 'Wallet',
    'prefix' => 'api/wallet/',
    'namespace' => 'App\Modules\Wallet\Controllers\Api\Admin',
    'middleware' => ['isMaintenanceModeOn']
], function () {

    Route::get('transaction-purpose/{walletType}/type/{userTypeCode}', 'WalletTransactionPurposeControllerApi@getAllTransactionPurposesByPurposeAndUserType');

});

Route::group([
    'module' => 'Wallet',
    'prefix' => 'api/wallet/',
    'namespace' => 'App\Modules\Wallet\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn','auth:api']
], function () {

    Route::get('current-balance','WalletController@getCurrentBalance');
    Route::post('load-balance/offline/save','WalletLoadBalanceApiController@saveOfflineLoadBalance');

});


