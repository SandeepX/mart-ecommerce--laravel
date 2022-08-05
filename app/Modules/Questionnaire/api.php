<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Route::group([
        'module' => 'Questionnaire',
        'namespace' => 'App\Modules\Questionnaire\Controllers\Api\Admin',
        'middleware' => ['isMaintenanceModeOn', 'web']
        ], function () {
            Route::get('verification/entity/{entity}/action/{action}/questions', 'ActionVerificationQuestionsApiController@getVerificationQuestionByEntityAndAction');
    });
});


