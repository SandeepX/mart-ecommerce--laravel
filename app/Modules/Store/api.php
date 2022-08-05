<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api', 'module' => 'Store'], function () {
    Route::group([
        //'middleware' => ['throttle:3,1'],// 5 attempts and block for 1 minute
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\Auth',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::post('store-login', 'StoreAuthenticationApiController@loginStore');
        //Route::get('login', 'StoreAuthenticationApiController@show');
    });

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']

    ], function () {
//        Route::group([
//            'middleware' => ['storeAccessBarrier']
//
//        ], function () {
//
//          Route::post('store/orders', 'StoreOrderController@store');
//
//        });
        Route::post('store/orders', 'StoreOrderController@store')->middleware('checkScope:manage-all');
        Route::get('store/orders/bill/{storeOrderCode}', 'StoreOrderController@generateStoreOrderBill')->middleware('checkScope:manage-all');
        Route::get('store/orders', 'StoreOrderController@index');
        Route::get('store/orders/{order}', 'StoreOrderController@show');
    });


    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\Kyc',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']

    ] , function () {
        Route::post('individual-kyc/store', 'IndividualKycApiController@storeIndividualKyc')->middleware('checkScope:manage-all');
        Route::get('individual-kyc/{kycFor}', 'IndividualKycApiController@getIndividualKyc');
        Route::post('firm-kyc/store', 'FirmKycApiController@storeFirmKyc')->middleware('checkScope:manage-all');
        Route::get('firm-kyc', 'FirmKycApiController@getFirmKyc');

       Route::get('generate/agreement-paper/akhtiyari', 'KycAgreementGenerationController@generateAkhtiyariAgreementPaper');
       Route::get('generate/agreement-paper/samjhauta', 'KycAgreementGenerationController@generateSamjhautaAgreementPaper');
       Route::get('kyc/agreement-video', 'KycAgreementVideoController@getKycAgreementVideo');
       Route::post('submit/agreement-video', 'KycAgreementVideoController@storeKycAgreementVideo')->middleware('checkScope:manage-all');

       Route::get('kyc/store/{storeCode}/bank-details-added', 'KycApiController@getBankDetailsAddedInKYC')->middleware('checkScope:manage-all');


    });

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\Payment',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']
    ] , function () {
        Route::get('miscellaneous-payments', 'StoreMiscellaneousPaymentController@getMiscellaneousPayments');
        Route::post('miscellaneous-payments/store', 'StoreMiscellaneousPaymentController@saveMiscellaneousPayment')->middleware('checkScope:manage-all');
        Route::get('miscellaneous-payments/{payment_code}/show', 'StoreMiscellaneousPaymentController@showMiscellaneousPayment');


        Route::get('store/payments/all-lists','StorePaymentController@getAllListsOfPayments');
        Route::get('store/payment/{paymentMethod}/{paymentCode}/show','StorePaymentController@showPaymentDetails');
    });

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\StoreTransaction',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']
    ] , function () {

       // Route::get('store/balance-management/tranFsaction/{store_code}','StoreBalanceApiController@getalltransaction');

        Route::get('store/balance-management/transaction','StoreBalanceApiController@getAllTransactions');

        Route::get('store/balance-management/current-balance','StoreBalanceApiController@getStoreCurrentBalance');
        Route::get('store/transaction-filter/data', 'StoreBalanceApiController@getDataForStoreTransactionFilter');


    });
