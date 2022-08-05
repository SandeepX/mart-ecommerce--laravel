<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/store', 'module' => 'EnquiryMessage'], function () {
    Route::group([
        'namespace' => 'App\Modules\EnquiryMessage\Controllers\Api\Front',
        'middleware' => ['auth:api','isMaintenanceModeOn']
    ], function () {

        Route::post('enquiry-mailbox/compose', 'EnquiryMessageFrontController@storeEnquiryMessage')->middleware('checkScope:manage-all');
        Route::get('enquiry-mailbox/inbox-messages', 'EnquiryMessageFrontController@getInboxMessages');
        Route::get('enquiry-mailbox/sent-messages', 'EnquiryMessageFrontController@getSentMessages');
        Route::post('enquiry-mailbox/inbox-reply/{parent_id}', 'EnquiryMessageFrontController@storeEnquiryMessageReply')->middleware('checkScope:manage-all');
        Route::get('replied-messages/{store_message_code}', 'EnquiryMessageFrontController@getRepliedMessages');
        Route::get('search-mailbox', 'EnquiryMessageFrontController@searchMailbox');
        Route::get('search-mailbox-sent-message', 'EnquiryMessageFrontController@searchMailboxSentMessage');
        Route::post('enquiry-mailbox/update-navbar-seen/inbox-messages/{storeMessageCode}', 'EnquiryMessageFrontController@updateInboxMessageSeen')->middleware('checkScope:manage-all');
        Route::get('enquiry-mailbox/parent-message/{parentId}', 'EnquiryMessageFrontController@getParentMessage');
        Route::get('enquiry-mailbox/sent-message/{storeMessageCode}', 'EnquiryMessageFrontController@getSentMessageDetail');
    });
});

