<?php


Route::group([
    'module'=>'Newsletter',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Newsletter\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('subscribers', 'AdminSubscriberController@getSubscribers')->name('subscribers.index');
    Route::get('subscribers/toggle-status/{code}', 'AdminSubscriberController@toggleStatus')->name('subscribers.toggleStatus');
    Route::delete('subscribers/destroy/{code}', 'AdminSubscriberController@destroy')->name('subscribers.destroy');

});
