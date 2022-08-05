<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'api', 'module' => 'SalesManager'], function () {
    Route::group([
        'namespace' => 'App\Modules\SalesManager\Controllers\Api\Front\Auth',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('sales-manager/registration/data', 'SalesManagerRegisterController@getDataRequiredForManagerRegistration');
        Route::post('sales-manager-registration', 'SalesManagerRegisterController@storeSalesManagerUserFromApi');
        Route::post('sales-manager-login', 'SalesManagerAuthenticationApiController@loginSalesManager');
    });

    Route::group([
        'namespace' => 'App\Modules\SalesManager\Controllers\Api\Front\Auth',
        'middleware' => ['isMaintenanceModeOn','auth:api','isSalesManageUser']
    ] , function () {
        Route::get('sales-manager/status', 'SalesManagerRegisterController@findSalesManagerAccountStatus');
    });

    Route::group([
        'namespace' => 'App\Modules\SalesManager\Controllers\Api',
        'middleware' => ['isMaintenanceModeOn','auth:api','isSalesManageUser']
    ] , function () {
        Route::get('sales-manager/dashboard', 'SalesManagerController@getAllVendorTargetForSalesManager');
        Route::post('sales-manager/profile/update', 'ManagerProfileController@updateManagerProfile');
        Route::get('sales-manager/getAllIncentaive/{VTMcode}', 'SalesManagerController@getAllVendorTargetIncentativeByVTMcode');
        Route::get('manager/referred-stores', 'SalesManagerController@getStoreByReferralCode');
        Route::get('manager/referred-stores/{storeCode}/status', 'SalesManagerController@getStoreStatusByStoreCode');


        Route::get('manager/detail', 'SalesManagerDetailController@getManagerDetail');

        Route::get('manager/referred-managers', 'SalesManagerController@getReferredManagersByReferralCode');

        Route::get('manager/referred-users/lists','SalesManagerController@getManagersAllReferralsList');

        //manager otp verification for phone and email
        Route::post('manager/generate/phone/otp','ManagerProfileController@generatePhoneVerificationOTP');
        Route::post('manager/generate/email/otp','ManagerProfileController@generateEmailVerificationOTP');
        Route::post('manager/verify/phone/otp','ManagerProfileController@verifyPhoneOTP');
        Route::post('manager/verify/email/otp','ManagerProfileController@verifyEmailOTP');

        //update email and phone no of manager
        Route::post('sales-manager/update/email','ManagerProfileController@updateManagerEmail');
        Route::post('sales-manager/update/phone','ManagerProfileController@updateManagerPhone');

    });


    Route::group([
        'namespace' => 'App\Modules\SalesManager\Controllers\Api\Front\SalesManagerTransaction',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isSalesManageUser']
    ] , function () {

        Route::get('sales-manager/wallet/transaction','SalesManagerBalanceApiController@getAllTransactions');
        Route::get('sales-manager/transaction-filter/data', 'SalesManagerBalanceApiController@getDataForManagerTransactionFilter');

    });
    //manager investment detail route
    Route::group([
        'namespace' => 'App\Modules\SalesManager\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn','auth:api','isSalesManageUser']
    ],function () {
       Route::get('sales-manager/investment-plan/detail/{IPCode}', 'ManagerInvestmentPlanController@getActiveInvestmentPlanDetail');
       Route::post('sales-manager/investment-plan/subscribe/{IPIRCode}', 'ManagerInvestmentPlanController@storeInvestmentPlanSubscription');

    });

    //smi-manager settings
    Route::group([
        'namespace' => 'App\Modules\SalesManager\Controllers\Api',
        'middleware' => ['isMaintenanceModeOn']
    ],function () {
        Route::get('manager-smi-settings', 'ManagerSMISettingController@getLatestManagerSMISetting');
    });

    // smi-manager route
    Route::group([
        'namespace' => 'App\Modules\SalesManager\Controllers\Api',
        'middleware' => ['isMaintenanceModeOn','auth:api','isSalesManageUser']
    ],function () {
        Route::post('manager-smi/store', 'ManagerSMIController@store');
        Route::get('manager-smi/show-detail','ManagerSMIController@show');
        Route::post('manager-smi/update', 'ManagerSMIController@update');
    });

    //smi manager Attendance
    Route::group([
        'namespace' => 'App\Modules\SalesManager\Controllers\Api',
        'middleware' => ['isMaintenanceModeOn','auth:api','isSalesManageUser']
    ],function () {
        Route::get('manager-smi/attendance-detail','SMIManagerAttendanceController@showSMIManagerAttendanceDetail');
    });

    //social media
    Route::group([
        'namespace' => 'App\Modules\SalesManager\Controllers\Api',
        'middleware' => ['isMaintenanceModeOn']
    ],function () {
        Route::get('social-media', 'SocialMediaController@getAllEnabledSocialMedia');
    });


});

