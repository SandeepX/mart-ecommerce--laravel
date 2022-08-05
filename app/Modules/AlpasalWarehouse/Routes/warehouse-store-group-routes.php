<?php
Route::group([
    'module' => 'AlpasalWarehouse',
    'prefix' => 'warehouse',
    'as' => 'warehouse.',
    'namespace' => 'App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseStoreGroup',
    'middleware' => ['web', 'warehouse.auth', 'isWarehouseUser']
], function () {

    Route::post('warehouse-store-group/store', 'WarehouseStoreGroupController@saveWarehouseStoreGroup');
    Route::put('warehouse-store-group/{groupCode}/update', 'WarehouseStoreGroupController@updateWarehouseStoreGroup');
    Route::put('warehouse-store-group/{groupCode}/toggle-status', 'WarehouseStoreGroupController@toggleWarehouseStoreGroupStatus');
    Route::delete('warehouse-store-group/{groupCode}/delete', 'WarehouseStoreGroupController@deleteWarehouseStoreGroup');

    //group detail routes store add and remove

    Route::post('warehouse-store-group/{groupCode}/mass-create-details', 'WarehouseStoreGroupDetailController@addStoresToGroup');
    Route::post('warehouse-store-group/{groupCode}/sort-stores-order', 'WarehouseStoreGroupDetailController@sortStoresOfGroup');
    Route::delete('warehouse-store-group/{groupCode}/delete-details', 'WarehouseStoreGroupDetailController@massDeleteGroupDetail');

});
