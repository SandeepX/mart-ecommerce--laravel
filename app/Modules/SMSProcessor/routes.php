<?php
use Illuminate\Support\Facades\Route;

    Route::group([
        'module'=>'SMSProcessor',
        'prefix'=>'admin',
        'as'=>'admin.',
        'namespace' => 'App\Modules\SMSProcessor\Controllers\Web',
        'middleware' => ['web','admin.auth','isAdmin','ipAccess']
    ], function() {

        Route::get('sms-log','SmsController@index')->name('sms.index');

    });

