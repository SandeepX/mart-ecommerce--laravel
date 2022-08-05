<?php


Route::group([
    'module'=>'PromotionLinks',
    'prefix'=>'admin',
    'as'=>'admin.',
    'middleware'=>['web','admin.auth','isAdmin','ipAccess'],
    'namespace' => 'App\Modules\PromotionLinks\Controllers\Web\Admin'], function() {

    // Admin Promotion Links Routes...
    Route::resource('promotion-links', 'PromotionLinkController');

});
