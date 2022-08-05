<?php

Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'Types',
        'namespace' => 'App\Modules\Types\Controllers\Api\Frontend',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('cancellation-params', 'CancellationParamController@index');
        Route::get('/store-types', 'StoreTypeApiController@index');
    });
});
