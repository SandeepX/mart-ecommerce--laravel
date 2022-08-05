<?php


Route::group([
    'module'=>'ContactMessage',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\ContactMessage\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('contact-messages', 'ContactMessageController@index')->name('contact-messages.index');
    Route::get('contact-messages/{id}/show', 'ContactMessageController@show')->name('contact-messages.show');

});