//    withdraw request api
    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\StoreTransaction\Withdraw',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']
    ] , function () {
        Route::post('store/balance-management/withdrawRequest', 'WithdrawRequestApiController@saveBalanceWithdrawRequest')->middleware('checkScope:manage-all');
        Route::get('store/balance-management/withdraw-request-lists','WithdrawRequestApiController@getWithdrawRequestLists');
        Route::get('store/balance-management/withdraw-request-list/detail/{withdrawRequestCode}','WithdrawRequestApiController@getWithdrawRequestListDetail');
        Route::get('store/balance-management/withdraw-request-verification/detail/{withdrawRequestCode}','WithdrawRequestApiController@getWithdrawRequestVerificationDetail');
        Route::post('store/balance-management/withdraw-request-cancel','WithdrawRequestApiController@cancelBalanceWithdrawRequestByStore');
    });


    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\Payment',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser','storeAccessBarrier']
    ] , function () {

        Route::get('store-order-offline-payments', 'StoreOrderOfflinePaymentController@getStoreOrderPayments');
        Route::get('store-order-offline-payments/{store_order_code}', 'StoreOrderOfflinePaymentController@getOfflinePaymentsListByOrderCode');
        Route::post('store-order-offline-payments/store/{store_order_code}', 'StoreOrderOfflinePaymentController@saveStoreOrderPayment')->middleware('checkScope:manage-all');
        Route::get('store-order-offline-payments/{payment_code}/show', 'StoreOrderOfflinePaymentController@showStoreOrderPayment');
    });

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\Dashboard',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']

    ] , function () {
        Route::get('store/dashboard/stats', 'StoreDashboardController@getDashboardStats')->name('store.dashboard');

        //store otp verification for phone and email
        Route::post('store/generate/phone/otp','StoreProfileApiController@generatePhoneVerificationOTP');
        Route::post('store/generate/email/otp','StoreProfileApiController@generateEmailVerificationOTP');
        Route::post('store/verify/phone/otp','StoreProfileApiController@verifyPhoneOTP');
        Route::post('store/verify/email/otp','StoreProfileApiController@verifyEmailOTP');
    });

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']

    ] , function () {
//        Route::get('store-finder', 'StoreFinderApiController@findStoresInLocation')->name('store.finder');
        Route::get('store-finder', 'StoreFinderApiController@findStoresInWard')->name('store.finder');
        Route::get('all-stores-locations', 'StoreFinderApiController@getAllStoreLocations');
    });

    /**category filter of preOrder**/

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\PreOrder',
        'middleware' => ['isMaintenanceModeOn','auth:api','isStoreUser']
    ] , function () {
        Route::get('store/preorder-categories-filter', 'StorePreOrderControllerApi@getAllPreOrderCategoryByFilter');
    });

    /********End here*********/

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\PreOrder',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']

    ], function () {

        Route::get('store/warehouse-pre-orders-listings/{warehousePreOrderListingCode}', 'StorePreOrderControllerApi@getWHPreOrderListingInfo');
        Route::get('store/warehouse-pre-orders-date', 'StorePreOrderControllerApi@getWarehousePreOrdersDateForStore');
        Route::get('store/warehouse-pre-orders/{warehousePreOrderCode}/products', 'StorePreOrderControllerApi@getWarehousePreOrderProductsForStore');

    });

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\PreOrder',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']

    ], function () {

//        Route::group([
//            'middleware' => ['storeAccessBarrier']
//        ], function () {
//
//            // Save Product in Pre Order By Store
//            Route::post('store/product/pre-order/{warehouse_pre_order_listing_code}', 'StorePreOrderControllerApi@saveProductInPreOrderByStore');
//
//            Route::post('store/update/pre-orders/details/{store_preorder_detail_code}','StorePreOrderControllerApi@updatePreOrderProductQuantity');
//            Route::delete('store/delete/pre-orders/{store_preorder_detail_code}/detail','StorePreOrderControllerApi@deletePreOrderProductDetail');
//        });

        Route::post('store/product/pre-order/{warehouse_pre_order_listing_code}', 'StorePreOrderControllerApi@saveProductInPreOrderByStore')->middleware('checkScope:manage-all');

        Route::post('store/update/pre-orders/details/{store_preorder_detail_code}','StorePreOrderControllerApi@updatePreOrderProductQuantity')->middleware('checkScope:manage-all');
        Route::delete('store/delete/pre-orders/{store_preorder_detail_code}/detail','StorePreOrderControllerApi@deletePreOrderProductDetail')->middleware('checkScope:manage-all');
        Route::get('store/pre-orders','StorePreOrderControllerApi@getStorePreOrders');
        Route::get('store/pre-order/amounts','StorePreOrderControllerApi@getAmountGroupingsOfStorePreOrders');
        Route::get('store/pre-orders/{store_preorder_code}/details','StorePreOrderControllerApi@getStorePreOrderDetails');

        // get preorder products collection for mobile view

        Route::get('store/pre-order/product-collections','StorePreOrderCollectionApiController@getStorePreOrderProductCollections');

    });

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\PreOrder\Product',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']

    ], function () {

        //Single Preorder Product Detail Page
        Route::get('product/{product_slug}/pre-order/{wh_pre_order_listing_code}','SinglePreOrderProductController@getPreOrderProductInfo');
        Route::get('product/{product_slug}/variant/{variant_name}/pre-order/{wh_pre_order_listing_code}','SinglePreOrderProductController@getPreOrderProductVariantImageAndPrice');
        Route::get('pre-orders/{whPreOrderListingCode}/related-products/{productCode}','SinglePreOrderProductController@getRelatedPreOrderProductsOfWhPreOrderListingCode');
        Route::get('pre-orders/variant-associations/pre-order-listing-code/{whPreOrderListingCode}/product-code/{productCode}/variant-value-code/{variantValueCode}/variant-depth/{variantDepth}/{ancestorCode}','SinglePreOrderProductController@getAssociatedPreOrderVariantDetails');

        Route::get('pre-orders/{warehousePreOrderCode}/products/{productCode}/list-view-details', 'SinglePreOrderProductController@getSinglePreOrderProductsListViewDetails');
        Route::get('pre-orders/{warehousePreOrderCode}/all-products/list-view-details', 'SinglePreOrderProductController@getAllPreOrderProductsListViewDetails');

    });


    //store document upload api
    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\StoreDocument',
