<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api', 'module' => 'PaymentGateway'], function () {
    //connect-ips
    Route::group([
        'namespace' => 'App\Modules\PaymentGateway\Controllers\Api\Front\ConnectIPS',
        'middleware' => ['isMaintenanceModeOn','auth:api']
    ], function () {
        Route::post('connect-ips/load-balance/save/payment', 'ConnectIPSApiController@paymentStore');
        Route::get('connect-ips/validate-payment/{transactionId}', 'ConnectIPSApiController@validatePayment');
    });
});


