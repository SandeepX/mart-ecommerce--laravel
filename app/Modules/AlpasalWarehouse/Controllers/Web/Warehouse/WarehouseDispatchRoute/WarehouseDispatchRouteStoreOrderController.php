<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseDispatchRoute;

use App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute\WhDispatchRouteStoreOrderCreateRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute\WhDispatchRouteStoreOrderDeleteRequest;
use App\Modules\AlpasalWarehouse\Services\WarehouseDispatchRoute\WarehouseDispatchRouteStoreOrderService;
use Exception;

class WarehouseDispatchRouteStoreOrderController
{
    private $warehouseDispatchRouteStoreOrderService;
    public function __construct(
        WarehouseDispatchRouteStoreOrderService $warehouseDispatchRouteStoreOrderService
    )
    {
        $this->warehouseDispatchRouteStoreOrderService= $warehouseDispatchRouteStoreOrderService;
    }

    public function addStoresOrderToDispatchRoute(
        WhDispatchRouteStoreOrderCreateRequest $request,$dispatchRouteCode
    ){
        try{
            $validatedData = $request->validated();
            $this->warehouseDispatchRouteStoreOrderService->addStoreOrderToDispatchRoute($dispatchRouteCode,$validatedData);
            return sendSuccessResponse('Orders add to route successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteRouteStoreOrders(WhDispatchRouteStoreOrderDeleteRequest $request,$dispatchRouteCode){
        try{
            $validatedData = $request->validated();
            $this->warehouseDispatchRouteStoreOrderService->deleteRouteOrdersByDispatchRouteCode($dispatchRouteCode,$validatedData);
            return sendSuccessResponse('Orders deleted from route successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
