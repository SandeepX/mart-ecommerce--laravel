<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'GlobalNotification',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\GlobalNotification\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::resource('notification','GlobalNotificationController');
    Route::get('global-notification/toggleStatus/{global_notification_code}','GlobalNotificationController@toggleStatus')->name('global-notification.toggle-status');

});

