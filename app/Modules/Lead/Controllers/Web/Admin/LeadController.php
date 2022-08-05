<?php

namespace App\Modules\Lead\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Lead\Requests\LeadCreateRequest;
use App\Modules\Lead\Requests\LeadUpdateRequest;
use App\Modules\Lead\Services\LeadService;
use App\Modules\Location\Services\LocationHierarchyService;
use Symfony\Component\HttpFoundation\Request;

class LeadController extends BaseController
{

    public $title = 'Lead';
    public $base_route = 'admin.leads';
    public $sub_icon = 'file';
    public $module = 'Lead::';


    private $view;

    private $leadService, $locationHierarchyService;




    public function __construct(
        LeadService $leadService,
        LocationHierarchyService $locationHierarchyService
    ) {
        $this->view = 'admin.';
        $this->leadService = $leadService;
        $this->locationHierarchyService = $locationHierarchyService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $leads = $this->leadService->getAllLeads();
        return view(Parent::loadViewData($this->module . $this->view . 'index'), compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
        return view(Parent::loadViewData($this->module . $this->view . 'create'), compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(LeadCreateRequest $request)
    {
    
        $validated = $request->validated();
        try {
            $lead = $this->leadService->storeLeadDetails($validated);
        
        } catch (\Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
        return sendSuccessResponse($this->title . ': ' . $lead->lead_name . ' Created Successfully',[
            'lead_code' => $lead->lead_code
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    { }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($leadCode)
    {
        try {
            $lead = $this->leadService->findOrFailLeadByCode($leadCode);
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');

            $locationPath = $this->locationHierarchyService->getLocationPath(
                $lead->lead_location_code,
                ['ward.municipality.district.province.country']
            );

        } catch (\Exception $ex) {
            return redirect()->back()->with('danger', $ex->getMessage());
        }
        return view(Parent::loadViewData($this->module . $this->view . 'edit'), compact('lead', 'provinces', 'locationPath'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(LeadUpdateRequest $request, $leadCode)
    {
        $validated = $request->validated();
        try {
            $lead = $this->leadService->updateLeadDetails($validated, $leadCode);
        } catch (\Exception $exception) {

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        return redirect()->back()->with('success', $this->title . ': ' . $lead->lead_name . ' Updated Successfully')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($leadCode)
    {
        try {
            $lead = $this->leadService->deleteLeadDetails($leadCode);
            return redirect()->back()->with('success', $this->title . ': ' . $lead->lead_name . ' Trashed Successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
