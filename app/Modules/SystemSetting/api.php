<?php

Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'SystemSetting',
        'namespace' => 'App\Modules\SystemSetting\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('site/general-settings', 'GeneralSettingController@getGeneralSiteSettings');
        Route::get('site/maintenance-mode', 'GeneralSettingController@getMaintenanceModeStatus');

        Route::get('app/latest/versions','MobileAppDeploymentVersionApiController@getMobileAppDeploymentVersion');


    });
});
