<?php
Route::group([
    'module'=>'Bank',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Bank\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::resource('banks', 'BankController');
    
});

Route::group([
    'module'=>'Bank',
    'prefix'=>'api',
    'namespace' => 'App\Modules\Bank\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn']
], function() {

    Route::get('banks', 'BankController@index');
    
});


