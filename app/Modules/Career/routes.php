<?php


Route::group([
    'module'=>'Career',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\Career\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin','ipAccess']
], function() {

    Route::resource('job-questions', 'JobQuestionController');
    Route::resource('job-openings', 'JobOpeningController');
    Route::resource('job-applications', 'JobApplicationController');
    Route::resource('careers', 'CareerController');
    Route::get('candidates','CandidateController@index')->name('candidates.index');
    Route::get('candidates/{candidateCode}/show','CandidateController@show')->name('candidates.show');

});




