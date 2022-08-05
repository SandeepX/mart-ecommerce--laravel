<?php

Route::group([
    'module'=>'Newsletter',
    'prefix'=>'api',
    'namespace' => 'App\Modules\Newsletter\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn']
], function() {
    Route::group([
        'prefix'=>'newsletter',
    ], function() {
        Route::post('subscribers', 'SubscriberController@storeSubscriber');
        Route::get('subscribe/confirmation/{token}','SubscriberController@confirmSubscription')->name('fe.confirmSubscription');
    });
});