<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'OfflinePayment',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\OfflinePayment\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {
    Route::resource('offline-payment','OfflinePaymentController');
    Route::post('offline-payment/repond/{SMPCode}','OfflinePaymentController@respondToOfflinePayment')->name('offline-payment.payment-verify');
    Route::get('offline-payment/{payment_holder_type}/{payment_for}/lists','OfflinePaymentController@getOfflinePaymentLists')->name('offline-payment.payment-holder-type.payment-for.lists');

    //miscellaneous payment remarks
    Route::get('offline-payment/{payment_code}/remarks/list', 'OfflinePaymentRemarkController@viewRemarksByOfflinePaymentCode')->name('offline-payment.remarks.list');
    Route::get('offline-payment/{payment_code}/remarks/create', 'OfflinePaymentRemarkController@createRemarks')->name('offline-payment.remarks.create');
    Route::post('offline-payment/{payment_code}/remarks/save', 'OfflinePaymentRemarkController@saveRemarks')->name('offline-payment.remarks.save');

});

