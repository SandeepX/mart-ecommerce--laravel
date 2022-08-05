<?php
Route::group([
    'module'=>'PaymentGateway',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\PaymentGateway\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','ipAccess']
], function() {

    /** connect ips routes */

//    Route::get('connect-ips/payment-lists','ConnectIPSController@paymentLists')->name('connect-ips.payment-lists');
//    Route::get('connect-ips/payment-form','ConnectIPSController@paymentForm')->name('connect-ips.payment-form');
//    Route::post('connect-ips/payment-store','ConnectIPSController@paymentStore')->name('connect-ips.payment-store');
//    Route::get('connect-ips/success','ConnectIPSController@paymentSuccess')->name('connect-ips.success');
//    Route::get('connect-ips/error','ConnectIPSController@paymentError')->name('connect-ips.error');

    /** ends here */
    /** Online Payment Logs */
       Route::get('online-payments/lists','OnlinePaymentLogController@paymentLists')->name('online-payments.lists');
       Route::get('online-payment/{storeCode}/{transactionId}','OnlinePaymentLogController@reValidateIpsPayment')->name('online-payments.reverify.connect-ips');
       Route::get('online-payment/{payment_holder_code}/{payment_for}/lists','OnlinePaymentLogController@getOnlinePaymentsLists')->name('online-payments.payment-holder-type.payment-for.lists');

    /** ends here */

});







