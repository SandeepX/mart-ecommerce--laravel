<?php

namespace App\Modules\Types\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Types\Requests\StoreSize\StoreSizeCreateRequest;
use App\Modules\Types\Requests\StoreSize\StoreSizeUpdateRequest;
use App\Modules\Types\Services\StoreSizeService;

class StoreSizeController extends BaseController
{
    public $title = 'Store Size';
    public $base_route = 'admin.store-sizes';
    public $sub_icon = 'file';
    public $module = 'Types::';


    private $view;
    private $storeSizeService;


    public function __construct(StoreSizeService $storeSizeService)
    {
        $this->middleware('permission:View Store Size List', ['only' => ['index']]);
        $this->middleware('permission:Create Store Size', ['only' => ['create','store']]);
        $this->middleware('permission:Show Store Size', ['only' => ['show']]);
        $this->middleware('permission:Update Store Size', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Store Size', ['only' => ['destroy']]);

        $this->view = 'admin.store-sizes.';
        $this->storeSizeService = $storeSizeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storeSizes = $this->storeSizeService->getAllStoreSizes();
        return view(Parent::loadViewData($this->module.$this->view.'index'),compact('storeSizes'));
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
    public function store(StoreSizeCreateRequest $request)
    {
        $validated = $request->validated();
        try{
            $storeSize =  $this->storeSizeService->storeStoreSize($validated);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage());
        }
        return redirect()->back()->with('success', $this->title . ': '. $storeSize->store_size_name .' Created Successfully');
    }

   


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($storeSizeCode)
    {
        try{
            $storeSize = $this->storeSizeService->findOrFailStoreSizeByCode($storeSizeCode);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('storeSize'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSizeUpdateRequest $request,$storeSizeCode)
    {
        $validated = $request->validated();
        try{
            $storeSize = $this->storeSizeService->updateStoreSize($validated, $storeSizeCode);
            return redirect()->back()->with('success', $this->title . ': '. $storeSize->store_size_name .' Updated Successfully');
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
    public function destroy($storeSizeCode)
    {
        try{
            $storeSize = $this->storeSizeService->deleteStoreSize($storeSizeCode);
            return redirect()->back()->with('success', $this->title . ': '. $storeSize->store_size_name .' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
