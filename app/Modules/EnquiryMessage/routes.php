<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'EnquiryMessage',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\EnquiryMessage\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::get('enquiry-messages', 'EnquiryMessageController@index')->name('enquiry-messages.index');
    Route::get('enquiry-messages/sent', 'EnquiryMessageController@sentAdminMessages')->name('enquiry-messages.sent');
    Route::get('enquiry-messages/reply/{id}', 'EnquiryMessageController@reply')->name('enquiry-messages.adminreply');
    Route::post('enquiry-messages/reply', 'EnquiryMessageController@storeAdminReplyMessage')->name('enquiry-messages.reply');
    Route::get('enquiry-messages/{id}/show', 'EnquiryMessageController@show')->name('enquiry-messages.show');

});
