<?php

Route::group([
    'module'=>'AlpasalWarehouse',
    'namespace'=>'App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse',
    'prefix'=>'warehouse',
    'as'=>'warehouse',
    'middleware'=>['web','admin.auth']
],function (){
    Route::get('list','WarehouseFilterControllerApi@warhouseListsWithConnectedStores')->name('lists');
    Route::get('/{warehouseCode}/vendors/list','WarehouseFilterControllerApi@getAllRelatedVendorsOfWarehouseByWarehouseCode')->name('vendors.list');
    Route::get('/{warehouseCode}/products/list','WarehouseFilterControllerApi@getAllRelatedProductOfWarehouseByWarehouseCode')->name('products.list');
});
