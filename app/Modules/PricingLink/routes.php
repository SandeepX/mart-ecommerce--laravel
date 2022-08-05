<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'PricingLink',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\PricingLink\Controllers\Web',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {
    Route::resource('pricing-master','PricingMasterController');
    Route::get('pricing-master/toggle-status/{pricing_master_code}','PricingMasterController@changePricingLinkStatus')
        ->name('pricing-master.toggle-status');

});
Route::group([
    'module'=>'PricingLink',
    'namespace' => 'App\Modules\PricingLink\Controllers\Web',
    'middleware' => ['web']
], function() {
    Route::get('product-pricing-request-form/{link}','ProductPricingController@form')
        ->name('product-pricing.form');
    Route::post('product-pricing-request-form-store','ProductPricingController@store')
        ->name('product-pricing-form.store');
    Route::get('product-pricing-exception','ProductPricingController@exceptionPage')
        ->name('product-pricing.exception');
    Route::get('product-pricing-otp-form/{linkCode}/{mobileNumber}','ProductPricingController@otpVerifyForm')
        ->name('product-pricing.otpVerifyForm');
    Route::post('product-pricing-otp-form','ProductPricingController@verifyOTPWithoutAuth')
        ->name('product-pricing.verifyOTPWithoutAuth');
});


Route::group([
    'module' => 'PricingLink',
    'namespace' => 'App\Modules\PricingLink\Controllers\Web',
    'middleware' => ['web','IsSessionExists']
    //IsSessionExists
], function () {
    Route::get('product-pricing/{linkCode}', 'ProductPricingController@index')
        ->name('product-pricing.index');
});


Route::group([
    'module'=>'PricingLink',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\PricingLink\Controllers\Web',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {
    Route::get('pricing-link-lead','LeadController@index')->name('pricing-link-lead.index');
    Route::get('pricing-link-lead/export-excell','LeadController@exportExcellPricingLinkLead')
        ->name('pricing-link-lead.exportExcellPricingLinkLead');
});



