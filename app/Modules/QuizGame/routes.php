<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'module'=>'QuizGame',
    'prefix'=>'admin',
    'as'=>'admin.',
    'namespace' => 'App\Modules\QuizGame\Controllers\Web\Admin',
    'middleware' => ['web','admin.auth','isAdmin']
], function() {

    Route::get('quiz/passage','QuizPassageController@index')->name('quiz.passage.index');
    Route::get('quiz/passage/create','QuizPassageController@create')->name('quiz.passage.create');
    Route::post('quiz/passage/store','QuizPassageController@store')->name('quiz.passage.store');
    Route::get('quiz/passage/edit/{qp_code}','QuizPassageController@edit')->name('quiz.passage.edit');
    Route::put('quiz/passage/update/{qp_code}','QuizPassageController@update')->name('quiz.passage.update');
    Route::get('quiz/passage/show-detail/{qp_code}','QuizPassageController@show')->name('quiz.passage.show');
    Route::delete('quiz/passage/destroy/{qp_code}','QuizPassageController@deleteQuizPassageAlongWithQuestions')->name('quiz.passage.destroy');
    Route::get('quiz/passage/toggle-status/{qp_code}','QuizPassageController@toggleQuizPassageIsActiveStatus')->name('quiz.passage.toggle-status');

    //Quiz Question route:
    Route::get('quiz/passage-question/delete/{questionCode}','QuizQuestionController@deleteQuizQuestions')->name('quiz.passage.question-delete');
    Route::get('quiz/passage-question/edit/{questionCode}','QuizQuestionController@edit')->name('quiz.passage.question.edit');
    Route::put('quiz/passage-question/update/{questionCode}','QuizQuestionController@update')->name('quiz.passage.question.update');
    Route::get('quiz/passage-question/add-more/{passageCode}','QuizQuestionController@addMoreQuestion')->name('quiz.passage.question.create');
    Route::post('quiz/passage-question/add-question/{passageCode}','QuizQuestionController@storeMoreQuestionInPassage')->name('quiz.passage.question.add');

    //Quiz Participator route:
    Route::get('quiz/participator','QuizParticipatorDetailController@index')->name('quiz.participator.index');
    Route::get('quiz/participator/{qpd_code}','QuizParticipatorDetailController@showParticipatorDetail')->name('quiz.participator.show');
    Route::get('quiz/participator/quiz-detail/{participatorCode}','QuizParticipatorDetailController@getAllQuizByParticipatorCode')->name('quiz.participator.quiz-detail');
    Route::get('quiz/participator/submitted-quiz-detail/{quizSubmittedCode}','QuizParticipatorDetailController@getSubmittedQuizDetailByQSCode')->name('quiz.submitted-detail');
    Route::put('quiz/participator/change-status/{qpd_code}','QuizParticipatorDetailController@changeParticipatorStatus')->name('quiz.participator.changeStatus');
    Route::delete('quiz/participator/destroy/{qpd_code}','QuizParticipatorDetailController@delete')->name('quiz.participator.destroy');
});





