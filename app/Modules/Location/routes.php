<?php

Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'Location',
        'prefix' => 'admin',
        'namespace' => 'App\Modules\Location\Controllers\Api\Admin'
    ], function () {
        Route::apiResource('/location-hierarchies', 'LocationHierarchyController');
    });
});


Route::group([
    'module' => 'Location',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Location\Controllers\Web\Admin',
    'middleware' => ['web', 'admin.auth','isAdmin','ipAccess']

], function () {
    // Route::post('/location-hierarchies/toles', 'LocationHierarchyController@tolesByWard')->name('location-hierarchies.toles');
  //  Route::resource('/location-hierarchies', 'LocationHierarchyController', ['only' => [ 'index' ]]);
    Route::resource('/location-hierarchies', 'LocationHierarchyController');
});


//Front End Routes
Route::group([
    'module' => 'Location',
    'prefix' => 'api/location-hierarchies',
    'namespace' => 'App\Modules\Location\Controllers\Api\Front',
   // 'middleware' => ['isMaintenanceModeOn']
], function () {

    Route::get('/by-type', 'LocationHierarchyController@getAllLocationsByType');
    Route::get('/by-code', 'LocationHierarchyController@getLocationByCode');
    Route::get('/{location_hierarchy}/location-path', 'LocationHierarchyController@getLocationPath');
    Route::get('/{location_hierarchy}/lower-locations', 'LocationHierarchyController@getLowerLocations');
    Route::get('/{location_hierarchy}', 'LocationHierarchyController@getLocationById');
    Route::get('/{location_hierarchy}/upper-location', 'LocationHierarchyController@getUpperLocation');
});

//location blacklisted
Route::group([
    'module' => 'Location',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Location\Controllers\Web\Admin',
    'middleware' => ['web', 'admin.auth','isAdmin','ipAccess']

], function () {
    Route::resource('/location-blacklisted', 'LocationBlacklistedController');
    Route::get('/location-blacklisted/toggle-status/{BLHCode}', 'LocationBlacklistedController@toggleStatus')->name('blacklisted-location.toggle-status');
});
