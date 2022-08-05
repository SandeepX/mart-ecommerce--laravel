<?php

Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'Variants',
        'prefix' => 'admin',
        'namespace' => 'App\Modules\Variants\Controllers\Api\Admin'
    ], function () {
        Route::apiResource('variants', 'VariantController');
    });

    Route::group([
        'module' => 'Variants',
        'namespace' => 'App\Modules\Variants\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('variants', 'VariantController@index');
    });

});

Route::group([
    'module' => 'Variants',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Variants\Controllers\Web\Admin',
    'middleware' => ['web', 'admin.auth', 'isAdmin', 'ipAccess']
], function () {

    Route::resource('variants', 'VariantController');
    Route::post('store/variant-values/variant/{variantID}', 'VariantValueController@store')->name('variant-values.store');
    Route::put('update/variant-values/{variantValueCode}', 'VariantValueController@update')->name('variant-values.update');
    Route::delete('delete/variant-values/{variantValueCode}', 'VariantValueController@destroy')->name('variant-values.destroy');

});


