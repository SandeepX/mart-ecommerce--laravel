<?php

namespace App\Modules\Types\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Requests\CompanyType\CompanyTypeCreateRequest;
use App\Modules\Types\Requests\CompanyType\CompanyTypeUpdateRequest;
use App\Modules\Types\Services\CompanyTypeService;


class CompanyTypeController extends BaseController
{
    public $title = 'Company Type';
    public $base_route = 'admin.company-types';
    public $sub_icon = 'file';
    public $module = 'Types::';


    private $view;
    private $companyTypeService;


    public function __construct(CompanyTypeService $companyTypeService)
    {
        $this->middleware('permission:View Company Type List', ['only' => ['index']]);
        $this->middleware('permission:Create Company Type', ['only' => ['create','store']]);
        $this->middleware('permission:Show Company Type', ['only' => ['show']]);
        $this->middleware('permission:Update Company Type', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Company Type', ['only' => ['destroy']]);

        $this->view = 'admin.company-types.';
        $this->companyTypeService = $companyTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companyTypes = $this->companyTypeService->getAllCompanyTypes();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('companyTypes'));
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
    public function store(CompanyTypeCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $companyType =  $this->companyTypeService->storeCompanyType($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $companyType->company_type_name .' Created Successfully');
    }

   


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($companyTypeCode)
    {
        try{
            $companyType = $this->companyTypeService->findOrFailCompanyTypeByCode($companyTypeCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('companyType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyTypeUpdateRequest $request,$companyTypeCode)
    {
        $validated = $request->validated();
        try{
            $companyType = $this->companyTypeService->updateCompanyType($validated, $companyTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $companyType->company_type_name .' Updated Successfully');
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
    public function destroy($companyTypeCode)
    {
        try{
            $companyType = $this->companyTypeService->deleteCompanyType($companyTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $companyType->company_type_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
