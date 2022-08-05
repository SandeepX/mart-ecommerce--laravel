<?php

Route::group([
  'module' => 'Types',
  'prefix' => 'admin',
  'as' => 'admin.',
  'middleware' => ['web','admin.auth','isAdmin','ipAccess'],
  'namespace' => 'App\Modules\Types\Controllers\Web\Admin'
], function () {

  Route::resource('user-types', 'UserTypeController');
  Route::resource('vendor-types', 'VendorTypeController');
  Route::resource('company-types', 'CompanyTypeController');
  Route::resource('registration-types', 'RegistrationTypeController');

  Route::resource('category-types', 'CategoryTypeController');
  Route::resource('rejection-params', 'RejectionParamController');
  Route::resource('cancellation-params', 'CancellationParamController');
  Route::resource('store-sizes', 'StoreSizeController');

  Route::resource('store-types', 'StoreTypeController');
  Route::get('/store-types/toggle-status/{storeTypeCode}/{status}', 'StoreTypeController@toggleStatus')->name('store-types.toggle-status');

    Route::post('store-types/change-display-order/{storeTPCode}', 'StoreTypeController@changeStoreTypeDisplayOrder')
        ->name('store-types.change-display-order');
});

