<?php

Route::group([
    'module'=>'AdminWarehouse',
    'prefix'=>'warehouse',
    'as'=>'warehouse.',
    'middleware'=>['web'],
    'namespace' => 'App\Modules\AdminWarehouse\Controllers\Auth'], function() {

    // Authentication Routes...
    Route::get('login', 'WarehouseLoginController@showAdminLoginForm')->name('login');
    Route::post('login', 'WarehouseLoginController@login')->name('login.process')->middleware('throttle:3,1');

    Route::group(['middleware' => ['web','warehouse.auth','isWarehouseUser']], function () {
        Route::post('logout', 'WarehouseLoginController@logout')->name('logout');


    });

    //Forgot Password AdminWarehouse
    Route::get('forgot-password', 'WarehouseForgotPasswordController@showForgotPasswordPage')->name('forgot.password');
    Route::post('send-reset-email', 'WarehouseForgotPasswordController@sendResetLinkEmail')->name('send.reset.email');

    //Reset Password AdminWarehouse Page
    Route::get('password/reset/{token}', 'WarehousePasswordResetController@showResetForm')->name('reset.password');
    Route::post('password/reset', 'WarehousePasswordResetController@reset')->name('update.reset.password');

});

Route::group([
    'module'=>'AdminWarehouse',
    'prefix' => 'warehouse',
    'as' => 'warehouse.',
    'namespace' => 'App\Modules\AdminWarehouse\Controllers\profile',
    'middleware' => ['web','warehouse.auth','isWarehouseUser']
], function () {
    //change password routes
    Route::get('/password', 'SupportAdminProfileController@changePassword')->name('changePassword');
    Route::put('/password', 'SupportAdminProfileController@updatePassword')->name('updatePassword');
});

Route::group([
    'prefix' => 'warehouse',
    'as' => 'warehouse.',
    'namespace' => 'App\Modules\AdminWarehouse\Controllers\notifications',
    'middleware' => ['web', 'warehouse.auth','isWarehouseUser']
], function () {
    //change password routes
    Route::get('/notifications', 'NotificationController@index')->name('notifications.index');
});