//        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']
    ] , function () {
        Route::post('stores/{store}/documents', 'StoreDocumentUploadApiController@store');
    });

    //register store with user api
    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\StoreRegistration',
        'middleware' => ['isMaintenanceModeOn']
    ] , function () {
        Route::post('store-registration', 'StoreRegistrationController@createUserWithStoreFromApi');
        Route::get('form-resources', 'StoreRegistrationController@getStoreRegistrationFormResources');
    });

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\StoreRegistration',
        'middleware' => ['isMaintenanceModeOn','auth:api','isStoreUser']
    ] , function () {
        Route::get('store/status', 'StoreRegistrationController@findStoreAccountStatus');
    });

    //fetch form field route
    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front\StoreRegistrationApiFormField',
    ], function () {

        Route::get('store-company-type', 'StoreRegistrationFormFieldController@getAllCompanyType');
        Route::get('store-location', 'StoreRegistrationFormFieldController@getAllLocation');
        Route::get('store-registration-type', 'StoreRegistrationFormFieldController@getAllRegistration');
        Route::get('user-type', 'StoreRegistrationFormFieldController@getAllUserType');
        Route::get('store-size', 'StoreRegistrationFormFieldController@getAllStoreSize');
        Route::get('store-type', 'StoreRegistrationFormFieldController@getAllStoreType');
    });
    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']

    ], function () {
        Route::get('/store-type-packages/get-packages/{storeTypeCode}', 'StoreTypePackageApiController@getStoreTypePackageOfStoreType');
    });
    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser',]

    ], function () {
        //store detail api in profile section
        Route::get('store/detail', 'StoreDetailController@getStoreDetail');

    });
    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn','auth:api','isStoreUser','checkScope:manage-all']
    ] , function () {
        Route::post('store/package-upgrade', 'StorePackageUpgradeRequestApiController@store');
    });

    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn','auth:api','isStoreUser']
    ] , function () {
        Route::post('store-detail/update', 'StoreDetailController@update')->middleware('checkScope:manage-all');
        Route::post('store-map-location/update', 'StoreDetailController@updateStoreMapLocation');
        Route::get('store-detail/has-store', 'StoreDetailController@checkHasStore');
    });


    Route::group([
        'namespace' => 'App\Modules\Store\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn','auth:api','isStoreUser']
    ] , function () {
        Route::post('store-order-remarks/{storeOrderCode}/save', 'StoreOrderRemarkController@saveRemarks');
    });
});


include app_path('Modules/Store/Routes/InvestmentPlan/api-routes.php');
