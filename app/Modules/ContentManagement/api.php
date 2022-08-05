<?php

Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'ContentManagement',
        'middleware' =>['isMaintenanceModeOn'],
        'namespace' => 'App\Modules\ContentManagement\Controllers\Api\Front'
    ], function () {
        Route::get('about-us', 'SitePageController@getAboutUsContent');
        Route::get('privacy-policy', 'SitePageController@getPrivacyPolicyContent');
        Route::get('terms-and-conditions', 'SitePageController@getTermsAndConditionsContent');
        Route::get('faqs', 'SitePageController@getFaqs');
        Route::get('static-page-image/{page_name}','StaticPageImageController@getPageImages');
        Route::get('about','AboutUsController@index');
        Route::get('vision-mission','VisionMissionController@index');
        Route::get('company-timeline','CompanyTimelineController@index');
        Route::get('our-teams','OurTeamController@index');
        Route::get('team-testimonial','OurTeamController@testimonial');
        Route::get('team-gallery','TeamGalleryController@index');
    });
});

