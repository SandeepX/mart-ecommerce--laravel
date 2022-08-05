<?php

Route::group([
    'module' => 'User'
], function () {


    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'App\Modules\User\Controllers\Web\Admin',
        'middleware' => ['web', 'admin.auth','isAdmin','ipAccess']
    ], function () {
        Route::resource('users', 'UserController');
        Route::get('vendor-users/{vendorCode}/update-vendor-admin-password', 'VendorUserController@editVendorAdminPassword')->name('vendor-password.edit');
        Route::put('vendor-users/{userCode}/update-vendor-admin-password', 'VendorUserController@updateVendorAdminPassword')->name('vendor-password.update');
        Route::resource('vendor-users', 'VendorUserController', ['only' => [ 'index']]);

        Route::get('store-users/{storeCode}/update-store-admin-password', 'TempStoreUserController@editStoreAdminPassword')->name('store-password.edit');
        Route::put('store-users/{userCode}/update-store-admin-password', 'TempStoreUserController@updateStoreAdminPassword')->name('store-password.update');

//        warehouse password change
        Route::get('warehouse-users/{warehouseCode}/update-warehouse-admin-password', 'WarehouseUserController@editWarehouseAdminPassword')->name('warehouse-password.edit');
        Route::put('warehouse-users/{userCode}/update-warehouse-admin-password', 'WarehouseUserController@updateWarehouseAdminPassword')->name('warehouse-password.update');

//        admin password change
        Route::get('admin-password/change/{userCode}', 'UserController@editAdminPassword')->name('admin-password.edit');
        Route::put('admin-password/change/{userCode}', 'UserController@updateAdminPassword')->name('admin-password.update');
       // Route::get('user-role', 'UserController@saveUserRole');

        // user Account Log Routes Starts Here
        Route::post('user-account-log/{userCode}/suspend','UserAccountLogController@storeSuspendUserDetail')->name('user-account-log.suspend');
        Route::get('user-account-log/{userCode}/unSuspend','UserAccountLogController@unSuspendUser')->name('user-account-log.unSuspendUser');
        Route::post('user-account-log/{userCode}/banned','UserAccountLogController@storeBannedUserDetail')->name('user-account-log.banned');
        Route::get('user-account-log/{userCode}/unBanned','UserAccountLogController@unBanUser')->name('user-account-log.unBannedUser');

        Route::get('user-account-log/{userCode}','UserAccountLogController@getUserAccountLogByUserCode')->name('user-account-logs');
        Route::get('user-account-log/{userCode}/toggle-active','UserController@toggleActiveStatus')->name('user-account-log.toggleActive');


    });

    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'App\Modules\User\Controllers\Web\Admin\Profile',
        'middleware' => ['web', 'admin.auth','isAdmin','ipAccess']
    ], function () {
        //change password routes
        Route::get('/password', 'ProfileController@changePassword')->name('changePassword');
        Route::put('/password', 'ProfileController@updatePassword')->name('updatePassword');
    });

    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'App\Modules\User\Controllers\Web\Admin',
        'middleware' => ['web', 'admin.auth','isAdmin','ipAccess']
    ], function () {
        //change password routes
        Route::get('/notifications', 'NotificationController@index')->name('notifications.index');
    });



    Route::group([
        'prefix' => 'warehouse',
        'as' => 'warehouse.',
        'namespace' => 'App\Modules\User\Controllers\Web\Warehouse',
        'middleware' => ['web','warehouse.auth','isWarehouseUser']
    ], function () {
        Route::get('warehouse-users/{userCode}/toggleStatus', 'WarehouseUserController@toggleUserStatus')->name('warehouse-users.toggle-status');
        Route::resource('warehouse-users', 'WarehouseUserController');
    });




});
