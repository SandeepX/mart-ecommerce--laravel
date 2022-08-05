<?php

Route::group([
    'module' => 'PromotionLinks'
], function () {
    Route::group([
        'prefix' => 'api',
        'namespace' => 'App\Modules\PromotionLinks\Controllers\Api\Front',
    ], function () {
        Route::get('promotion-links', 'PromotionLinksApiController@getAllPromotionLinks');
        Route::get('detail/file/{linkCode}', 'PromotionLinksApiController@getPromotionLinkDetail');
        Route::get('download/file/{linkCode}', 'PromotionLinksApiController@downloadPromotionLink');
    });
});
