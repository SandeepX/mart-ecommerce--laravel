<?php

use Illuminate\Support\Facades\Route;

    Route::group([
        'module'=>'QuizGame',
        'prefix'=>'api',
        'namespace' => 'App\Modules\QuizGame\Controllers\Api',
        'middleware' => ['isMaintenanceModeOn','auth:api']
    ], function() {
        Route::get('quiz/all-detail', 'QuizPassageController@getPassageDetailOfTheDayAlongWithQuestion');
        Route::post('quiz/participator-detail/store', 'QuizParticipatorController@storeParticipatorDetail');
        Route::put('quiz/participator-detail/update/{qpd_code}', 'QuizParticipatorController@updateParticipatorDetail');

        Route::post('quiz/submit-detail/store', 'QuizSubmissionController@storeQuizSubmission');
        Route::get('quiz/submissions', 'QuizSubmissionController@getAllQuizSubmissionByParticipator');
        Route::get('quiz/submitted-quiz-detail/{qs_code}', 'QuizSubmissionController@getQuizSubmittedDetailByQSCode');

        Route::get('quiz/check/{questionCode}/correct-answers/{submittedAnswer}','QuizSubmissionController@checkCorrectAnswerOfQQCode');

    });
