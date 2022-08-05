<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Web\Admin;


use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Helpers\StoreWarehouseHelper;


class WarehouseCompleteDetailController extends BaseController
{
    public $title = 'Warehouse Details';
    public $base_route = 'admin.warehouses';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';
    public $view ='admin.warehouse-complete-details.';


    private $warehouseService,$warehousePreOrderService;

    public function __construct(WarehouseService $warehouseService,WarehousePreOrderService $warehousePreOrderService){
        $this->warehouseService = $warehouseService;
        $this->warehousePreOrderService = $warehousePreOrderService;
    }

    public function getWarehouseCompleteDetail($warehouseCode){
        $warehouse = $this->warehouseService->findOrFailWarehouseByCode($warehouseCode);
        return view(Parent::loadViewData($this->module . $this->view . 'complete-details'), compact('warehouse','warehouseCode'));
    }

    public function getWarehouseGeneralDetail($warehouseCode){
        try {
            $warehouseUser = $this->warehouseService->getWarehouseUser($warehouseCode);
            $warehouse = $this->warehouseService->findOrFailWarehouseByCode($warehouseCode);
            $response['html'] = view($this->module . $this->view . 'layout.partials.general-detail.show',compact('warehouse','warehouseUser'))->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getWarehousePreOrderList($warehouseCode){
        try{
            $warehouse = $this->warehouseService->findOrFailWarehouseByCode($warehouseCode);
            $warehousePreOrders = $this->warehousePreOrderService->getPaginatedPreOrdersOfWarehouse($warehouseCode,10);
//            dd($warehousePreOrders);
            $response['html'] = view($this->module . $this->view . 'layout.partials.pre-order.index',compact('warehousePreOrders','warehouse'))->render();
            return response()->json($response);
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function getStoresConnectedToWarehouse($warehouseCode){
        try{
            $stores= StoreWarehouseHelper::getStoresAssociatedWithWarehouseByWarehouseCode($warehouseCode,10);
            $response['html'] = view($this->module . $this->view . 'layout.partials.connected-stores.index',compact('stores'))->render();
            return response()->json($response);
        }catch (\Exception $exception){
            return $exception->getMessage();
        }

    }

}
