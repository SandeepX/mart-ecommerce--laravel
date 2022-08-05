<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function(){
    Route::group([
        'module' => 'User',
        'prefix' => 'user',
        'namespace' => 'App\Modules\User\Controllers\Api\Frontend',
        'middleware' => ['isMaintenanceModeOn','auth:api']
    ], function () {
        Route::post('/change-first-password', 'UserPasswordController@changeFirstPassword')->middleware('checkScope:manage-all');
        Route::post('/change-password', 'UserPasswordController@changePassword')->middleware('checkScope:manage-all');
        Route::get('/notifications', 'UserNotificationController@index');
        Route::post('/notifications/{notification}/mark-read', 'UserNotificationController@markAsRead')->middleware('checkScope:manage-all');
        Route::post('/update-avatar', 'ProfileController@updateAvatar')->middleware('checkScope:manage-all');
        Route::get('account-info', 'ProfileController@getUserAccountInformation');
    });


    Route::group([
        'module' => 'User',
        'prefix' => 'user',
        'namespace' => 'App\Modules\User\Controllers\Auth',
        'middleware' => ['isMaintenanceModeOn','checkScope:manage-all']
    ], function () {

         Route::post('send-reset-email', 'UserForgotPasswordController@sendResetLinkEmail');
         Route::post('password/reset', 'UserPasswordResetController@reset');
         Route::post('refresh-token', 'UserRefreshTokenController@getAccessTokenFromRefreshToken');

    });

    Route::group([
        'module' => 'User',
        'prefix' => 'user',
        'namespace' => 'App\Modules\User\Controllers\Auth',
        'middleware' => ['isMaintenanceModeOn','api','auth:api']
    ], function () {
         Route::post('logout', 'UserLogoutController@logoutAuthenticatedUser');
    });
// check email and phone number routes
    Route::group([
        'module' => 'User',
        'namespace' => 'App\Modules\User\Controllers\Api\Frontend',
        'middleware' => ['isMaintenanceModeOn']
    ], function(){
        Route::get('check-email-exists','CheckEmailPhoneExistsController@checkEmailExists');
        Route::get('check-phone-exists','CheckEmailPhoneExistsController@checkPhoneExists');
    });

//email verification routes
    Route::group([
        'module' => 'User',
        'namespace' => 'App\Modules\User\Controllers\Auth',
    ], function () {

        Auth::routes(['verify' => true]);
        Route::get('/email/resend/{id}', 'VerificationController@resend')->name('verification.resend');
        Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
    });


    //sales manager account registration otp
    Route::group([
        'namespace' => 'App\Modules\User\Controllers\Api\Frontend',
        'middleware' => ['isMaintenanceModeOn']
    ],function (){

        Route::post('user/account-verification/otp/send','UserRegistrationOTPApiController@createOTP');
        Route::post('user/account-verification/otp/verify','UserRegistrationOTPApiController@verifyOTP');

        //user forget password
        Route::post('user/forget-password/otp/send','UserForgetPasswordOTPController@generateOTP');
        Route::post('user/forget-password/otp/verify','UserForgetPasswordOTPController@verifyOTP');

    });

});



