<?php

namespace App\Modules\Types\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Services\RejectionParamService;
use App\Modules\Types\Requests\RejectionParam\RejectionParamCreateRequest;
use App\Modules\Types\Requests\RejectionParam\RejectionParamUpdateRequest;

class RejectionParamController extends BaseController
{
    public $title = 'Rejection Parameter';
    public $base_route = 'admin.rejection-params';
    public $sub_icon = 'file';
    public $module = 'Types::';


    private $view;
    private $rejectionParamService;


    public function __construct(RejectionParamService $rejectionParamService)
    {
        $this->middleware('permission:View Rejection Parameter List', ['only' => ['index']]);
        $this->middleware('permission:Create Rejection Parameter', ['only' => ['create','store']]);
        $this->middleware('permission:Show Rejection Parameter', ['only' => ['show']]);
        $this->middleware('permission:Update Rejection Parameter', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Rejection Parameter', ['only' => ['destroy']]);

        $this->view = 'admin.rejection-params.';
        $this->rejectionParamService = $rejectionParamService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rejectionParams = $this->rejectionParamService->getAllRejectionParams();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('rejectionParams'));
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
    public function store(RejectionParamCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $rejectionParam =  $this->rejectionParamService->storeRejectionParam($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $rejectionParam->rejection_name .' Created Successfully');
    }

   


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($rejectionParamCode)
    {
        try{
            $rejectionParam = $this->rejectionParamService->findOrFailRejectionParamByCode($rejectionParamCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('rejectionParam'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RejectionParamUpdateRequest $request,$rejectionParamCode)
    {
        $validated = $request->validated();
        try{
            $rejectionParam = $this->rejectionParamService->updateRejectionParam($validated, $rejectionParamCode);
            return redirect()->back()->with('success', $this->title . ': '. $rejectionParam->rejection_name .' Updated Successfully');
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
    public function destroy($rejectionParamCode)
    {
        try{
            $rejectionParam = $this->rejectionParamService->deleteRejectionParam($rejectionParamCode);
            return redirect()->back()->with('success', $this->title . ': '. $rejectionParam->rejection_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
