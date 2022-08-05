<?php


namespace App\Modules\QuizGame\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\QuizGame\Requests\QuizQuestion\QuizQuestionStoreRequest;
use App\Modules\QuizGame\Requests\QuizQuestion\QuizQuestionUpdateRequest;
use App\Modules\QuizGame\Services\QuizQuestionService;
use App\Modules\QuizGame\Services\QuizService;
use Exception;


class QuizQuestionController extends BaseController
{
    public $title = 'Quiz Game Passage Question';
    public $base_route = 'admin.quiz.passage';
    public $sub_icon = 'file';
    public $module = 'QuizGame::';
    public $view = 'quiz-passage.';

    private $quizQuestionService;
    private $quizService;

    public function __construct(QuizQuestionService $quizQuestionService,QuizService $quizService)
    {
        $this->quizQuestionService = $quizQuestionService;
        $this->quizService = $quizService;
    }

    public function addMoreQuestion($passageCode)
    {
        try{
            $passageDetail = $this->quizService->findNotExpiredPassageDetailByPassageCode($passageCode);
            return view(Parent::loadViewData($this->module . $this->view . 'quiz-question.add-more-question'),
                compact('passageDetail')
            );
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function edit($question_code)
    {
        try{
            $quizQuestionDetail = $this->quizQuestionService->findPassageQuestionDetailByCode($question_code);
            return view(Parent::loadViewData($this->module . $this->view . 'quiz-question.edit-question'),
                compact('quizQuestionDetail'));
        }catch(Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }

    public function update(QuizQuestionUpdateRequest $request,$questionCode)
    {
        try{
            $questionDetail = $this->quizQuestionService->findPassageQuestionDetailByCode($questionCode);
            $validatedData = $request->validated();
            $question = $this->quizQuestionService->updateQuestion($validatedData,$questionDetail);
            return redirect()->back()
                ->with('success', $this->title . ':  Updated Successfully');
        }catch(Exception $e){
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function deleteQuizQuestions($questionCode)
    {
        try {
            $quizQuestionDetail = $this->quizQuestionService->findPassageQuestionDetailByCode($questionCode);
            $deleteStatus = $this->quizQuestionService->deletePassageQuestions($quizQuestionDetail);
            return redirect()->back()
                ->with('success', $this->title . ':  deleted Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function storeMoreQuestionInPassage(QuizQuestionStoreRequest $request,$quizPassageCode)
    {
        try{
            $passageDetail = $this->quizService->findNotExpiredPassageDetailByPassageCode($quizPassageCode);
            $validatedData = $request->validated();
            $this->quizQuestionService->addMoreQuestionToPassage($passageDetail,$validatedData);
            return redirect()->back()->with('success', 'New Questions Added In Passage Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

}

