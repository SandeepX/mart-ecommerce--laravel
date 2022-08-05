<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api', 'module' => 'B2cCustomer'], function () {

    Route::group([
        'namespace' => 'App\Modules\B2cCustomer\Controllers\Api\Auth',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {

        Route::post('b2c-user-registration', 'B2CUserRegistrationController@storeB2CUserFromApi');
        Route::post('b2c-user-login', 'B2CUserAuthenticationController@loginB2CUser');
        Route::get('b2c-user/account/status', 'B2CUserRegistrationController@findB2CUserAccountStatus')->middleware('isB2CUser');

    });

    Route::group([
        'namespace' => 'App\Modules\B2cCustomer\Controllers\Api',
        'middleware' => ['isMaintenanceModeOn','auth:api','isB2CUser']
    ], function () {
        Route::get('b2c-user/profile','B2CController@getProfile');
        Route::post('b2c-user/profile/update', 'B2CController@updateProfile');
    });
});


