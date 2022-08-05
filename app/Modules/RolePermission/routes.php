<?php


Route::group([
    'module'=>'RolePermission',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\RolePermission\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::resource('roles', 'RoleController');

    Route::get('roles-filter','RoleController@rolesFilter')->name('roles.filter');
});
