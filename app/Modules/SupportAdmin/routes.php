<?php

Route::group([
    'module'=>'SupportAdmin',
    'prefix'=>'support-admin',
    'as'=>'support-admin.',
    'middleware'=>['web'],
    'namespace' => 'App\Modules\SupportAdmin\Controllers\Auth'], function() {

    // Authentication Routes...
    Route::get('login', 'SupportAdminLoginController@showSupportAdminLoginForm')->name('login');
    Route::post('login', 'SupportAdminLoginController@login')->name('login.process')->middleware('throttle:3,1');

    Route::group(['middleware' => ['web','supportAdmin.auth','isSupportAdmin']], function () {
        Route::post('logout', 'SupportAdminLoginController@logout')->name('logout');
    });

    //Forgot Password SupportAdmin
    Route::get('forgot-password', 'SupportAdminForgotPasswordController@showForgotPasswordPage')->name('forgot.password');
    Route::post('send-reset-email', 'SupportAdminForgotPasswordController@sendResetLinkEmail')->name('send.reset.email');

    //Reset Password SupportAdmin Page
    Route::get('password/reset/{token}', 'SupportAdminPasswordResetController@showResetForm')->name('reset.password');
    Route::post('password/reset', 'SupportAdminPasswordResetController@reset')->name('update.reset.password');


});

Route::group([
    'module'=>'SupportAdmin',
    'prefix' => 'support-admin',
    'as' => 'support-admin.',
    'namespace' => 'App\Modules\SupportAdmin\Controllers\profile',
    'middleware' => ['web','supportAdmin.auth','isSupportAdmin']
], function () {
    //change password routes
    Route::get('/password', 'SupportAdminProfileController@changePassword')->name('changePassword');
    Route::put('/password', 'SupportAdminProfileController@updatePassword')->name('updatePassword');
});

Route::group([
    'module'=>'SupportAdmin',
    'prefix' => 'support-admin',
    'as' => 'support-admin.',
    'namespace' => 'App\Modules\SupportAdmin\Controllers\notifications',
    'middleware' => ['web','supportAdmin.auth','isSupportAdmin']
], function () {
    //notification
    Route::get('/notifications', 'SupportAdminNotificationController@index')->name('notifications.index');

});

Route::group([
    'module'=>'SupportAdmin',
    'prefix' => 'support-admin',
    'as' => 'support-admin.',
    'namespace' => 'App\Modules\SupportAdmin\Controllers\stores',
    'middleware' => ['web','supportAdmin.auth','isSupportAdmin']
], function () {

    Route::get('/store/search-store', 'StoreDetailForSupportAdminController@showStoreSearchForm')->name('store.index');
    Route::post('/store-detail', 'StoreDetailForSupportAdminController@searchStore')->name('search-store');

    Route::group(['prefix'=>'store-detail'],function(){

        //store order for admin support
//        Route::get('/store-orders/{storeCode}', 'StoreOrderDetailForSupportAdminController@getStoreOrderForSupportAdmin')->name('store-order');
        Route::get('/store-order-detail/{storeOrderCode}', 'StoreOrderDetailForSupportAdminController@getStoreOrderDetailForSupportAdmin')->name('store-order-details');

        //store all order  and preorder for admin support
        Route::get('/store-orders/{storeCode}', 'StoreOrderCompleteDetailForAdminSupportController@getStoreOrderAndPreorderForSupportAdmin')->name('store-order');

        //store preorder for admin support
//        Route::get('/store-preorders/{storeCode}', 'StorePreorderDetailForSupportAdminController@getStorePreOrderForSupportAdmin')->name('store-preorder');
        Route::get('/store-preorder-detail/{storePreOrderCode}', 'StorePreorderDetailForSupportAdminController@getStoreOrderDetailForSupportAdmin')->name('store-preorder-detail');

        //store kyc detail
        Route::get('/store-individual-kyc/{storeCode}', 'StoreKycDetailForSupportAdminController@getIndividualKycDetail')->name('store-individual-kyc');
        Route::get('/store-individual-kyc/{kycCode}/show', 'StoreKycDetailForSupportAdminController@showDetailKyc')->name('stores-kyc.individuals.show');
        Route::get('/store-firm-kyc/{storeCode}', 'StoreKycDetailForSupportAdminController@getFirmsKycForAdminSupport')->name('store-firm-kyc');
        Route::get('/store-firm-kyc/{kycCode}/show', 'StoreKycDetailForSupportAdminController@showDetailFirmKyc')->name('stores-kyc.firm.show');

        //store withdraw detail
        Route::get('/store-withdraw/{storeCode}', 'StoreWithdrawDetailForSupportAdminController@getStoreWithdrawDetailForSupportAdmin')->name('store-withdraw');
        Route::get('/store-all-withdraw-requests/{storeCode}', 'StoreWithdrawDetailForSupportAdminController@getAllWithdrawRequestOfStore')->name('store-withdraw-requests');
        Route::get('/store-all-withdraw-request/{withdrawRequestCode}/show', 'StoreWithdrawDetailForSupportAdminController@showDetailOfWithdrawRequestOfStore')->name('store-withdraw-requests.show');

        //store payment detail
        Route::get('/store-payment/{storeCode}', 'StorePaymentForSupportAdminController@getStorePaymentForSupportAdmin')->name('store-payment');
        Route::get('/store-payment/{storeCode}/list/{paymentFor}', 'StorePaymentForSupportAdminController@getStorePaymentListForSupportAdmin')->name('store-payment.list');
        Route::get('/store-payment/detail/{miscPaymentCode}', 'StorePaymentForSupportAdminController@showStoreMiscPaymentForSupportAdmin')->name('store-payment.show');

        //store payment statement detail
        Route::get('/transaction-statements/{storeCode}', 'StoreTransactionStatementForSupportAdminController@getStoreTransactionStatements')->name('store-transaction-statement');
        Route::get('/transaction-statements/extra-remark/{transactionWalletCode}', 'StoreTransactionStatementForSupportAdminController@viewRemark')->name('transaction.extra-remark-view');

        //store investment
        Route::get('/investment-lists/{storeCode}', 'StoreInvestmentDetailForSupportAdminController@getStoreInvestment')->name('store-investment');
        Route::get('/investment-return-detail/{ISCode}', 'StoreInvestmentDetailForSupportAdminController@showDetail')->name('investment-return.show');





    });
});



