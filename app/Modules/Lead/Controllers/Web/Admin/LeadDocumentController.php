<?php

namespace App\Modules\Lead\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Lead\Requests\LeadDocument\LeadDocumentCreateRequest;
use App\Modules\Lead\Services\LeadDocumentService;
use App\Modules\Lead\Services\LeadService;
use Exception;

class LeadDocumentController extends BaseController
{
    
    public $title = 'Lead Document';
    public $base_route = 'admin.leads.documents';
    public $sub_icon = 'file';
    public $module = 'Lead::';

    private $view;
    protected $leadDocumentService, $leadService;

    public function __construct(LeadDocumentService $leadDocumentService, LeadService $leadService)
    {
        $this->view = 'admin.lead-document.';
        $this->leadDocumentService = $leadDocumentService;
        $this->leadService = $leadService;
    }
    

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create($leadCode)
    {
       try{
            $lead = $this->leadService->findOrFailLeadByCode($leadCode);
            $leadDocuments = $this->leadDocumentService->getAllDocuments($lead);
            $leadDocumentTypes = $this->leadDocumentService->getDocumentTypeOptions();
            $documentTypeSelectHtmlPartial = view($this->module.$this->view.'partials.document-type-select',compact('leadDocumentTypes'))->render();
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('lead','leadDocuments','documentTypeSelectHtmlPartial'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(LeadDocumentCreateRequest $request,$leadCode)
    {   
        $validated = $request->validated();
        try{
            $lead = $this->leadService->findOrFailLeadByCode($leadCode);
            $this->leadDocumentService->storeleadDocuments($validated, $lead);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Document for Lead: '. $lead->lead_name .' Created Successfully');
    }

 

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($leadCode, $leadDocumentID)
    {
        try{
             $leadDocument = $this->leadDocumentService->deleteLeadDocument($leadCode,$leadDocumentID);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Document for Lead: '. $leadDocument->lead->lead_name .' Deleted Successfully');
    }
}
