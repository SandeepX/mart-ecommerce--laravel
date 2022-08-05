<?php

namespace App\Modules\Vendor\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Vendor\Requests\VendorDocumentCreateRequest;
use App\Modules\Vendor\Services\VendorDocumentService;
use App\Modules\Vendor\Services\VendorService;
use Exception;

class VendorDocumentController extends BaseController
{
    
    public $title = 'Vendor Document';
    public $base_route = 'admin.vendors.documents';
    public $sub_icon = 'file';
    public $module = 'Vendor::';

    private $view;
    protected $vendorDocumentService, $vendorService;

    public function __construct(VendorDocumentService $vendorDocumentService, VendorService $vendorService)
    {
        $this->view = 'admin.vendor-document.';
        $this->vendorDocumentService = $vendorDocumentService;
        $this->vendorService = $vendorService;
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('Vendor::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create($vendorSlug)
    {
        try{
            $vendor = $this->vendorService->findOrFailVendorBySlug($vendorSlug);
            $vendorDocuments = $this->vendorDocumentService->getAllDocuments($vendor);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('vendor','vendorDocuments'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store($vendorSlug, VendorDocumentCreateRequest $request)
    {

        $validated = $request->validated();
        try{
            $vendor = $this->vendorService->findOrFailVendorBySlug($vendorSlug);
            $this->vendorDocumentService->storeVendorDocuments($validated, $vendor);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->route($this->base_route.'.create', $vendor->slug)->with('success', 'Document for Vendor: '. $vendor->vendor_name .' Created Successfully');
    }

 

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($vendorSlug, $vendorDocumentId)
    {
        try{
            $vendor = $this->vendorService->findOrFailVendorBySlug($vendorSlug);
            $this->vendorDocumentService->deleteVendorDocument($vendor,$vendorDocumentId);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->route($this->base_route.'.create', $vendor->slug)->with('success', 'Document for Vendor: '. $vendor->vendor_name .' Deleted Successfully');
    }
}
