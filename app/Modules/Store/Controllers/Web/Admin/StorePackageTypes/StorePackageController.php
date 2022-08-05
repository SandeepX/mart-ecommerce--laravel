<?php


namespace App\Modules\Store\Controllers\Web\Admin\StorePackageTypes;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Requests\StorePackageTypes\StorePackageUpdateRequest;
use App\Modules\Store\Services\StorePackageTypes\StorePackageAdminService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Types\Services\StoreTypeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorePackageController extends BaseController
{
    public $title = 'Store Type Packages';
    public $base_route = 'admin.store-package';
    public $sub_icon = 'file';
    public $module = 'Store::';

    private $view='admin.store-package.';

    private $storeService;
    private $storeTypeService;
    private $storePackageAdminService;


    public function __construct(
        StoreService $storeService,
        StoreTypeService $storeTypeService,
        StorePackageAdminService $storePackageAdminService
    ){
        $this->storeService = $storeService;
        $this->storeTypeService = $storeTypeService;
        $this->storePackageAdminService = $storePackageAdminService;
    }


    public function updateForm($storeCode)
    {
        try{
            $store = $this->storeService->findOrFailStoreByCode($storeCode);
            $storeTypes = $this->storeTypeService->getAllActiveStoreTypes();

            return view(Parent::loadViewData($this->module.$this->view.'update'),compact('store','storeTypes'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function update(StorePackageUpdateRequest $request,$storeCode){
        try{
            DB::beginTransaction();
            $validated = $request->validated();
            $store = $this->storePackageAdminService->storeUpdatePackage($storeCode,$validated);
            DB::commit();
            return $request->session()->flash('success','Package Updated successfully of '.$store->store_name.'('.$store->store_code.')');
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
