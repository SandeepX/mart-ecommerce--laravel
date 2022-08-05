<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api', 'module' => 'LuckyDraw'], function () {

    Route::group([
        'namespace' => 'App\Modules\LuckyDraw\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn','auth:api','checkUserType:super-admin,store']

    ], function () {

        Route::get('store-lucky-draws/status/{status}', 'StoreLuckydrawController@getStoreLuckydraws');
        Route::get('store-lucky-draws/detail/{SLCode}', 'StoreLuckydrawController@storeLuckydrawDetail');

//        winner selection
      //  Route::get('store-lucky-draws/select-winner/{SLCode}', 'StoreLuckydrawController@openLuckydraw');

    });
    Route::group([
        'namespace' => 'App\Modules\LuckyDraw\Controllers\Api\Front',
        'middleware' => ['auth:api','isMaintenanceModeOn','isSuperAdmin']

    ], function () {

//        winner selection
        Route::post('store-lucky-draws/open-lucky-draw/{SLCode}', 'StoreLuckydrawController@openLuckyDraw');
        Route::get('store-lucky-draws/select-winner/{SLCode}', 'StoreLuckydrawController@selectLuckyDrawWinner');
        Route::get('store-lucky-draws/close-lucky-draw/{SLCode}', 'StoreLuckydrawController@closeLuckydraw');

    });

});


