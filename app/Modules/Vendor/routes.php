<?php
Route::group([
    'module'=>'Vendor',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Vendor\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::resource('vendors', 'VendorController');
    Route::get('/vendor/change-status/{vendorCode}/{status}', 'VendorController@changeStatus')
        ->name('vendor.toggle-status');
    //Vendor Banner Routes
    Route::get('vendors/{vendor}/banners', 'VendorBannerController@create')->name('vendors.banners.create');
    Route::post('vendors/{vendor}/banners', 'VendorBannerController@store')->name('vendors.banners.store');
    Route::delete('vendors/{vendor}/banners/{banner}', 'VendorBannerController@destroy')->name('vendors.banners.destroy');
    Route::get('vendors/{vendor}/banners/{banner}/change-status', 'VendorBannerController@changeStatus')->name('vendors.banners.change-status');
    //Vendor Banner Routes End

    //Vendor Document Routes
    Route::get('vendors/{vendor}/documents', 'VendorDocumentController@create')->name('vendors.documents.create');
    Route::post('vendors/{vendor}/documents', 'VendorDocumentController@store')->name('vendors.documents.store');
    Route::delete('vendors/{vendor}/documents/{banner}', 'VendorDocumentController@destroy')->name('vendors.documents.destroy');
    //Vendor Document Routes End

    //Vendor Product Routes
    Route::get('vendors/{vendor}/products', 'VendorProductController@index');


    //vendor targets Route
    Route::get('vendor-targets', 'VendorTargetController@index')->name('vendorTarget.index');
    Route::get('vendor-targets/get-location/province','VendorTargetController@getAllProvince')->name('vendorTarget.get-province');
    Route::get('vendor-targets/get-location/district','VendorTargetController@getAllDistrict')->name('vendorTarget.get-district');
    Route::get('vendor-targets/get-location/municipality','VendorTargetController@getAllMunicipality')->name('vendorTarget.get-muncilipality');
    Route::get('vendor-targets/get-location/ward','VendorTargetController@getAllWard')->name('vendorTarget.get-ward');
    //Route::get('vendor-target/get-location','VendorTargetController@getAllLocation')->name('vendorTarget.get-location');
    Route::get('vendor-targets/change-isActive-status','VendorTargetController@changeIsActiveStatus')->name('vendorTarget.changeStaus');
    Route::get('vendor-targets/change-VTM-status','VendorTargetController@changeVTMStatus')->name('vendorTarget.change-VTM-status');
    Route::get('vendor-targets/vendor-target-incentative/show/{VTMcode}','VendorTargetController@showTargetIncentative')->name('vendor-target-incentative.show');

    //vendor complete detail
    Route::get('vendor/{vendorCode}/complete-detail', 'VendorCompleteDetailController@getVendorCompleteDetail')->name('vendor.complete.detail');
    Route::get('vendor/{vendorCode}/general-detail', 'VendorCompleteDetailController@getVendorGeneralDetail')->name('vendor.general.detail');
    Route::get('vendor/{vendorCode}/vendor-product', 'VendorCompleteDetailController@getAllVendorProducts')->name('vendor.products');
    Route::get('vendor/{vendorCode}/vendor-product/toggle-status', 'VendorCompleteDetailController@toggleVendorProductStatus')->name('vendor.products.toggle-status');


});


