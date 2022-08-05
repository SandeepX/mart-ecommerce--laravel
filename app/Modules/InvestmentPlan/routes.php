<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'InvestmentPlan',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\InvestmentPlan\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {
    //Investment Plan Route
    Route::resource('investment','InvestmentPlanController');
    Route::get('investment/toggle-status/{IPCode}','InvestmentPlanController@changeInvestmentStatus')->name('investment.toggle-status');
    Route::post('investment-plans/change-display-order', 'InvestmentPlanController@changeInvestmentPlanDisplayOrder')
        ->name('investment-plans.change-display-order');
    //Investment Interest Release Route
    Route::get('investment-plan/interest-release/{IPCode}','InvestmentPlanInterestReleaseController@show')->name('investment-interest-release');
    Route::get('investment-plan/interest-release/create/{IPCode}','InvestmentPlanInterestReleaseController@create')->name('investment-interest-release.create');
    Route::post('investment-plan/interest-release/store','InvestmentPlanInterestReleaseController@store')->name('investment-interest-release.store');
    Route::get('investment-plan/interest-release/edit/{IPIRCode}','InvestmentPlanInterestReleaseController@edit')->name('investment-interest-release-option.edit');
    Route::put('investment-plan/interest-release/update/{IPIRCode}','InvestmentPlanInterestReleaseController@update')->name('investment-interest-release.update');
    Route::get('investment-plan/interest-release/toggle-status/{IPIRCode}','InvestmentPlanInterestReleaseController@changeStatus')->name('investment-interest-release.toggle-status');

    //Inventment Plan Commssion Route
    Route::get('investment-plan/investment-commission/{IPCode}','InvestmentPlanCommissionController@show')->name('investment-commission.show');
    Route::get('investment-plan/investment-commission/create/{IPCode}','InvestmentPlanCommissionController@create')->name('investment-commission.create');
    Route::post('investment-plan/investment-commission/store','InvestmentPlanCommissionController@store')->name('investment-commission.store');
    Route::get('investment-plan/interest-commission/edit/{IPCCode}','InvestmentPlanCommissionController@edit')->name('investment-commission.edit');
    Route::put('investment-plan/interest-commission/update/{IPCCode}','InvestmentPlanCommissionController@update')->name('investment-commission.update');
    Route::get('investment-plan/interest-commission/toggle-status/{IPCCode}','InvestmentPlanCommissionController@changeInvestmentCommissionStatus')->name('investment-commission.toggle-status');



    //investment plan subscription
    Route::get('investment-plan/subscription','InvestmentPlanSubscriptionController@index')->name('investment-subscription.index');
    Route::get('investment-plan/subscription/detail/{IPCode}','InvestmentPlanSubscriptionController@detailSubscription')->name('investment-subscription.detail-show');
    Route::get('investment-plan/subscription/{ISC}','InvestmentPlanSubscriptionController@show')->name('investment-subscription.show');
    Route::get('investment-plan/subscription/{ISC}/respond-form','InvestmentPlanSubscriptionController@respondISForm')->name('investment-subscription.respondIS.form');
    Route::post('investment-plan/subscription/{ISC}','InvestmentPlanSubscriptionController@respondIS')->name('investment-subscription.respondIS');
    Route::get('investment-plan/subscription/toggle-status/{ISCode}','InvestmentPlanSubscriptionController@toggleStatus')->name('investment-subscription.toggle-status');

    //investment plan Types
    Route::get('investment-plan/types','InvestmentPlanTypeController@index')->name('investment-type.index');
//    Route::get('investment-plan/types/create','InvestmentPlanTypeController@create')->name('investment-type.create');
//    Route::post('investment-plan/types/store','InvestmentPlanTypeController@store')->name('investment-type.store');
    Route::get('investment-plan/types/show/{IPTCode}','InvestmentPlanTypeController@show')->name('investment-type.show');
//    Route::get('investment-plan/types/edit/{IPTCode}','InvestmentPlanTypeController@edit')->name('investment-type.edit');
//    Route::put('investment-plan/types/update/{IPTCode}','InvestmentPlanTypeController@update')->name('investment-type.update');
    Route::get('investment-plan/types/toggle-status/{IPTCode}','InvestmentPlanTypeController@changeInvestmentTypeStatus')->name('investment-type.toggle-status');


});





