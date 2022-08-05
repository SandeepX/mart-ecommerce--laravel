<?php

namespace App\Modules\Career\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Career\Requests\JobOpeningStoreRequest;
use App\Modules\Career\Requests\JobOpeningUpdateRequest;
use App\Modules\Career\Services\JobOpeningService;
use App\Modules\Career\Services\JobQuestionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Exception;

class JobOpeningController extends BaseController
{
    public $title = 'Job Opening';
    public $base_route = 'admin.job-openings.';
    public $sub_icon = 'file';
    public $module = 'Career::';

    private $view='admin.job-opening.';

    private $jobOpeningService,$jobQuestionService;

    public function __construct(JobOpeningService $jobOpeningService,JobQuestionService $jobQuestionService){
        $this->jobOpeningService = $jobOpeningService;
        $this->jobQuestionService = $jobQuestionService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $jobOpenings = $this->jobOpeningService->getAllJobOpenings();

        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('jobOpenings'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        try{
            $jobTypes = $this->jobOpeningService->getAllJobTypes();
            $jobQuestions = $this->jobQuestionService->getActiveJobQuestions();

            return view(Parent::loadViewData($this->module.$this->view.'create'),compact('jobTypes','jobQuestions'));
        }catch (Exception $e){
            return redirect()->route($this->base_route.'index')->with('danger', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(JobOpeningStoreRequest $request)
    {
        try{
            $validated = $request->validated();
            $this->jobOpeningService->saveJobOpening($validated);
            return redirect()->back()->with('success', $this->title .' Created Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }


    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return redirect()->route($this->base_route.'index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($jobOpeningCode)
    {
        try{
            $jobTypes = $this->jobOpeningService->getAllJobTypes();
            $jobQuestions = $this->jobQuestionService->getActiveJobQuestions();

            $jobOpening = $this->jobOpeningService->findOrFailJobOpeningByCodeWithEager($jobOpeningCode);

            $jobOpeningQuestionsCode = $jobOpening->jobQuestions->pluck('pivot.priority','question_code')->toArray();

            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('jobOpening',
                'jobTypes','jobQuestions','jobOpeningQuestionsCode'));

        }catch (Exception $ex){
            return redirect()->route($this->base_route.'index')->with('danger',$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(JobOpeningUpdateRequest $request,$openingCode)
    {
        $validated = $request->validated();
        try{
            $this->jobOpeningService->updateJobOpening($validated,$openingCode);
            return redirect()->back()->with('success', $this->title .' Updated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($jobOpeningCode)
    {
        try{
            $jobOpening = $this->jobOpeningService->deleteJobOpening($jobOpeningCode);
            return redirect()->back()->with('success', $this->title . ': '. $jobOpening->opening_code .' Trashed Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
