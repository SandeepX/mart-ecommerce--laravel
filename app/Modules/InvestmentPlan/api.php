<?php

use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'InvestmentPlan',
    'prefix'=>'api',
    'namespace' => 'App\Modules\InvestmentPlan\Controllers\Api',
    'middleware' => ['isMaintenanceModeOn']
], function() {
    Route::get('investment-plan', 'InvestmentPlanController@getAllActiveInvestmentPlans');
    Route::get('investment-plan/details/{IPCode}', 'InvestmentPlanController@getActiveInvestmentPlanDetail');//->middleware('auth:api');

});

Route::group([
    'module'=>'InvestmentPlan',
    'prefix'=>'api',
    'namespace' => 'App\Modules\InvestmentPlan\Controllers\Api',
    'middleware' => ['isMaintenanceModeOn','auth:api']
], function() {
    Route::get('investment-plan/referred-investment', 'InvestmentPlanSubscriptionController@getAllReferredInvestmentSubscribed')->middleware('isSalesManageUser');
    Route::post('investment-plan/online-pay/subscribe', 'InvestmentPlanSubscriptionController@createSubscription')->middleware('checkScope:manage-all');
    Route::post('investment-plan/offline-pay/subscribe', 'InvestmentPlanSubscriptionController@createSubscriptionByPayingOffline')->middleware('checkScope:manage-all');
    Route::get('investment-plan/my-subscription', 'InvestmentPlanSubscriptionController@getAllSubscribedInvestmentPlanByUser');
   # Route::get('investment-plan/connect-ips/validate-payment/{transactionId}', 'ConnectIPSApiController@validatePayment');

});

Route::group([
    'module'=>'InvestmentPlan',
    'prefix'=>'api',
    'namespace' => 'App\Modules\InvestmentPlan\Controllers\Api',
    'middleware' => ['isMaintenanceModeOn','auth:api']
], function() {
    Route::get('investment-plan/calculator/{IPCode}/{investedAmount}', 'InvestmentPlanCalculator@investmentReturnCalculation');
});




