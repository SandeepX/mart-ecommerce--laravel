<?php

namespace App\Modules\Types\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Services\CancellationParamService;
use App\Modules\Types\Requests\CancellationParam\CancellationParamCreateRequest;
use App\Modules\Types\Requests\CancellationParam\CancellationParamUpdateRequest;

class CancellationParamController extends BaseController
{
    public $title = 'Cancellation Parameter';
    public $base_route = 'admin.cancellation-params';
    public $sub_icon = 'file';
    public $module = 'Types::';


    private $view;
    private $cancellationParamService;


    public function __construct(CancellationParamService $cancellationParamService)
    {
        $this->middleware('permission:View Cancellation Parameter List', ['only' => ['index']]);
        $this->middleware('permission:Create Cancellation Parameter', ['only' => ['create','store']]);
        $this->middleware('permission:Show Cancellation Parameter', ['only' => ['show']]);
        $this->middleware('permission:Update Cancellation Parameter', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Cancellation Parameter', ['only' => ['destroy']]);

        $this->view = 'admin.cancellation-params.';
        $this->cancellationParamService = $cancellationParamService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cancellationParams = $this->cancellationParamService->getAllCancellationParams();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('cancellationParams'));
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
    public function store(CancellationParamCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $cancellationParam =  $this->cancellationParamService->storeCancellationParam($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $cancellationParam->cancellation_name .' Created Successfully');
    }

   


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($cancellationParamCode)
    {
        try{
            $cancellationParam = $this->cancellationParamService->findOrFailCancellationParamByCode($cancellationParamCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('cancellationParam'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CancellationParamUpdateRequest $request,$cancellationParamCode)
    {
        $validated = $request->validated();
        try{
            $cancellationParam = $this->cancellationParamService->updateCancellationParam($validated, $cancellationParamCode);
            return redirect()->back()->with('success', $this->title . ': '. $cancellationParam->cancellation_name .' Updated Successfully');
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
    public function destroy($cancellationParamCode)
    {
        try{
            $cancellationParam = $this->cancellationParamService->deleteCancellationParam($cancellationParamCode);
            return redirect()->back()->with('success', $this->title . ': '. $cancellationParam->cancellation_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
