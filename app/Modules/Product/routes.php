<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'module' => 'Product',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Product\Controllers\Web\Admin\ProductSensitivity',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']

], function () {
    Route::resource('/product-sensitivities', 'ProductSensitivityController');
});

Route::group([
    'module' => 'Product',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Product\Controllers\Web\Admin\ProductWarranty',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']

], function () {
    Route::resource('/product-warranties', 'ProductWarrantyController');
});

Route::group([
    'module' => 'Product',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Product\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']

], function () {
    Route::get('/products', 'ProductController@index')->name('products.index');
    Route::get('/products/{product}', 'ProductController@show')->name('products.show');
    Route::get('/products/toggle-status/{productCode}', 'ProductController@toggleStatus')->name('products.toggle-status');

});



Route::group([
    'module' => 'Product',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Product\Controllers\Web\Admin\ProductCollection',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']

], function () {
    Route::get('product-collections/{product_collection_code}/products','ProductCollectionController@showProductAdditionInCollection')->name('product-collection.show.add-products');
    Route::post('product-collections/{product_collection_code}/products','ProductCollectionController@addProductsToCollection')->name('product-collection.add-products');
    Route::delete('product-collections/{product_collection_code}/remove/product/{product_code}','ProductCollectionController@removeProductFromCollection')->name('product-collection.remove-product');
    Route::resource('product-collections', 'ProductCollectionController');
    Route::get('/product-collections/toggle-status/{productCollectionCode}', 'ProductCollectionController@updateProductCollectionStatus')->name('product-collections.toggle-status');
    Route::get('/products/toggle-status/{productCollectionCode}/{productCode}', 'ProductCollectionController@toggleStatus')->name('collection.products.toggle-status');



});

Route::group([
    'module' => 'Product',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Product\Controllers\Web\Admin\ProductVerification',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']

], function () {
    Route::post('product/{product}/verify','ProductVerificationController@storeProductVerification')->name('product-verification.store');

});

Route::group([
    'module' => 'Product',
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Product\Controllers\Web\Admin\WarehouseProduct',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']

], function () {
    Route::get('warehouse-products/warehouses-stock/{productCode}','WarehouseProductController@showWarehousesProductStocksDetail')->name('warehouse-products.warehouses-stock');

});



