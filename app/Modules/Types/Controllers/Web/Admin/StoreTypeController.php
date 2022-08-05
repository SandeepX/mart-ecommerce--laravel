<?php

namespace App\Modules\Types\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Requests\StoreType\StoreSizeCreateRequest;
use App\Modules\Types\Requests\StoreType\StoreTypeCreateRequest;
use App\Modules\Types\Requests\StoreType\StoreTypeUpdateRequest;
use App\Modules\Types\Services\StoreTypeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class StoreTypeController extends BaseController
{
    public $title = 'Store Types';
    public $base_route = 'admin.store-types';
    public $sub_icon = 'file';
    public $module = 'Types::';


    private $view;
    private $storeTypeService;
    public function __construct(StoreTypeService $storeTypeService)
    {
        $this->middleware('permission:View Store Type List', ['only' => ['index']]);
        $this->middleware('permission:Create Store Type', ['only' => ['create','store']]);
        $this->middleware('permission:Show Store Type', ['only' => ['show']]);
        $this->middleware('permission:Update Store Type', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Store Type', ['only' => ['destroy']]);
        $this->middleware('permission:Change Store Type Status', ['only' => ['toggleStatus']]);

        $this->view = 'admin.store-types.';
        $this->storeTypeService = $storeTypeService;
    }




    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $storeTypes = $this->storeTypeService->getAllStoreTypes();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('storeTypes'));
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
    public function store(StoreTypeCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $storeType =  $this->storeTypeService->storeStoreType($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $storeType->store_type_name .' Created Successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('Types::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($storeTypeCode)
    {
        try{
            $storeType = $this->storeTypeService->findOrFailStoreTypeByCode($storeTypeCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('storeType'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(StoreTypeUpdateRequest $request,$storeTypeCode)
    {
        $validated = $request->validated();
        try{
            $storeType= $this->storeTypeService->updateStoreType($validated, $storeTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $storeType->store_type_name .' Updated Successfully');
        }catch (\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($storeTypeCode)
    {
        try{
            $storeType = $this->storeTypeService->deleteStoreType($storeTypeCode);
            return redirect()->back()->with('success', $this->title . ': '. $storeType->store_type_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleStatus($storeTypeCode,$status){

//        try{
//            $this->storeTypeService->updateActiveStatus($storeTypeCode);
//            return redirect()->back()->with('success', $this->title .' status updated successfully');
//        }catch (\Exception $exception){
//            return redirect()->back()->with('danger', $exception->getMessage());
//        }
        try{
            $updateStatus = $this->storeTypeService->changeStoreTypeStatus($storeTypeCode,$status);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
          }
    }

    public function changeStoreTypeDisplayOrder(Request $request,$storeTypeCode){
        try{
            $sortOrdersToChange = $request->sort_order;
            $updateStatus = $this->storeTypeService->changeStoreTypeDisplayOrder($storeTypeCode,$sortOrdersToChange);
            return sendSuccessResponse('Display Order Updated');
        }catch(\Exception $exception){
            return sendErrorResponse('Sorry ! Could not update display order');
        }
    }
}
