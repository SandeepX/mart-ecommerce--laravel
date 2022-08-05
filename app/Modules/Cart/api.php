<?php

Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'Cart',
        'namespace' => 'App\Modules\Cart\Controllers\Api\Frontend',
       'middleware' => ['isMaintenanceModeOn','auth:api','isStoreUser']
    ], function () {

        Route::put('carts/update-quantity/{cart_code}', 'CartController@updateQuantity')->middleware('checkScope:manage-all');
        Route::delete('carts/mass-destroy', 'CartController@massDestroy')->middleware('checkScope:manage-all');
        Route::apiresource('user/carts', 'CartController')->middleware('checkScope:manage-all');
        Route::get('user/cart/counts','CartController@getUserCartCounts');

    });
});
