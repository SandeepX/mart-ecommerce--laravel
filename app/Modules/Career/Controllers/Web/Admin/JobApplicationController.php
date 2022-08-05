<?php

namespace App\Modules\Career\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Career\Services\JobApplicationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Exception;

class JobApplicationController extends BaseController
{

    public $title = 'Job Application';
    public $base_route = 'admin.job-applications.';
    public $sub_icon = 'file';
    public $module = 'Career::';

    private $view='admin.job-application.';

    private $jobApplicationService;

    public function __construct(JobApplicationService $jobApplicationService){
        $this->jobApplicationService = $jobApplicationService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $array1 = array(
         1,2,3,4,5
        );

        $array2 = array(
          1,2,3,4
        );

        $diff = array_diff($array1, $array2);

        dd($diff);

        $jobApplications = $this->jobApplicationService->getAllJobApplicationsWith(['jobOpening']);
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('jobApplications'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('Career::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($jobApplicationCode)
    {

        try{
            $jobApplication = $this->jobApplicationService->findOrFailJobApplicationByCodeWithEager($jobApplicationCode);
            $otherContacts = json_decode($jobApplication->other_contacts);

            $jobApplicationDocuments = $jobApplication->applicationDocuments;

            $jobApplicationAnswers = $jobApplication->answers;

            return view(Parent::loadViewData($this->module.$this->view.'show'),compact('jobApplication',
                'otherContacts','jobApplicationDocuments','jobApplicationAnswers'));

        }catch (Exception $ex){
            return redirect()->route($this->base_route.'index')->with('danger',$ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('Career::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
