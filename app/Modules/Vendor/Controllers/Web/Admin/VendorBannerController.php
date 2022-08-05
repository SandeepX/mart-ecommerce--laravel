<?php

namespace App\Modules\Vendor\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Vendor\Requests\VendorBannerCreateRequest;
use App\Modules\Vendor\Services\VendorBannerService;
use App\Modules\Vendor\Services\VendorService;
use Exception;

class VendorBannerController extends BaseController
{
    
    public $title = 'Vendor Banner';
    public $base_route = 'admin.vendors.banners';
    public $sub_icon = 'file';
    public $module = 'Vendor::';

    private $view;
    protected $vendorBannerService, $vendorService;

    public function __construct(VendorBannerService $vendorBannerService, VendorService $vendorService)
    {
        $this->view = 'admin.vendor-banner.';
        $this->vendorBannerService = $vendorBannerService;
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
            $vendorBanners = $this->vendorBannerService->getAllBanners($vendor);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'create'),compact('vendor','vendorBanners'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store($vendorSlug, VendorBannerCreateRequest $request)
    {   
        $validated = $request->validated();
        try{
            $vendor = $this->vendorService->findOrFailVendorBySlug($vendorSlug);
            $this->vendorBannerService->storeVendorBanners($validated, $vendor);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->route($this->base_route.'.create', $vendor->slug)->with('success', 'Banner for Vendor: '. $vendor->vendor_name .' Created Successfully');
    }

    public function changeStatus($vendorSlug, $vendorBannerName)
    {   
        try{
            $vendor = $this->vendorService->findOrFailVendorBySlug($vendorSlug);
            $this->vendorBannerService->changeBannerStatus($vendorBannerName);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->route($this->base_route.'.create', $vendor->slug)->with('success', 'Action Completed Successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('Vendor::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('Vendor::edit');
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
    public function destroy($vendorSlug, $vendorBannerName)
    {
        try{
            $vendor = $this->vendorService->findOrFailVendorBySlug($vendorSlug);
            $this->vendorBannerService->deleteVendorBanner($vendorBannerName);
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->route($this->base_route.'.create', $vendor->slug)->with('success', 'Banner for Vendor: '. $vendor->vendor_name .' Deleted Successfully');
    }
}
