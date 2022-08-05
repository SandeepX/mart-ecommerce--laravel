<?php


Route::group([
    'module'=>'Brand',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Brand\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::resource('brands', 'BrandController');
    Route::get('brand-sliders/{brandCode}','BrandSliderController@index')->name('brand-sliders.index');
    Route::get('brand-sliders/{brandCode}/create','BrandSliderController@create')->name('brand-sliders.create');
    Route::get('brand-sliders/{brandCode}/{brandSliderCode}','BrandSliderController@show')->name('brand-sliders.show');
    Route::get('brand-sliders/{brandCode}/{brandSliderCode}/edit','BrandSliderController@edit')->name('brand-sliders.edit');

    //    Route::put('brand-sliders/{brandCode}/{brandSliderCode}/update','BrandSliderController@update')->name('brand-sliders.update');

    Route::resource('brand-sliders','BrandSliderController',['except'=>['create','index']]);

});



