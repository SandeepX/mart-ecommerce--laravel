<?php

Route::group([
    'module' => 'Package',
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
  ], function () {
    Route::group([
        'namespace' => 'App\Modules\Home\Controllers\Web\Admin\Slider'
      ], function () {
        Route::resource('sliders', 'SliderController');
      });
  });
  