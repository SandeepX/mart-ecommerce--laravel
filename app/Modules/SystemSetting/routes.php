<?php

Route::group([
    'module'=>'SystemSetting',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SystemSetting\Controllers\Web\Admin\GeneralSetting',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('general-settings', 'GeneralSettingController@show')->name('general-settings.show');
    Route::post('general-settings', 'GeneralSettingController@store')->name('general-settings.store');
    Route::get('general-settings/edit', 'GeneralSettingController@edit')->name('general-settings.edit');
});

Route::group([
    'module'=>'SystemSetting',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SystemSetting\Controllers\Web\Admin\SeoSetting',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('seo-settings', 'SeoSettingController@show')->name('seo-settings.show');
    Route::post('seo-settings', 'SeoSettingController@store')->name('seo-settings.store');
    Route::get('seo-settings/edit', 'SeoSettingController@edit')->name('seo-settings.edit');
});

Route::group([
    'module'=>'SystemSetting',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SystemSetting\Controllers\Web\Admin\MailSetting',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('mail-settings', 'MailSettingController@edit')->name('mail-settings.edit');
    Route::post('mail-settings', 'MailSettingController@update')->name('mail-settings.update');
});

Route::group([
    'module'=>'SystemSetting',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SystemSetting\Controllers\Web\Admin\PassportSetting',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('passport-settings', 'PassportSettingController@edit')->name('passport-settings.edit');
    Route::post('passport-settings', 'PassportSettingController@update')->name('passport-settings.update');
});

Route::group([
    'module'=>'SystemSetting',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SystemSetting\Controllers\Web\Admin\SiteUrlSetting',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('url-settings', 'SiteUrlSettingController@edit')->name('url-settings.edit');
    Route::post('url-settings', 'SiteUrlSettingController@update')->name('url-settings.update');
});


Route::group([
    'module'=>'SystemSetting',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SystemSetting\Controllers\Web\Admin\IpAccess',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::resource('ip-access-settings', 'IpAccessSettingController');

});

Route::group([
    'module'=>'SystemSetting',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SystemSetting\Controllers\Web\Admin\StoreForceLogOut',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('force-logout-store', 'ForceStoreLogoutController@index')->name('force-logout-store.index');
    Route::post('force-logout-store/user-logout', 'ForceStoreLogoutController@forceStoreAllUsersLogout')->name('store-user-force-logout');

});

Route::group([
    'module'=>'SystemSetting',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\SystemSetting\Controllers\Web\Admin\MobileAppDeployment',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {
    Route::get('mobile-app-deployment-version', 'MobileAppDeploymentVersionController@show')->name('mobile-app-deployment-version.show');
    Route::post('mobile-app-deployment-version', 'MobileAppDeploymentVersionController@store')->name('mobile-app-deployment-version.store');
    Route::get('mobile-app-deployment-version/edit', 'MobileAppDeploymentVersionController@edit')->name('mobile-app-deployment-version.edit');
});




