<?php

Route::group([
    'module'=>'Career',
    'prefix'=>'api',
    'namespace' => 'App\Modules\Career\Controllers\Api\Front',
    'middleware' => ['isMaintenanceModeOn']
], function() {
    Route::group([
        'prefix'=>'career',
    ], function() {

        Route::get('job-openings', 'JobOpeningApiController@index');
        Route::get('job-openings/{slug}/show', 'JobOpeningApiController@showJobOpening');
        Route::post('job-applications/{job_opening}/store', 'JobApplicationApiController@storeJobApplication');
        Route::post('candidates','CandidateApiController@createCandidate');
    });
});
