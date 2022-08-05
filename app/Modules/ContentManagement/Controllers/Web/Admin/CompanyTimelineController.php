<?php

namespace App\Modules\ContentManagement\Controllers\Web\Admin;

use App\Modules\ContentManagement\Requests\CompanyTimelineCreateRequest;
use App\Modules\ContentManagement\Requests\CompanyTimelineUpdateRequest;
use App\Modules\ContentManagement\Services\CompanyTimelineService;
use App\Modules\Application\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CompanyTimelineController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public $title = 'Company Timeline';
    public $base_route = 'admin.company-timeline';
    public $sub_icon = 'file';
    public $module = 'ContentManagement::';
    public $view = 'admin.company-timeline.';

    private $companyTimelineService;
    public function __construct(CompanyTimelineService $companyTimelineService){
        $this->companyTimelineService =$companyTimelineService;
    }
    public function index()
    {
        try{
            $companyTimelines = $this->companyTimelineService->getAllCompanyTimeline();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('companyTimelines'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view(Parent::loadViewData($this->module.$this->view.'create'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CompanyTimelineCreateRequest $request)
    {

        $validatedData = $request->validated();

        try{
            $this->companyTimelineService->storeCompanyTimeline($validatedData);
            return redirect()->back()->with('success', 'Company Timeline Created Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($companyTimelineCode)
    {
        $companyTimeline = $this->companyTimelineService->findOrFailCompanyTimelineByCode($companyTimelineCode);
        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('companyTimeline'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($companyTimelineCode)
    {

        try{
            $companyTimeline = $this->companyTimelineService->findOrFailCompanyTimelineByCode($companyTimelineCode);
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('companyTimeline'));
        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(CompanyTimelineUpdateRequest $request, $companyTimelineCode)
    {
        $validatedData = $request->validated();
        try{
            $this->companyTimelineService->updateCompanyTimeline($validatedData,$companyTimelineCode);
            return redirect()->back()->with('success', 'Company Timeline Updated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($companyTimelineCode)
    {
        try{
            $companyTimeline = $this->companyTimelineService->deleteVisionMission($companyTimelineCode);
            return redirect()->back()->with('success', $this->title .'Company Timeline Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
