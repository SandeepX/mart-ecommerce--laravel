<?php

namespace App\Modules\Questionnaire\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Questionnaire\Models\ActionVerificationQuestionnaire;
use App\Modules\Questionnaire\Requests\ActionVerificationQuestionCreateRequest;
use App\Modules\Questionnaire\Requests\ActionVerificationQuestionUpdateRequest;
use App\Modules\Questionnaire\Services\VerificationQuestionnaireService;
use Illuminate\Support\Facades\DB;
use Exception;

class VerificationQuestionnaireController extends BaseController
{
    public $title = 'Verification Questions';
    public $base_route = 'admin.verification-questions';
    public $sub_icon = 'file';
    public $module = 'Questionnaire::';
    private $view = 'verification-questions.';

    private $verificationQuestionnaireService;

    public function __construct(VerificationQuestionnaireService $verificationQuestionnaireService)
    {
       $this->verificationQuestionnaireService =  $verificationQuestionnaireService;
    }

    public function index(){
        try{
            $verificationQuestions = $this->verificationQuestionnaireService->getAllVerificationQuestions();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('verificationQuestions'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function create(){
        try{
            $entities = ActionVerificationQuestionnaire::entity;
            $actions = ActionVerificationQuestionnaire::action;
            return view(Parent::loadViewData($this->module.$this->view.'create'),compact('entities','actions'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function store(ActionVerificationQuestionCreateRequest $actionVerificationQuestionCreateRequest){
        try{
            $validatedData = $actionVerificationQuestionCreateRequest->validated();
            DB::beginTransaction();
            $actionVerificationQuestion = $this->verificationQuestionnaireService->storeActionVerificationQuestions($validatedData);
            DB::commit();
            return redirect()->back()->with('success', $this->title . ':  Created Successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger',$exception->getMessage())->withInput();
        }
    }

    public function edit($avqCode){
        try{
            $actionVerificationQuestion = $this->verificationQuestionnaireService->findOrFailByActionVerificationQuestionCode($avqCode);
            $entities = ActionVerificationQuestionnaire::entity;
            $actions = ActionVerificationQuestionnaire::action;
            //dd($actionVerificationQuestion);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('entities','actions','actionVerificationQuestion'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function update(
        ActionVerificationQuestionUpdateRequest $actionVerificationQuestionUpdateRequest,
        $avqCode
    ){
        try{
            $validatedData = $actionVerificationQuestionUpdateRequest->validated();
            DB::beginTransaction();
            $actionVerificationQuestion = $this->verificationQuestionnaireService->updateActionVerificationQuestion($avqCode,$validatedData);
            DB::commit();
            return redirect()->route('admin.verification-questions.index')->with('success', $this->title . ':  Updated Successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function destroy($avqCode)
    {
        try{
            $this->verificationQuestionnaireService->deleteActionVerificationQuestions($avqCode);
            return redirect()->back()->with('success', ' Action Verification Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }




}
