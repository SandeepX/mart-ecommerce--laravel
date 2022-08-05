<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api', 'module' => 'ProductRatingReview'], function () {

    Route::group([
        'namespace' => 'App\Modules\ProductRatingReview\Controllers\Api\Store',
        'middleware' => ['isMaintenanceModeOn','auth:api','isStoreUser'],
        'as'=>'store.',
    ] , function () {
        Route::post('store/product-rating/create', 'ProductRatingReviewController@storeProductRatingByStore')->name('product-rating.create');
        Route::post('store/product-review/create', 'ProductRatingReviewController@storeProductReviewByStore')->name('product-review.create');
        Route::delete('store/product-review/delete/{review_code}', 'ProductRatingReviewController@deleteProductReviewByStore')->name('product-review.delete');
    });

    Route::group([
        'namespace' => 'App\Modules\ProductRatingReview\Controllers\Api\Store',
        'middleware' => ['isMaintenanceModeOn','auth:api'],
        'as'=>'store.',
    ] , function () {
        Route::post('store/product-review/reply/{review_code}', 'ProductRatingReviewController@storeProductReviewReply')->name('product-review.reply');
        Route::delete('store/product-review/reply/delete/{reply_code}', 'ProductRatingReviewController@deleteProductReviewReply')->name('product-review.reply.delete');
    });

    Route::group([
        'namespace' => 'App\Modules\ProductRatingReview\Controllers\Api\Store',
        'middleware' => ['isMaintenanceModeOn'],
        'as'=>'store.',
    ] , function () {
        Route::get('store/product-reviews/{product_code}', 'ProductRatingReviewController@getWarehouseProductReviewsByStore')->name('product-reviews.index');
    });
});
