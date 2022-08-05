<?php


namespace App\Modules\QuizGame\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\QuizGame\Helpers\QuizGameParticipatorHelper;
use App\Modules\QuizGame\Models\QuizParticipator;
use App\Modules\QuizGame\Requests\QuizParticipator\QuizParticipatorChangeStatusRequest;
use App\Modules\QuizGame\Services\QuizParticipatorService;
use App\Modules\QuizGame\Services\QuizSubmissionService;
use App\Modules\QuizGame\Services\QuizSubmittedDetailService;
use Exception;
use Illuminate\Http\Request;

class QuizParticipatorDetailController extends BaseController
{
    public $title = 'Quiz Game Participator';
    public $base_route = 'admin.quiz.participator';
    public $sub_icon = 'file';
    public $module = 'QuizGame::';
    public $view = 'quiz-participator.';

    public $quizParticipatorService;
    public $quizSubmissionService;
    public $quizSubmittedDetailService;

    public function __construct(QuizParticipatorService $quizParticipatorService,
                                QuizSubmissionService $quizSubmissionService,
                                QuizSubmittedDetailService $quizSubmittedDetailService
    )
    {
        $this->quizParticipatorService = $quizParticipatorService;
        $this->quizSubmissionService = $quizSubmissionService;
        $this->quizSubmittedDetailService = $quizSubmittedDetailService;
    }

    public function index(Request $request)
    {
        $filterParameters = [
            'recharge_phone_no' =>$request->get('recharge_phone_no'),
            'status' => $request->get('status'),
            'participation_from' => $request->get('participation_from'),
            'participation_to' => $request->get('participation_to'),
            'store_name' => $request->get('store_name'),
            'participator_type'=> $request->get('participator_type'),
        ];
        $status = QuizParticipator::STATUS;
        $quizParticipator = QuizGameParticipatorHelper::getAllParticipatorByFilter($filterParameters);
        return View(Parent::loadViewData($this->module . $this->view.'index'),
            compact('quizParticipator','filterParameters','status')
        );
    }

    public function showParticipatorDetail($qpd_code)
    {
        try{
            $status = QuizParticipator::STATUS;
            $quizParticipatorDetail = $this->quizParticipatorService->findQuizParticipatorDetailByCode($qpd_code);
            return View(Parent::loadViewData($this->module . $this->view.'show'),
                compact('quizParticipatorDetail','status')
            );
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function changeParticipatorStatus(QuizParticipatorChangeStatusRequest $request,$qpd_code)
    {
        try{
            $validatedData = $request->validated();
            $participatorDetail = $this->quizParticipatorService->findQuizParticipatorDetailByCode($qpd_code);
            $changeStatus = $this->quizParticipatorService->changeStatus($validatedData,$participatorDetail);
            return redirect()->back()
                ->with('success','Status Chnaged to '. $validatedData['status']. ' Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage())->withInput();
        }
    }

    public function delete($qpd_code)
    {
        try{
            $participatorDetail = $this->quizParticipatorService->findQuizParticipatorDetailByCode($qpd_code);
            $this->quizParticipatorService->deleteParticipatorDetial($participatorDetail);
            return redirect()->back()->with('success','Participator Detail Deleted Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage())->withInput();
        }
    }

    public function getAllQuizByParticipatorCode($participatorCode)
    {
        try{
            $with = ['quizPassage:qp_code,passage_title'];
            $select = ['qp_code','quiz_submission_code','submitted_date'];
            $participatorQuizDetail = $this->quizSubmissionService
                ->getAllQuizDetailByParticipatorCode($participatorCode,$select,$with);
            return View(Parent::loadViewData($this->module . $this->view.'show-participator-quiz'),
                compact('participatorQuizDetail')
            );
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function getSubmittedQuizDetailByQSCode($quizSubmittedCode)
    {
        try{
            $select = ['question','answer'];
            $submittedQuizDetail = $this->quizSubmittedDetailService->getAllSubmittedQuizDetailByQSCode($quizSubmittedCode);
            return View(Parent::loadViewData($this->module . $this->view.'quiz-submitted-detail'),
                compact('submittedQuizDetail')
            );
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}
