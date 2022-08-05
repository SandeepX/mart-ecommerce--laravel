<?php

Route::group([
    'module'=>'PaymentMethod',
    'prefix'=>'api',
    'namespace' => 'App\Modules\PaymentMethod\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn','auth:api']
], function() {

    Route::get('payment-lists','PaymentsController@getListsOfPayments');
//    Route::get('remits', 'PaymentMethodController@getRemitsList');

});
