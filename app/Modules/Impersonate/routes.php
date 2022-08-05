<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'Impersonate',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Impersonate\Controllers\Admin\Web',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {

    Route::get('impersonate/{storeCode}','ImpersonateController@impersonateStore')->name('impersonate');
});

