<?php

namespace App\Modules\Types\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Requests\VendorType\VendorTypeCreateRequest;
use App\Modules\Types\Requests\VendorType\VendorTypeUpdateRequest;
use App\Modules\Types\Services\VendorTypeService;


class VendorTypeController extends BaseController
{
    public $title = 'Vendor Type';
    public $base_route = 'admin.vendor-types';
    public $sub_icon = 'file';
    public $module = 'Types::';


    private $view;
    private $vendorTypeService;


    public function __construct(VendorTypeService $vendorTypeService)
    {
        $this->middleware('permission:View Vendor Type List', ['only' => ['index']]);
        $this->middleware('permission:Create Vendor Type', ['only' => ['create','store']]);
        $this->middleware('permission:Show Vendor Type', ['only' => ['show']]);
        $this->middleware('permission:Update Vendor Type', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Vendor Type', ['only' => ['destroy']]);

        $this->view = 'admin.vendor-types.';
        $this->vendorTypeService = $vendorTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendorTypes = $this->vendorTypeService->getAllVendorTypes();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('vendorTypes'));
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorTypeCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $vendorType =  $this->vendorTypeService->storeVendorType($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $vendorType->vendor_type_name .' Created Successfully');
    }

   


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($vendorTypeCode)
    {
        try{
            $vendorType = $this->vendorTypeService->findOrFailVendorTypeByCode($vendorTypeCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('vendorType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VendorTypeUpdateRequest $request,$vendorTypeCode)
    {
        $validated = $request->validated();
        try{
            $vendorType = $this->vendorTypeService->updateVendorType($validated, $vendorTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $vendorType->vendor_type_name .' Updated Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($vendorTypeCode)
    {
        try{
            $vendorType = $this->vendorTypeService->deleteVendorType($vendorTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $vendorType->vendor_type_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
