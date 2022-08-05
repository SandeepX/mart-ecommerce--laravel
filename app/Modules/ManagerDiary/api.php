<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api', 'module' => 'ManagerDiary'], function () {
    Route::group([
        'namespace' => 'App\Modules\ManagerDiary\Controllers\Api\Front\Diary',
        'middleware' => ['isMaintenanceModeOn','auth:api','isSalesManageUser']
        //   'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::resource('manager-diaries', 'ManagerDiaryApiController');
    });
});

Route::group(['prefix' => 'api', 'module' => 'ManagerDiary'], function () {
    Route::group([
        'namespace' => 'App\Modules\ManagerDiary\Controllers\Api\Front\VisitClaim',
        'middleware' => ['isMaintenanceModeOn','auth:api','isSalesManageUser']
        //   'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::post('store-visit-claim-request/{managerDiaryCode}/save','StoreVisitClaimRequestByManagerApiController@saveStoreVisitClaimRequestByManager');
        Route::get('store-visit-claim-requests/manager/list','StoreVisitClaimRequestByManagerApiController@getAllStoreVisitClaimRequestsOfManager');
        Route::post('store-visit-claim-request/{storeVisitClaimRequestCode}/submit','StoreVisitClaimRequestByManagerApiController@submitScannedStoreVisitClaimRequestByManager');

    });

});

Route::group(['prefix' => 'api', 'module' => 'ManagerDiary'], function () {
    Route::group([
        'namespace' => 'App\Modules\ManagerDiary\Controllers\Api\Front\VisitClaim',
        'middleware' => ['isMaintenanceModeOn','auth:api','isStoreUser']
        //   'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::post('store-visit-claim/scan-request/{storeVisitClaimRequestCode}','StoreVisitClaimRequestByManagerApiController@scanStoreVisitClaimRequestByStore');
    });

});
