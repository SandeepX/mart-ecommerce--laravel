<?php
use Illuminate\Support\Facades\Route;
Route::group([
    'module'=>'LuckyDraw',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\LuckyDraw\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    /****route****/

    //Route::get('stores/unapproved','StoreController@getUnapprovedStores')->name('stores.store-registration.unapproved');
    Route::resource('store-lucky-draws', 'StoreLuckydrawController');
    Route::get('store-lucky-draws/toggle-status/{SLCode}/{status}', 'StoreLuckydrawController@toggleStatus')
       ->name('store-lucky-draws.toggle-status');
    Route::get('/store-lucky-draws/change-active-status/{SLCode}/{status}', 'StoreLuckydrawController@changeStatus')
        ->name('store-lucky-draws.change-active-status');
    Route::get('store-lucky-draws/open-luckydraw/{SLCode}', 'StoreLuckydrawController@openLuckydraw')
        ->name('store-lucky-draws.open-luckydraw');
    Route::get('store-lucky-draws/re-select-winner/{SLCode}', 'StoreLuckydrawController@reSelectWinner')
        ->name('store-lucky-draws.re-select-winner');
    Route::get('store-lucky-draws/pre-load-store-page/{SLCode}', 'StoreLuckydrawController@preLoadStorePage')
        ->name('store-lucky-draws.pre-load-store-page');
    Route::get('store-lucky-draws/pre-load-store-lists/{SLCode}', 'StoreLuckydrawController@preLoadStoreLists')
        ->name('store-lucky-draws.pre-load-store-lists');

    // Prefix winner

    Route::resource('prefix-winners', 'PrefixWinnerController');
    Route::get('prefix-winners/edit/{PWCode}/{SLCode}', 'PrefixWinnerController@edit')
      ->name('prefix-winners.edit');
    Route::get('store-lucky-draws/prefix-winners/{storeLuckydrawCode}',
        'PrefixWinnerController@getStoresForPrefixWinner')
        ->name('store-lucky-draws.getStoresForPrefixWinner');
    Route::post('prefix-winners/change-display-order/{SLCode}', 'PrefixWinnerController@changePrefixWinnerDisplayOrder')
        ->name('store-type-packages.change-display-order');

    //store luckydraw winner
    Route::get('store-luckydraw-winners', 'StoreLuckydrawWinnerController@index')
        ->name('store-luckydraw-winners.index');
    Route::get('store-luckydraw-winners/show/{SLCode}', 'StoreLuckydrawWinnerController@show')
        ->name('store-luckydraw-winners.show');
});




