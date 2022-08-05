<?php

use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'Impersonate',
    'prefix'=>'api',
    'namespace' => 'App\Modules\Impersonate\Controllers\Admin\Api',
    'middleware' => ['isMaintenanceModeOn']
], function() {
    Route::post('impersonate/oauth', 'ImpersonateController@checkUUID');
});
