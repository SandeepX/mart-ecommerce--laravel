<?php

Route::group([
    'module' => 'Home',
    'prefix' => 'api',
    'middleware' => ['isMaintenanceModeOn']
  ], function () {
    Route::group([
        'namespace' => 'App\Modules\Home\Controllers\Api\Front'
      ], function () {
        Route::get('sliders', 'HomeController@getAllActiveSliders');
      });
  });

