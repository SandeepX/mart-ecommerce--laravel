<?php

namespace App\Modules\Package\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Package\Requests\PackageTypeCreateRequest;
use App\Modules\Package\Requests\PackageTypeUpdateRequest;
use App\Modules\Package\Services\PackageTypeService;

class PackageTypeController extends BaseController
{

    public $title = 'Package';
    public $base_route = 'admin.package-types';
    public $sub_icon = 'file';
    public $module = 'Package::';


    private $view;
    private $packageTypeService;

    public function __construct(PackageTypeService $packageTypeService)
    {
        $this->middleware('permission:View Package Type List', ['only' => ['index']]);
        $this->middleware('permission:Create Package Type', ['only' => ['create','store']]);
        $this->middleware('permission:Show Package Type', ['only' => ['show']]);
        $this->middleware('permission:Update Package Type', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Package Type', ['only' => ['destroy']]);

        $this->view = 'admin.';
        $this->packageTypeService = $packageTypeService;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $packageTypes = $this->packageTypeService->getAllPackageTypes();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('packageTypes'));
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
    public function store(PackageTypeCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $packageType =  $this->packageTypeService->storePackageType($validated);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $packageType->package_name .' Package Type Created Successfully');
    }

//    /**
//     * Show the specified resource.
//     * @return Response
//     */
//    public function show($packageTypeCode)
//    {
//        return view('package::show');
//    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($packageTypeCode)
    {
        $packageType = $this->packageTypeService->findOrFailPackageTypeByCode($packageTypeCode);
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('packageType'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(PackageTypeUpdateRequest $request, $packageTypeCode)
    {

        $validated = $request->validated();
        try{
            $packageType = $this->packageTypeService->updatePackageType($validated, $packageTypeCode);
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $packageType->package_name .' Package Type Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($packageTypeCode)
    {
        try{
            $packageType = $this->packageTypeService->deletePackageType($packageTypeCode);

        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $packageType->package_name .' Package Type Trashed Successfully');
    }
}