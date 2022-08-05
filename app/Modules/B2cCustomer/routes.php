<?php
use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'B2cCustomer',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\B2cCustomer\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {

    Route::get('b2c-user', 'B2CUserController@index')->name('b2c-user.index');
    Route::get('b2c-user/{userCode}/show', 'B2CUserController@show')->name('b2c-user.show');
    Route::put('b2c-user/{userCode}/change-status', 'B2CUserController@changeRegistartionStatus')->name('b2c-user.change-registration-status');

});

