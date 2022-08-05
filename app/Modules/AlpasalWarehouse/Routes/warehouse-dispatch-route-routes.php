<?php
Route::group([
    'module' => 'AlpasalWarehouse',
    'prefix' => 'api/warehouse',
    'as' => 'warehouse.',
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseDispatchRoute',
    'middleware' => ['web', 'warehouse.auth', 'isWarehouseUser']
], function () {

    Route::get('warehouse-dispatch-routes', 'WarehouseDispatchRouteController@getWarehouseDispatchRoutes');
    Route::get('warehouse-dispatch-routes/{dispatchRouteCode}/detail', 'WarehouseDispatchRouteController@showWarehouseDispatchRouteDetail');
    Route::get('dispatchable-stores', 'WarehouseDispatchRouteController@getAvailableStores');
    Route::post('warehouse-dispatch-route/store', 'WarehouseDispatchRouteController@saveWarehouseDispatchRouteWithStores');
    Route::put('warehouse-dispatch-route/{dispatchRouteCode}/minimal-update', 'WarehouseDispatchRouteController@updateMinimalWarehouseDispatchRoute');

    Route::put('warehouse-dispatch-route/{dispatchRouteCode}/final-update', 'WarehouseDispatchRouteController@finalDispatchWarehouseRoute');

    Route::delete('warehouse-dispatch-route/{dispatchRouteCode}/delete', 'WarehouseDispatchRouteController@deleteDispatchRoute');

    //dispatch routes store add and remove
    Route::post('warehouse-dispatch-route/{dispatchRouteCode}/mass-add-stores', 'WarehouseDispatchRouteStoreController@addStoresToDispatchRoute');
    Route::put('warehouse-dispatch-route/{dispatchRouteCode}/sort-stores-order', 'WarehouseDispatchRouteStoreController@sortStoresOfDispatchRoute');
    Route::delete('warehouse-dispatch-route/{dispatchRouteCode}/delete-stores', 'WarehouseDispatchRouteStoreController@deleteDispatchRouteStores');
    Route::delete('warehouse-dispatch-route/{dispatchRouteCode}/mass-delete-stores', 'WarehouseDispatchRouteStoreController@deleteMassStoresByDispatchCode');

    //dispatch route orders
    Route::post('warehouse-dispatch-route/{dispatchRouteCode}/mass-add-store-orders', 'WarehouseDispatchRouteStoreOrderController@addStoresOrderToDispatchRoute');
    Route::delete('warehouse-dispatch-route/{dispatchRouteCode}/delete-store-orders', 'WarehouseDispatchRouteStoreOrderController@deleteRouteStoreOrders');

    //dispatch route markers
    Route::post('warehouse-dispatch-route/{dispatchRouteCode}/mass-add-markers', 'WarehouseDispatchRouteMarkerController@createDispatchRouteMarkers');
    Route::delete('warehouse-dispatch-route/{dispatchRouteCode}/mass-delete-markers', 'WarehouseDispatchRouteMarkerController@deleteMassRouteMarkers');
});

Route::group([
    'module' => 'AlpasalWarehouse',
    'prefix' => 'warehouse',
    'as' => 'warehouse.',
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseDispatchRoute',
    'middleware' => ['web', 'warehouse.auth', 'isWarehouseUser']
], function () {

    Route::get('warehouse-dispatch-routes/{dispatchRouteCode}/show', 'WarehouseDispatchRoutePageController@showPage')->name('dispatch-route.show-page');

    //warehouse dispatch routes lists
    Route::get('warehouse-dispatch-routes/lists', 'WarehouseDispatchRoutePageController@getDispatchRoutesLists')->name('dispatch-route.lists');
});
