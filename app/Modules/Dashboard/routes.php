<?php


Route::group([
    'module'=>'Dashboard',
    'namespace' => 'App\Modules\Dashboard\Controllers',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
],
    function() {

 Route::get('admin/dashboard', 'DashboardController@index')->name('admin.dashboard');

});

Route::group([
    'module'=>'Dashboard',
    'namespace' => 'App\Modules\Dashboard\Controllers\Warehouse',
    'middleware' => ['web','warehouse.auth','isWarehouseUser']
],
    function() {

        Route::get('warehouse/dashboard', 'WarehouseDashboardController@index')->name('warehouse.dashboard');

    });

Route::group([
    'module'=>'Dashboard',
    'namespace' => 'App\Modules\Dashboard\Controllers\SupportAdmin',
    'middleware' => ['web','supportAdmin.auth','isSupportAdmin']
],
    function() {

        Route::get('support-admin/dashboard', 'AdminSupportDashboardController@index')->name('support-admin.dashboard');

    });
