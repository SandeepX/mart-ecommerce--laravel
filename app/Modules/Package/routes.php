<?php

Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'Package',
        'prefix' => 'admin',
        'namespace' => 'App\Modules\Package\Controllers\Api\Admin',
        'middleware' => 'auth:api'
    ], function () {
        Route::apiResource('/package-types', 'PackageTypeController');
    });

    Route::group([
        'module' => 'Package',
        'namespace' => 'App\Modules\Package\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::apiResource('/package-types', 'PackageTypeController');
    });

});


Route::group([
    'module' => 'Package',
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['web', 'admin.auth', 'isAdmin', 'ipAccess'],
    'namespace' => 'App\Modules\Package\Controllers\Web\Admin'
], function () {
    Route::resource('/package-types', 'PackageTypeController');
});
