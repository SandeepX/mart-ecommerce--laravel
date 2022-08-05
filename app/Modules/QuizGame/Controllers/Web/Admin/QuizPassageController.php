<?php


namespace App\Modules\QuizGame\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\QuizGame\Requests\QuizDate\QuizDateRequest;
use App\Modules\QuizGame\Requests\QuizDate\QuizDateUpdateRequest;
use App\Modules\QuizGame\Requests\QuizPassage\QuizPassageStoreRequest;
use App\Modules\QuizGame\Requests\QuizPassage\QuizPassageUpdateRequest;
use App\Modules\QuizGame\Requests\QuizQuestion\QuizQuestionStoreRequest;
use App\Modules\QuizGame\Services\QuizService;
use Exception;
use Illuminate\Http\Request;


class QuizPassageController extends BaseController
{
    public $title = 'Quiz Game Passage';
    public $base_route = 'admin.quiz.passage';
    public $sub_icon = 'file';
    public $module = 'QuizGame::';
    public $view = 'quiz-passage.';

    private $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function index()
    {
        $quizPassage = $this->quizService->getALlQuizPassages();
        return View(Parent::loadViewData($this->module . $this->view.'index'),
            compact('quizPassage')
        );
    }

    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    public function store(
        QuizPassageStoreRequest $passageRequest,
        QuizQuestionStoreRequest $questionRequest,
        QuizDateRequest $dateRequest
    )
    {
        try{
            $validatedPassageData = $passageRequest->validated();
            $validatedQuizDate = $dateRequest->validated();
            $validatedQuestion = $questionRequest->validated();
            $quizPassage = $this->quizService->storeQuizPassageDetail(
                $validatedPassageData,$validatedQuizDate,$validatedQuestion
            );
            return redirect()->route('admin.quiz.passage.index')
                ->with('success',$this->title . ':  Created Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage())->withInput();
        }
    }

    public function show($qp_code)
    {
        try{
            $with = ['quizDates','quizQuestions'];
            $passageDetail = $this->quizService
                ->findPassageDetailByCode($qp_code,$with);
            return View(Parent::loadViewData($this->module . $this->view.'show'),
                compact('passageDetail')
            );
        }catch(Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }

    public function deleteQuizPassageAlongWithQuestions($qp_code)
    {
        try{
            $quizPassageDetail = $this->quizService->findPassageDetailByCode($qp_code);
            $deleteStatus = $this->quizService->deletePassageDetailAlongWithQuestions($quizPassageDetail);
            return redirect()->route('admin.quiz.passage.index')
                ->with('success',$this->title . ':  deleted Successfully');
        }catch (Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }

    public function toggleQuizPassageIsActiveStatus($qp_code)
    {
        try{
            $quizPassage = $this->quizService->findPassageDetailByCode($qp_code);
            $status = $this->quizService->togglePassageStatus($quizPassage);
            return redirect()->route('admin.quiz.passage.index')
                ->with('success',$this->title . ':  Status changed Successfully');
        }catch(Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }

    public function edit($qp_code)
    {
        try{
            $with = ['quizDates'];
            $quizDates = [];
            $quizPassageDetail = $this->quizService->findPassageDetailByCode($qp_code,$with);
            foreach($quizPassageDetail->quizDates as $key =>$value){
                $quizDates[] = $value['quiz_passage_date'];
            }
            $quizDates = implode(',',$quizDates);
            return View(Parent::loadViewData($this->module . $this->view.'edit'),
                compact('quizPassageDetail','quizDates')
            );
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function update(QuizPassageUpdateRequest $passageRequest,
                           QuizDateUpdateRequest $dateRequest,
                           $qpd_code)
    {
        try{
            $validatedPassageData = $passageRequest->validated();
            $validatedQuizDate = $dateRequest->validated();
            $quizPassage = $this->quizService->updateQuizPassageDetail($validatedPassageData,$validatedQuizDate,$qpd_code);

            return redirect()->route('admin.quiz.passage.index')
                ->with('success',$this->title . ':  updated Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}
