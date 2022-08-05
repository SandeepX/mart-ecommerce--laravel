<?php

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'App\Modules\Questionnaire\Controllers\Web\Admin',
    'middleware' => ['web', 'admin.auth','isAdmin']
], function () {
    Route::resource('verification-questions', 'VerificationQuestionnaireController');
});
