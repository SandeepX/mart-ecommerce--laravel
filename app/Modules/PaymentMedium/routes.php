<?php

Route::group([
    'module'=>'PaymentMedium',
    'prefix'=>'api',
    'namespace' => 'App\Modules\PaymentMedium\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn']
], function() {

    Route::get('remits', 'PaymentMediumController@getRemitsList');
    Route::get('digital-wallets', 'PaymentMediumController@getDigitalWalletsList');

});
