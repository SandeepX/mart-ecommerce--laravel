<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function(){
    Route::group([
        'module' => 'Admin',
        'namespace' => 'App\Modules\Admin\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::post('/admin/login', 'AdminLoginApiController@loginAdmin');
    });

});



