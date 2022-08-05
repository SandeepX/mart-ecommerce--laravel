<?php

namespace App\Modules\Store\Controllers\Web\Admin\StorePackageTypes;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\Store\Helpers\StoreFilter;
use App\Modules\Store\Models\StorePackageTypes\StoreTypePackageMaster;
use App\Modules\Store\Requests\RegistrationChargeRequest;
use App\Modules\Store\Requests\StoreCreateRequest;
use App\Modules\Store\Requests\StorePackageTypes\StoreTypePackageCreateRequest;
use App\Modules\Store\Requests\StorePackageTypes\StoreTypePackageUpdateRequest;
use App\Modules\Store\Requests\StoreUpdateRequest;
use App\Modules\Store\Services\StorePackageTypes\StoreTypePackageMasterService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Transformers\StoreDetailTransformer;
use App\Modules\Types\Services\CompanyTypeService;
use App\Modules\Types\Services\RegistrationTypeService;
use App\Modules\Types\Services\StoreSizeService;
use App\Modules\Types\Services\StoreTypeService;
use App\Modules\User\Requests\StoreUserCreateTempRequest;
use App\Modules\User\Requests\UserCreateRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;

class StoreTypePackageMasterController extends BaseController
{

    public $title = 'Store Type Packages';
    public $base_route = 'admin.store-type-packages';
    public $sub_icon = 'file';
    public $module = 'Store::';


    private $view='admin.store-type-packages.';

    private $storeTypePackageMasterService,$storeTypeService;



    public function __construct(
         StoreTypePackageMasterService $storeTypePackageMasterService,
        StoreTypeService $storeTypeService
    )
    {
        $this->middleware('permission:View Store Type Package Lists', ['only' => ['index']]);
        $this->middleware('permission:Create Store Type Package', ['only' => ['create', 'store']]);
        $this->middleware('permission:Show Store Type Package', ['only' => ['show']]);
        $this->middleware('permission:Update Store Type Package', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Change Store Type Package Status', ['only' => ['changeStatus']]);

         $this->storeTypePackageMasterService = $storeTypePackageMasterService;
         $this->storeTypeService = $storeTypeService;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($storeTypeCode)
    {
        try{
            $storeType = $this->storeTypeService->findStoreTypeByCode($storeTypeCode);
            $storeTypePackages = $this->storeTypePackageMasterService->getAllStoreTypePackages($storeTypeCode);
            return view(Parent::loadViewData($this->module.$this->view.'index'),
                compact('storeTypePackages','storeType'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreTypePackageCreateRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $storeTypePackage = $this->storeTypePackageMasterService->createStoreTypePackage($validatedData);
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

        return redirect()->back()->with('success',$this->title . ': '. $storeTypePackage->package_name .' created successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($storeTPMCode)
    {
        $storeTypePackage = $this->storeTypePackageMasterService->findOrFailStoreTypePackageByCode($storeTPMCode);

        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('storeTypePackage'));

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($storeTPMCode,Request $request)
    {
        try{
            $storeTypePackage = $this->storeTypePackageMasterService->findStoreTypePackageByCode($storeTPMCode);
            $storeType = $this->storeTypeService->findStoreTypeByCode($storeTypePackage->store_type_code);
            if ($request->ajax()) {
                return view(Parent::loadViewData($this->module.$this->view.'common.edit-form'),
                    compact('storeTypePackage', 'storeType'));
            }
        }catch (\Exception $ex){
            return redirect()->back()->with('danger',$ex->getMessage());
        }
        return view(Parent::loadViewData($this->module.$this->view.'edit'),
            compact('storeTypePackage','storeType'));

    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(StoreTypePackageUpdateRequest $request, $storeTPMCode)
    {
        $validatedData = $request->validated();
        try{
            $storeTypePackage = $this->storeTypePackageMasterService->updateStoreTypePackage($validatedData, $storeTPMCode);

        }catch(\Exception $exception){

            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
        return redirect()->back()->with('success', $this->title . ': '. $storeTypePackage->package_name .' Updated Successfully')->withInput();

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($storeTPMCode)
    {
        try{
            $storeTypePackage = $this->storeTypePackageMasterService->deleteTypePackage($storeTPMCode);
            return redirect()->back()->with('success', $this->title . ' Trashed Successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changeStatus($storeTPMCode,$status){
        try{
            $updateStatus = $this->storeTypePackageMasterService->changeStoreTypePackageStatus($storeTPMCode,$status);
            return redirect()->back()->with('success','Store Type Package :'.$updateStatus->package_name.' status changed successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changePackageDisplayOrder(Request $request,$storeTypeCode){
        try{
            $sortOrdersToChange = $request->sort_order;
            $updateStatus = $this->storeTypePackageMasterService->changePackageDisplayOrder($storeTypeCode,$sortOrdersToChange);
            return sendSuccessResponse('Display Order Updated');
        }catch(\Exception $exception){
            return sendErrorResponse('Sorry ! Could not update display order');
        }
    }

}

