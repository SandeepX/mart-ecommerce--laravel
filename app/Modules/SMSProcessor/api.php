<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'api', 'module' => 'SMSProcessor'], function () {

    Route::group([
        'namespace' => 'App\Modules\SMSProcessor\Controllers',
        'middleware' => ['isMaintenanceModeOn']
    ] , function () {
        Route::post('send-sms', 'SendSmsController@sendSMS');
    });

});

