<?php

use Illuminate\Support\Facades\Route;
    Route::group([
        'module'=>'GlobalNotification',
        'prefix'=>'api',
        'namespace' => 'App\Modules\GlobalNotification\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function() {

        Route::get('alerts', 'GlobalNotificationController@getAllNotifications');

    });
