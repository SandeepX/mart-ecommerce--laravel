<?php


namespace App\Modules\AlpasalWarehouse\Services\WarehouseDispatchRoute;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch\WarehouseDispatchRouteStoreOrderHelper;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteStoreOrderRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteStoreRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseDispatchRouteStoreOrderService
{
    private $warehouseDispatchRouteRepository, $warehouseDispatchRouteStoreRepository;

    private $warehouseDispatchRouteStoreOrderRepository;

    public function __construct(
        WarehouseDispatchRouteRepository $warehouseDispatchRouteRepository,
        WarehouseDispatchRouteStoreRepository $warehouseDispatchRouteStoreRepository,
        WarehouseDispatchRouteStoreOrderRepository $warehouseDispatchRouteStoreOrderRepository
    )
    {
        $this->warehouseDispatchRouteRepository = $warehouseDispatchRouteRepository;
        $this->warehouseDispatchRouteStoreRepository = $warehouseDispatchRouteStoreRepository;
        $this->warehouseDispatchRouteStoreOrderRepository = $warehouseDispatchRouteStoreOrderRepository;
    }

    public function addStoreOrderToDispatchRoute($dispatchRouteCode, $validatedData)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $warehouseDispatchRoute = $this->warehouseDispatchRouteRepository->findByWarehouseCode($authWarehouseCode, $dispatchRouteCode);

            if (!$warehouseDispatchRoute) {
                throw new Exception('Dispatch route not found');
            }

            if ($warehouseDispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }

            $validatedData['order_code'] = array_filter($validatedData['order_code']);

            $whDispatchRouteStore = $this->warehouseDispatchRouteStoreRepository->with(['warehouseDispatchRouteStoreOrders'])
                ->findByCode($validatedData['wh_dispatch_route_store_code']);
            if (!$whDispatchRouteStore){
                throw new Exception('Store not found in the route');
            }
            $existingStoreOrders = $whDispatchRouteStore->warehouseDispatchRouteStoreOrders->pluck('order_code')->toArray();
            $validDispatchableStoreOrders =WarehouseDispatchRouteStoreOrderHelper::getDispatchableStoreOrders(
                $authWarehouseCode,$whDispatchRouteStore->store_code);

            $toBeStoredStoreOrders =[];
            foreach ($validatedData['order_code'] as $orderCode){
                $inputOrder =  $validDispatchableStoreOrders->where('order_code',$orderCode)->first();
                if (!$inputOrder){
                    throw new Exception('Invalid order');
                }
                if(in_array($orderCode,$existingStoreOrders)){
                    continue;
                }
                array_push($toBeStoredStoreOrders,[
                   'wh_dispatch_route_store_code' => $whDispatchRouteStore->wh_dispatch_route_store_code,
                    'order_code' => $orderCode,
                    'order_type' => $inputOrder->order_type,
                    'total_amount' => $inputOrder->total_amount,
                ]);
            }
           $this->warehouseDispatchRouteStoreOrderRepository->createMany($toBeStoredStoreOrders);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteRouteOrdersByDispatchRouteCode($dispatchRouteCode,$validatedData){
        try{
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();

            $with =[
                'warehouseDispatchRouteStores'
            ];

            $dispatchRoute = $this->warehouseDispatchRouteRepository->with($with)->findByWarehouseCode($authWarehouseCode,$dispatchRouteCode);

            if (!$dispatchRoute){
                throw new Exception('Dispatch route not found');
            }
            if ($dispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }
            $validatedData['dispatch_route_store_order_code'] = array_filter($validatedData['dispatch_route_store_order_code']);

            $this->warehouseDispatchRouteStoreOrderRepository->deleteByCodes($validatedData['dispatch_route_store_order_code']);

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
