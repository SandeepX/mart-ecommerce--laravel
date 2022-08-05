<?php

use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'OTP',
    'namespace' => 'App\Modules\OTP\Controllers\Web',
    'middleware' => ['isMaintenanceModeOn','auth:api']
], function() {

    Route::post('otp/send', 'OtpController@createOTP')->middleware('throttle:1,1');
    Route::post('otp/verification', 'OtpController@verifyOTP');

});

//throttle:1,1 one request per one minute
