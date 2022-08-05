<?php

namespace App\Modules\Types\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Requests\RegistrationType\RegistrationTypeCreateRequest;
use App\Modules\Types\Requests\RegistrationType\RegistrationTypeUpdateRequest;
use App\Modules\Types\Services\RegistrationTypeService;

class RegistrationTypeController extends BaseController
{
    public $title = 'Registration Type';
    public $base_route = 'admin.registration-types';
    public $sub_icon = 'file';
    public $module = 'Types::';


    private $view;
    private $registrationTypeService;


    public function __construct(RegistrationTypeService $registrationTypeService)
    {
        $this->middleware('permission:View Registration Type List', ['only' => ['index']]);
        $this->middleware('permission:Create Registration Type', ['only' => ['create','store']]);
        $this->middleware('permission:Show Registration Type', ['only' => ['show']]);
        $this->middleware('permission:Update Registration Type', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Registration Type', ['only' => ['destroy']]);

        $this->view = 'admin.registration-types.';
        $this->registrationTypeService = $registrationTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $registrationTypes = $this->registrationTypeService->getAllRegistrationTypes();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('registrationTypes'));
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
    public function store(RegistrationTypeCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $registrationType =  $this->registrationTypeService->storeRegistrationType($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $registrationType->registration_type_name .' Created Successfully');
    }

   


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($registrationTypeCode)
    {
        try{
            $registrationType = $this->registrationTypeService->findOrFailRegistrationTypeByCode($registrationTypeCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('registrationType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RegistrationTypeUpdateRequest $request,$registrationTypeCode)
    {
        $validated = $request->validated();
        try{
            $registrationType = $this->registrationTypeService->updateRegistrationType($validated, $registrationTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $registrationType->registration_type_name .' Updated Successfully');
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
    public function destroy($registrationTypeCode)
    {
        try{
            $registrationType = $this->registrationTypeService->deleteRegistrationType($registrationTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $registrationType->registration_type_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
