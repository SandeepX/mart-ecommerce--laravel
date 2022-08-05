<?php

namespace App\Modules\Career\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Career\Requests\JobQuestionStoreRequest;
use App\Modules\Career\Requests\JobQuestionUpdateRequest;
use App\Modules\Career\Services\JobQuestionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Exception;

class JobQuestionController extends BaseController
{
    public $title = 'Job Question';
    public $base_route = 'admin.job-questions';
    public $sub_icon = 'file';
    public $module = 'Career::';


    private $view;
    private $jobQuestionService;

    public function __construct(JobQuestionService $jobQuestionService)
    {
        $this->view = 'admin.job-question.';
        $this->jobQuestionService = $jobQuestionService;

    }


    public function index()
    {
        $jobQuestions = $this->jobQuestionService->getAllJobQuestions();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('jobQuestions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        try{
            return view(Parent::loadViewData($this->module.$this->view.'create'));
        }catch (Exception $e){
            return redirect()->route($this->base_route.'.index')->with('danger', $e->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(JobQuestionStoreRequest $request)
    {
        $validated = $request->validated();
        try{
            $this->jobQuestionService->saveJobQuestion($validated);
            return redirect()->back()->with('success', $this->title .' Created Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


    public function edit($questionCode)
    {
        try{
            $jobQuestion = $this->jobQuestionService->findOrFailJobQuestionByCode($questionCode);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('jobQuestion'));

        }catch (Exception $ex){
            return redirect()->route($this->base_route.'.index')->with('danger',$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(JobQuestionUpdateRequest $request,$questionCode)
    {
        $validated = $request->validated();

        try{
            $this->jobQuestionService->updateJobQuestion($validated,$questionCode);
            return redirect()->back()->with('success', $this->title .' Updated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($questionCode)
    {
        try{
            $jobQuestion = $this->jobQuestionService->deleteJobQuestion($questionCode);
            return redirect()->back()->with('success', $this->title . ': '. $jobQuestion->question_code .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
