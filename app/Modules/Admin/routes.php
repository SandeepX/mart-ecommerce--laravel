<?php

Route::group([
    'module'=>'Admin',
    'prefix'=>'admin',
    'as'=>'admin.',
    'middleware'=>['web'],
    'namespace' => 'App\Modules\Admin\Controllers'], function() {

    // Authentication Routes...
    Route::get('login', 'AdminLoginController@showAdminLoginForm')->name('login');
    Route::post('login', 'AdminLoginController@login')->name('login.process')->middleware('throttle:3,1');

    Route::group(['middleware' => ['admin.auth','isAdmin','ipAccess']], function () {
        Route::post('logout', 'AdminLoginController@logout')->name('logout');
    });

    //Forgot Password Admin
    Route::get('forgot-password', 'AdminForgotPasswordController@showForgotPasswordPage')->name('forgot.password');
    Route::post('send-reset-email', 'AdminForgotPasswordController@sendResetLinkEmail')->name('send.reset.email');

    //Reset Password Admin Page
    Route::get('password/reset/{token}', 'AdminPasswordResetController@showResetForm')->name('reset.password');
    Route::post('password/reset', 'AdminPasswordResetController@reset')->name('update.reset.password');

});
