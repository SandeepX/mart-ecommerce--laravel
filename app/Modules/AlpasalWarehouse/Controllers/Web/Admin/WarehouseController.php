<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Web\Admin;

use App\Modules\AlpasalWarehouse\Requests\WarehouseCreateRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseUpdateRequest;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\AlpasalWarehouse\Services\WarehouseTypeService;
use App\Modules\AlpasalWarehouse\Transformers\WarehouseDetailTransformer;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use App\Modules\RolePermission\Services\RoleService;
use App\Modules\User\Requests\WarehouseAdminCreateRequest;
use Exception;

class WarehouseController extends BaseController
{
    public $title = 'AlpasalWarehouse';
    public $base_route = 'admin.warehouses';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';


    private $view;
    private $warehouseService;
    private $warehouseTypeService;
    private $locationHierarchyService,$roleService;

    public function __construct(
        WarehouseService $warehouseService,
        WarehouseTypeService $warehouseTypeService,
        LocationHierarchyService $locationHierarchyService,
        RoleService $roleService
    )
    {
        $this->middleware('permission:View Warehouse List', ['only' => ['index']]);
        $this->middleware('permission:Create Warehouse', ['only' => ['create','store']]);
        $this->middleware('permission:Show Warehouse', ['only' => ['show']]);
        $this->middleware('permission:Update Warehouse', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Warehouse', ['only' => ['destroy']]);

        $this->view = 'admin.';
        $this->warehouseService = $warehouseService;
        $this->warehouseTypeService = $warehouseTypeService;
        $this->locationHierarchyService = $locationHierarchyService;
        $this->roleService = $roleService;
    }

    public function index()
    {
       try{
            $warehouses = $this->warehouseService->getAllWarehouses();
            return view(Parent::loadViewData($this->module.$this->view.'index'),compact('warehouses'));
       }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());

       }
    }

    public function store(WarehouseCreateRequest $warehouseCreateRequest,
                          WarehouseAdminCreateRequest $warehouseAdminCreateRequest){
        try{
            $validatedWarehouse = $warehouseCreateRequest->validated();

            $validatedWarehouseAdmin = $warehouseAdminCreateRequest->validated();
            //$warehouse = $this->warehouseService->storeWarehouse($validatedWarehouse);

            $warehouseWithUser = $this->warehouseService->storeWarehouseWithAdmin($validatedWarehouse,$validatedWarehouseAdmin);
            return sendSuccessResponse($this->title . ': '. $warehouseWithUser['warehouse']->warehouse_name  .' created successfully');

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function create()
    {
        try{
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
            $warehouseTypes = $this->warehouseTypeService->getAllWarehouseTypes();
            $warehouseTypeRoles = $this->roleService->getWarehouseTypeRoles();
            return view(Parent::loadViewData($this->module.$this->view.'create'),compact('warehouseTypes',
                'provinces','warehouseTypeRoles'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function show($warehouseCode){

        $warehouse = $this->warehouseService->findOrFailWarehouseByCodeWith($warehouseCode,
            ['location','warehouseType','warehouseAdmins']);

        $warehouse = (new WarehouseDetailTransformer($warehouse))->transform();
       // dd($warehouse);

        return view(Parent::loadViewData($this->module.$this->view.'show'),compact('warehouse'));
    }

    public function edit($warehouseCode){

        try{
            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
            $warehouseTypes = $this->warehouseTypeService->getAllWarehouseTypes();
            $warehouse = $this->warehouseService->findWarehouseByCode($warehouseCode);
            $locationPath = $this->locationHierarchyService->getLocationPath($warehouse->location_code);

            $warehouseTypeRoles = $this->roleService->getWarehouseTypeRoles();
            return view(Parent::loadViewData($this->module.$this->view.'edit'),compact('warehouseTypes',
                'provinces', 'warehouse', 'locationPath','warehouseTypeRoles'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());

        }

    }

    public function update(WarehouseUpdateRequest $warehouseUpdateRequest, $warehouseCode){
        $validatedWarehouse = $warehouseUpdateRequest->validated();
        try{
            $warehouse = $this->warehouseService->updateWarehouse($validatedWarehouse, $warehouseCode);
            return redirect()->back()->with('success', $this->title . ': '. $warehouse->warehouse_name .' Updated Successfully')->withInput();
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());

        }
    }

//    public function destroy($warehouseCode){
//        try{
//            $warehouse = $this->warehouseService->deleteWarehouse($warehouseCode);
//            return redirect()->back()->with('success', $this->title . ': '. $warehouse->warehouse_name .' Deleted Successfully');
//        }catch(Exception $exception){
//            return redirect()->back()->with('danger', $exception->getMessage());
//
//        }
//    }
}
