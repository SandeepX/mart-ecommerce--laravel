<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'Vendor',
        //'middleware' => ['throttle:3,1'],// 5 attempts and block for 1 minute
        'namespace' => 'App\Modules\Vendor\Controllers\Api\Frontend\Authentication',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::post('vendor-login', 'VendorAuthenticationController@loginVendor');
    });


    Route::group([
        'module' => 'Vendor',
        'prefix' => 'vendor',
        'as' => 'vendor.',
        'middleware' => ['isMaintenanceModeOn','api','auth:api', 'isVendorUser'], // must be vendor after loggen IN (isVendor middleware)
        'namespace' => 'App\Modules\Vendor\Controllers\Api\Frontend'
    ], function () {

        Route::get('products/{product_code}/configure-packaging',
            'VendorProductPackagingController@getProductPackagingConfiguration');

        Route::post('products/{product_code}/configure-packaging',
            'VendorProductPackagingController@configureProductPackaging');

        Route::delete('products/{product_code}/delete-packaging-detail',
            'VendorProductPackagingController@deleteProductPackaging');

        Route::post('products/toggle-taxability/{product_code}', 'VendorProductController@toggleProductTaxability');
        Route::post('products/toggle-activation/{product_code}', 'VendorProductController@toggleProductActivation');

        Route::apiResource('products', 'VendorProductController');
        Route::apiResource('{vendor}/warehouses', 'VendorWarehouseController');

        Route::post('products/{product}/price-list', 'ProductPriceController@storeProductPrice');
        Route::get('products/{product}/price-list', 'ProductPriceController@getProductPrice');

        //vendor detail api in profile section
        Route::get('detail', 'VendorDetailController@getVendorDetail');

        //Vendor Documents

        Route::get('documents','VendorDocumentController@getAllDocuments');
        Route::post('documents/store','VendorDocumentController@storeDocument');
        Route::delete('delete/document/{documentId}','VendorDocumentController@deleteDocument');



    });

    Route::group([
        'module' => 'Vendor',
        'prefix' => 'vendor',
        'as' => 'vendor.',
        'middleware' => ['isMaintenanceModeOn','api','auth:api', 'isVendorUser'], // must be vendor after loggen IN (isVendor middleware)
        'namespace' => 'App\Modules\Vendor\Controllers\Api\Frontend\Dashboard'
    ], function () {
        Route::get('dashboard/stats', 'VendorDashboardController@getDashboardStats')->name('dashboard');
    });

    Route::group([
        'module' => 'Vendor',
        'prefix' => 'vendor',
        'as' => 'vendor.',
        'middleware' => ['isMaintenanceModeOn','api','auth:api', 'isVendorUser'], // must be vendor after loggen IN (isVendor middleware)
        'namespace' => 'App\Modules\Vendor\Controllers\Api\Frontend\SalesOrder'
    ], function () {
        Route::get('vendor-sales-orders/sales-returns', 'VendorSalesOrderController@getSalesReturns')->name('sales-returns.index');
        Route::get('vendor-sales-orders/sales-returns/show/{warehouseOrderCode}', 'VendorSalesOrderController@showSalesReturnDetail')->name('sales-returns.show');
        Route::post('vendor-sales-orders/sales-returns/respond/{purchaseReturnCode}', 'VendorSalesOrderController@respondToSalesReturn')->name('sales-returns.respond');
        //vendor sales report
        Route::get('vendor-sales-report','VendorSalesOrderController@getVendorSalesReportByVendorCode');
    });

    Route::group([
        'module' => 'Vendor',
        'prefix' => 'vendor',
        'as' => 'vendor.',
        'middleware' => ['isMaintenanceModeOn','api','auth:api', 'isVendorUser'], // must be vendor after logged IN (isVendor middleware)
        'namespace' => 'App\Modules\Vendor\Controllers\Api\Frontend'
    ], function () {
        //vendor Activity
        Route::get('vendor-activity','VendorActivityController@getVendorActivity');
        //set vendor Target Master
        Route::get('vendor-set-target', 'VendorTargetMasterController@index');
        Route::get('vendor-set-target/show/{VTMcode}','VendorTargetMasterController@getVendorTargetByCode');
        Route::get('vendor-set-target/edit/{VTMcode}','VendorTargetMasterController@edit');
        Route::post('vendor-set-target/store', 'VendorTargetMasterController@store');
        Route::put('vendor-set-target/update/{VTMcode}','VendorTargetMasterController@update');
        Route::delete('vendor-set-target/destroy/{VTMcode}','VendorTargetMasterController@destroy');


        //vendor Target Incentive

        Route::get('vendor-target-incentive', 'VendorTargetIncentiveController@index');
        Route::get('vendor-target-incentive/show/{VTIcode}', 'VendorTargetIncentiveController@getVendorTargetIncentativeBycode');
        Route::get('vendor-target-incentive/edit/{VTIcode}', 'VendorTargetIncentiveController@edit');
        Route::post('vendor-target-incentive/store', 'VendorTargetIncentiveController@store');
        Route::put('vendor-target-incentive/update/{VTIcode}','VendorTargetIncentiveController@update');
        Route::delete('vendor-target-incentive/delete/{VTIcode}','VendorTargetIncentiveController@destroy');
    });






















//    //set vendor Target Master
//    Route::group([
//        'module' => 'Vendor',
//        'prefix' => 'vendor',
//        'as' => 'vendor.',
//        'middleware' => ['isMaintenanceModeOn','api','auth:api', 'isVendorUser'], // must be vendor after logged IN (isVendor middleware)
//        'namespace' => 'App\Modules\Vendor\Controllers\Api\Frontend'
//    ], function () {
//        Route::get('vendor-set-target', 'VendorTargetMasterController@index');
//        Route::get('vendor-set-target/show/{VTMcode}','VendorTargetMasterController@getVendorTargetByCode');
//        Route::get('vendor-set-target/edit/{VTMcode}','VendorTargetMasterController@edit');
//        Route::post('vendor-set-target/store', 'VendorTargetMasterController@store');
//        Route::put('vendor-set-target/update/{VTMcode}','VendorTargetMasterController@update');
//        Route::delete('vendor-set-target/destroy/{VTMcode}','VendorTargetMasterController@destroy');
//    });
//
//   //vendor Target Incentive
//    Route::group([
//        'module' => 'Vendor',
//        'prefix' => 'vendor',
//        'as' => 'vendor.',
//        'middleware' => ['isMaintenanceModeOn','api','auth:api', 'isVendorUser'], // must be vendor after logged IN (isVendor middleware)
//        'namespace' => 'App\Modules\Vendor\Controllers\Api\Frontend'
//    ], function () {
//        Route::get('vendor-target-incentive', 'VendorTargetIncentiveController@index');
//        Route::get('vendor-target-incentive/show/{VTIcode}', 'VendorTargetIncentiveController@getVendorTargetIncentativeBycode');
//        Route::get('vendor-target-incentive/edit/{VTIcode}', 'VendorTargetIncentiveController@edit');
//        Route::post('vendor-target-incentive/store', 'VendorTargetIncentiveController@store');
//        Route::put('vendor-target-incentive/update/{VTIcode}','VendorTargetIncentiveController@update');
//        Route::delete('vendor-target-incentive/delete/{VTIcode}','VendorTargetIncentiveController@destroy');
//
//    });






});
