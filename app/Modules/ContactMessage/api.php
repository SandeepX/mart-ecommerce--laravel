<?php

Route::group([
    'module'=>'ContactMessage',
    'prefix'=>'api',
    'namespace' => 'App\Modules\ContactMessage\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn']
], function() {
    Route::group([
        'prefix'=>'contact-messages',
    ], function() {

        Route::post('/store', 'ContactMessageApiController@storeContactMessage');

    });
});