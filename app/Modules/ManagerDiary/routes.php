<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'ManagerDiary',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\ManagerDiary\Controllers\Web\Admin\Diary',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {
    Route::get('manager-diaries/{managerCode}', 'ManagerDiaryAdminController@index')->name('manager-diaries.index');
    Route::get('manager-diaries/{managerDiaryCode}/detail', 'ManagerDiaryAdminController@showManagerDiaryDetail')->name('manager-diaries.detail');

});

Route::group([
    'module'=>'ManagerDiary',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\ManagerDiary\Controllers\Web\Admin\VisitClaim',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {
    Route::get('store-visit-claim-requests', 'StoreVisitClaimRequestsAdminController@getAllStoreVisitClaimRequests')->name('store-visit-claim-requests.index');
    Route::get('store-visit-claim-requests/{storeVisitClaimCode}/detail', 'StoreVisitClaimRequestsAdminController@showStoreVisitClaimRequestDetails')->name('store-visit-claim-requests.show');
    Route::get('store-visit-claim-requests/{storeVisitClaimCode}/respond/form','StoreVisitClaimRequestsAdminController@getStoreVisitClaimRequestRespondForm')->name('store-visit-claim-requests.respond.form');
    Route::post('store-visit-claim-requests/{storeVisitClaimCode}/respond','StoreVisitClaimRequestsAdminController@respondToStoreVisitClaimRequest')->name('store-visit-claim-requests.respond');
});

Route::group([
    'module'=>'ManagerDiary',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\ManagerDiary\Controllers\Web\Admin\PayPerVisit',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {
    Route::resource('manager-pay-per-visits', 'ManagerPayPerVisitController');
});

Route::group([
    'module'=>'ManagerDiary',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\ManagerDiary\Controllers\Web\Admin\VisitClaimRedirection',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {
    Route::resource('visit-claim-scan-redirection', 'StoreVisitClaimScanRedirectionController');
});
