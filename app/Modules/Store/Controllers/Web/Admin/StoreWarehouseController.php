<?php

namespace App\Modules\Store\Controllers\Web\Admin;

use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Requests\StoreWarehouseRequest;
use App\Modules\Store\Services\StoreService;

use Exception;

class StoreWarehouseController extends BaseController{

    public $title = 'Store';
    public $base_route = 'admin.stores';
    public $sub_icon = 'file';
    public $module = 'Store::';


    private $view;
    private $warehouseService;
    private $storeService;

    public function __construct(WarehouseService $warehouseService, StoreService $storeService)
    {

        $this->middleware('permission:View Store Warehouse List', ['only' => ['getStoreWarehouses']]);
        $this->middleware('permission:Create Store Warehouse', ['only' => ['storeWarehousePage','syncStoreWarehouses']]);
        $this->middleware('permission:Show Store Warehouse', ['only' => ['showStoreWarehouses']]);
        $this->middleware('permission:Update Store Warehouse', ['only' => ['editStoreWarehouses','syncStoreWarehouses']]);

        $this->view = 'admin.';
        $this->warehouseService = $warehouseService;
        $this->storeService = $storeService;

    }

    public function getStoreWarehouses()
    {
        try{
            $stores = $this->storeService->getStoresHavingWarehouses(['warehouses']);
            return view(Parent::loadViewData($this->module.$this->view.'store-warehouses.index'),compact('stores'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function showStoreWarehouses($storeCode)
    {
        try{
            $store = $this->storeService->findOrFailStoreByCodeWith($storeCode,['warehouses','warehouses.warehouseType']);
            if($store->status == "approved")
            {
                $storeWarehouses = $store->warehouses;
                return view(Parent::loadViewData($this->module.$this->view.'store-warehouses.show'),compact('store',
                    'storeWarehouses'));
            }
          else{
              throw new Exception('The store is not approved to see connected warehouses');
          }

        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function storeWarehousePage()
    {
        try{
            $stores = $this->storeService->getAllActiveStores();
            $warehouses = $this->warehouseService->getAllClosedWarehouses();
            return view(Parent::loadViewData($this->module.$this->view.'store-warehouses.create'),compact('stores', 'warehouses'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function syncStoreWarehouses(StoreWarehouseRequest $request){
        try{
            $validated = $request->validated();
            $store = $this->storeService->syncStoreWarehouses($validated);
            return redirect()->back()->with('success', 'Warehouses Linked To '.$store->store_name.' Successfully');
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleConnectionStatus($storeCode,$warehouseCode){
        try{

            $this->storeService->toggleWarehouseStoreConnection($storeCode,$warehouseCode);
            return redirect()->back()->with('success', 'Connection updated successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function editStoreWarehouses($storeCode){
        try{
            $store =  $this->storeService->findOrFailStoreByCode($storeCode);
            $warehouseCodes = $store->warehouses()->pluck('warehouses.warehouse_code')->toArray();

            $warehouses = $this->warehouseService->getAllClosedWarehouses();
            return view(Parent::loadViewData($this->module.$this->view.'store-warehouses.edit'),compact('store', 'warehouses', 'warehouseCodes'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}
