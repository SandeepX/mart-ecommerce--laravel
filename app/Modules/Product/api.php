<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {

    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('products/categories', 'ProductCategoryController@getProductsOfCategories');
    });

    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front\ProductFilter',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('filter-products/vendor/{vendor_code}', 'ProductFilterController@filterProductsOfVendor');
        Route::get('filter-product-variants/product/{product_code}', 'ProductFilterController@getProductVariantsOfProduct');
    });


    //for admin
    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Admin',
        'prefix'=>'admin'
    ], function () {
        Route::get('filter-products/vendor', 'ProductFilterControllerApi@filterProductsOfVendor')->name('admin-api.vendor-products.filter');
        Route::get('filter-product-variants/product/{product_code}', 'ProductFilterControllerApi@getProductVariantsOfProduct');
        Route::get('product-packaging-types/{product_code}/{product_variant_code}', 'ProductFilterControllerApi@getAvailableProductPackagingTypes');
    });

    //for warehouse
    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Warehouse',
        'prefix'=>'warehouse'
    ], function () {
        Route::get('filter-products/vendor', 'ProductFilterControllerApi@filterProductsOfVendor')->name('warehouse-api.vendor-products.filter');
       // Route::get('filter-product-variants/product/{product_code}', 'ProductFilterControllerApi@getProductVariantsOfProduct');
    });


    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front\ProductCollection',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('product-collections', 'ProductCollectionController@getAllActiveProductCollections');
        Route::get('product-collection/{product_collection_slug}', 'ProductCollectionController@getProductCollectionDetails');
        Route::get('products/product-collection/{product_collection_slug}', 'ProductCollectionController@getProductsOfCollection');
    });


    Route::group([
        'module' => 'Product',
        'prefix' => 'admin',
        'namespace' => 'App\Modules\Product\Controllers\Api\Admin\ProductSensitivity'
    ], function () {
        Route::apiResource('/product-sensitivities', 'ProductSensitivityController');
    });

    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front',
        'middleware' => ['auth:api', 'isVendorUser']
    ], function () {
        Route::delete('/products/{productCode}/images/{image}', 'ProductImageController@destroy');
        Route::delete('products/{product}/variants', 'ProductVariantController@destroyProductVariants');
        Route::delete('/products/{product}/variant-value/{variant_value}', 'ProductVariantController@destroyProductVariantsByVariantValue');
        Route::delete('/products/{productCode}/variants/{productVariantCode}/images/{image}', 'ProductVariantImageController@destroy');
        Route::delete('/products/{productCode}/variants/{variant}', 'ProductVariantController@destroy');

        //Product Variant Group Routes starts here
        Route::delete('product/{productCode}/variant/group/{productVariantGroupCode}/delete','ProductVariantGroupController@deleteProductVariantGroup');
        Route::delete('product/{productCode}/variant/group/{productVariantGroupCode}/bulk/image/{groupBulkImageCode}/delete','ProductVariantGroupController@deleteProductVariantGroupBulkImage');
        // ends here
    });

    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('brand-product/{brandSlug}','ProductBrandController@productsByBrandSlug');
    });

    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('/products/{product}', 'ProductController@show');
        Route::get('/products/{productSlug}/{variantName}', 'ProductController@getProductVariantImageAndPrice');
        Route::get('normal-orders/variant-associations/product-code/{productCode}/variant-value-code/{variantValueCode}/variant-depth/{variantDepth}/{ancestorCode}','ProductController@getAssociatedNormalOrderVariantDetails');
        Route::get('products/{productCode}/stocks/list-view','ProductController@singleProductListViewDetails');
    });

    Route::group([
        'module' => 'Product',
        'prefix' => 'admin',
        'namespace' => 'App\Modules\Product\Controllers\Api\Admin\ProductWarranty'
    ], function () {
        Route::apiResource('/product-warranties', 'ProductWarrantyController');
    });

    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('/product-warranties', 'ProductWarrantyController@index');
    });

    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('/product-sensitivities', 'ProductSensitivityController@index');
    });

    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front\ProductSearch',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('/search-product', 'ProductSearchController@searchProductByName');
    });

    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front\RelatedProduct',
        'middleware' => ['isMaintenanceModeOn']
    ], function () {
        Route::get('related-products/product/{product}', 'RelatedProductController@relatedProducts');
    });


    Route::group([
        'module' => 'Product',
        'namespace' => 'App\Modules\Product\Controllers\Api\Front',
        'middleware' => ['isMaintenanceModeOn','auth:api', 'isStoreUser']
    ], function () {
          Route::get('products/{productCode}/stocks/package/list-view','ProductController@getSingleProductListViewDetails');
          Route::get('products/stocks/package/all-list-view','ProductController@getAllProductListViewDetails');

          Route::get('most-popular/all-products','MostPopularProductApiController@getAllMostPopularProducts');
          Route::get('most-popular/limited-products','MostPopularProductApiController@getLimitedMostPopularProducts');
    });

});
