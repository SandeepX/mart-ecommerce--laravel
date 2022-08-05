<?php

Route::group([
    'module'=>'Brand',
    'prefix'=>'api',
    'namespace' => 'App\Modules\Brand\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn']
], function() {

    Route::get('brands', 'BrandController@index');
    Route::get('featured-brands','BrandController@featuredBrand');
    Route::get('brand-sliders/{brandSlug}','BrandSliderController@index');
    Route::get('brand-detail/{brandSlug}','BrandController@brandDetails');


});
Route::group([
    'module'=>'Brand',
    'prefix'=>'api',
    'namespace' => 'App\Modules\Brand\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']
], function() {
    Route::get('brand-follow/{brandCode}','BrandFollowersByStoreController@createOrUpdateBrandFollow');
    Route::get('is-brand-follow/{brandCode}','BrandFollowersByStoreController@isBrandFollowed');
    Route::get('brand-follower/{brandCode}','BrandFollowersByStoreController@countBrandFollowerByStoreCode');
});
