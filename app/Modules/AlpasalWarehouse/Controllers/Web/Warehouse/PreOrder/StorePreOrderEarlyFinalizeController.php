<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;

use App\Modules\AlpasalWarehouse\Requests\StorePreOrder\EarlyFinalizeCreateRequest;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehouseStorePreOrderEarlyFinalizeService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Services\PreOrder\StorePreOrderService;
use Exception;
use Illuminate\Http\Request;

class StorePreOrderEarlyFinalizeController extends BaseController
{
    public $title = 'Store PreOrder Early finalization';
    public $base_route = 'admin.warehouse-pre-orders.store-pre-order.early-finalize';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view = 'warehouse.warehouse-pre-orders.store-pre-orders.early-finalize';

    private $warehouseStorePreOrderEarlyFinalizeService;
    public function __construct(
        WarehouseStorePreOrderEarlyFinalizeService $warehouseStorePreOrderEarlyFinalizeService
    ){
        $this->warehouseStorePreOrderEarlyFinalizeService = $warehouseStorePreOrderEarlyFinalizeService;
    }

    public function earlyFinalizeCreate($storePreOrderCode){
        try{
            $storePreOrder = $this->warehouseStorePreOrderEarlyFinalizeService->createStorePreOrderEarlyFinalize($storePreOrderCode);
            return view(Parent::loadViewData($this->module.$this->view.'.create'),compact('storePreOrder'));
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public  function earlyFinalizeSave(EarlyFinalizeCreateRequest $request,$storePreOrderCode){
        try{
            $validated = $request->validated();
            $this->warehouseStorePreOrderEarlyFinalizeService->saveStorePreOrderEarlyFinalize($storePreOrderCode,$validated);
            return $request->session()->flash('success','Early finalization success for '.$storePreOrderCode.'');
        }catch (Exception $exception){
          return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

}
