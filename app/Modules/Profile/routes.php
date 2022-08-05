<?php

Route::group([
    'module'=>'Admin',
    'prefix'=>'admin',
    'as'=>'admin.',
    'middleware'=>['web','admin.auth','isAdmin','ipAccess'],
    'namespace' => 'App\Modules\Profile\Controllers\Web\Admin'], function() {

    // Admin Profile Routes...
    Route::get('profile', 'AdminProfileController@showAdminProfilePage')->name('profile.show');


});
