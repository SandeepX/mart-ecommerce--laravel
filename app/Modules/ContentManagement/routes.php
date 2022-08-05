<?php

Route::group([
    'module'=>'ContentManagement',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\ContentManagement\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::DElETE('static-page-images/single-image/{SPICode}','StaticPageImageController@deleteSingleImage')->name('delete-single-Image');
    Route::DElETE('static-page-images/whole-image/{pageName}','StaticPageImageController@deleteAllImage')->name('delete-All-Image');
    Route::resource('site-pages', 'SitePageController');
    Route::resource('faqs', 'FaqController');
    Route::resource('static-page-images','StaticPageImageController');
    Route::resource('about-us', 'AboutUsController');
    Route::resource('vision-mission', 'VisionMissionController');
    Route::resource('company-timeline', 'CompanyTimelineController');
    Route::resource('our-teams','OurTeamController');
    Route::resource('team-gallery','TeamGalleryController');

});
