<?php

use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'OTP',
    'prefix'=>'api',
    'namespace' => 'App\Modules\OTP\Controllers\Api',
    'middleware' => ['isMaintenanceModeOn','auth:api']
], function() {

    Route::post('otp/send', 'OtpController@createOTP')->middleware('throttle:1,1,send_otp');
    Route::post('otp/verification', 'OtpController@verifyOTP');

});
Route::group([
    'module'=>'OTP',
    'prefix'=>'api',
    'namespace' => 'App\Modules\OTP\Controllers\Api',
    'middleware' => ['isMaintenanceModeOn']
], function() {
    Route::post('account-verification/send-phone-otp-code','OTPAccountVerificationsController@generatePhoneOTPVerificationsCode')->middleware('throttle:1,1,phone_otp_account_verification');
    Route::post('account-verification/send-email-otp-code','OTPAccountVerificationsController@generateEmailOTPVerificationsCode')->middleware('throttle:1,1,email_otp_account_verification');
});

//throttle:1,1 one request per one minute
