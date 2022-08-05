<?php


namespace App\Modules\AlpasalWarehouse\Services\WarehouseDispatchRoute;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch\WarehouseDispatchRouteStoreHelper;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteMarkerRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteStoreOrderRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteStoreRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseDispatchRouteStoreService
{
    private $warehouseDispatchRouteRepository,$warehouseDispatchRouteStoreRepository;

    private $warehouseDispatchRouteMarkerRepository,$warehouseDispatchRouteStoreOrderRepository;

    public function __construct(
        WarehouseDispatchRouteRepository $warehouseDispatchRouteRepository,
        WarehouseDispatchRouteStoreRepository $warehouseDispatchRouteStoreRepository,
        WarehouseDispatchRouteStoreOrderRepository $warehouseDispatchRouteStoreOrderRepository,
        WarehouseDispatchRouteMarkerRepository $warehouseDispatchRouteMarkerRepository
    )
    {
        $this->warehouseDispatchRouteRepository = $warehouseDispatchRouteRepository;
        $this->warehouseDispatchRouteStoreRepository = $warehouseDispatchRouteStoreRepository;
        $this->warehouseDispatchRouteStoreOrderRepository = $warehouseDispatchRouteStoreOrderRepository;
        $this->warehouseDispatchRouteMarkerRepository = $warehouseDispatchRouteMarkerRepository;
    }

    public function getDispatchRouteStores($dispatchRouteCode){
        try{
            $authWarehouseCode = getAuthWarehouseCode();
            $warehouseDispatchRouteStores = $this->warehouseDispatchRouteStoreRepository->orderBy('sort_order','asc')
                ->getByDispatchRouteCode($dispatchRouteCode,$authWarehouseCode);
            return $warehouseDispatchRouteStores;
        }catch (Exception $exception){
            throw $exception;
        }
    }
    public function massAddStoreToDispatchRoute($dispatchRouteCode,$validatedData){
        try{
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $validatedData['store_code'] = array_filter($validatedData['store_code']);
            $with = [
                'maxSortedWarehouseDispatchRouteStore',
                'warehouseDispatchRouteStores'
            ];
            $warehouseDispatchRoute = $this->warehouseDispatchRouteRepository->with($with)->findByWarehouseCode($authWarehouseCode, $dispatchRouteCode);

            if (!$warehouseDispatchRoute){
                throw new Exception('Dispatch route not found');
            }

            if ($warehouseDispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }

            $existingStoresCodeArr = $warehouseDispatchRoute->warehouseDispatchRouteStores->pluck('store_code')->toArray();

            $validStoresCodeArr = WarehouseDispatchRouteStoreHelper::getDispatchableStores(
                $authWarehouseCode,$dispatchRouteCode)
                ->pluck('store_code')->toArray();

            $toBeStoredStores =[];

            $lastMaxSortedOrder = $warehouseDispatchRoute->maxSortedWarehouseDispatchRouteStore ?
                $warehouseDispatchRoute->maxSortedWarehouseDispatchRouteStore->max_sort_order : 0;

            foreach ($validatedData['store_code'] as $inputStoreCode) {
                if (!in_array($inputStoreCode, $validStoresCodeArr)) {
                    throw new Exception('Invalid store');
                }
                if (in_array($inputStoreCode, $existingStoresCodeArr)) {
                    continue;
                }


                array_push($toBeStoredStores,[
                    'wh_dispatch_route_code' => $warehouseDispatchRoute->wh_dispatch_route_code,
                    'store_code' => $inputStoreCode,
                    'sort_order' => ++$lastMaxSortedOrder,
                ]);
            }

            if (count($toBeStoredStores) > 0){
               $this->warehouseDispatchRouteStoreRepository->createMany($toBeStoredStores);
            }
            DB::commit();
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function sortDispatchRouteStores($dispatchRouteCode,$validatedData){
        try{
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $validatedData['dispatch_route_store_code'] = array_filter($validatedData['dispatch_route_store_code']);
            $with = [
                'warehouseDispatchRouteStores'
            ];
            $warehouseDispatchRoute = $this->warehouseDispatchRouteRepository->with($with)
                ->findByWarehouseCode($authWarehouseCode,$dispatchRouteCode);

            if (!$warehouseDispatchRoute){
                throw new Exception('Dispatch route not found');
            }

            if ($warehouseDispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }

            foreach($warehouseDispatchRoute->warehouseDispatchRouteStores as $dispatchRouteStore){

                $inputRouteStoreCodeKey = array_search(
                    $dispatchRouteStore->wh_dispatch_route_store_code,
                    $validatedData['dispatch_route_store_code']
                );
                if ($inputRouteStoreCodeKey === false){
                    throw new Exception('Store missing: input all the stores');
                }
                $this->warehouseDispatchRouteStoreRepository->update($dispatchRouteStore,[
                    'sort_order' => $inputRouteStoreCodeKey+1
                ]);
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteWarehouseDispatchRouteStores($dispatchRouteCode,$validatedData){
        try{
            //dd($validatedData);
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
            $validatedData['dispatch_route_store_code'] = array_filter($validatedData['dispatch_route_store_code']);

            $existingRouteStoresCodeArr = $dispatchRoute->warehouseDispatchRouteStores->pluck('wh_dispatch_route_store_code')
                ->toArray();

            foreach ($validatedData['dispatch_route_store_code'] as $inputDispatchRouteStoreCode){
                if (!in_array($inputDispatchRouteStoreCode,$existingRouteStoresCodeArr)){
                    throw new Exception('Invalid route store code');
                }
            }

            $routeStoreOrdersCodeArr=$this->warehouseDispatchRouteStoreOrderRepository->getByDispatchRouteStoresCode($validatedData['dispatch_route_store_code'])
                ->pluck('wh_dispatch_route_store_order_code')->toArray();

            if (count($routeStoreOrdersCodeArr) > 0){
                $this->warehouseDispatchRouteStoreOrderRepository->deleteByCodes($routeStoreOrdersCodeArr);
            }
            $this->warehouseDispatchRouteStoreRepository->deleteByCodes($validatedData['dispatch_route_store_code']);

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


    public function deleteMassStoresByDispatchCode($dispatchRouteCode){
        try{
            //dd($validatedData);
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();

            $dispatchRoute = $this->warehouseDispatchRouteRepository->findByWarehouseCode($authWarehouseCode,$dispatchRouteCode);

            if (!$dispatchRoute){
                throw new Exception('Dispatch route not found');
            }
            if ($dispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }
            $routeStoreOrdersCodeArr=$this->warehouseDispatchRouteStoreOrderRepository->getByDispatchRouteCode($dispatchRouteCode)
                ->pluck('wh_dispatch_route_store_order_code')->toArray();

            if (count($routeStoreOrdersCodeArr) > 0){
                $this->warehouseDispatchRouteStoreOrderRepository->deleteByCodes($routeStoreOrdersCodeArr);
            }
            $this->warehouseDispatchRouteStoreRepository->deleteByDispatchRouteCode($dispatchRouteCode);
            $this->warehouseDispatchRouteMarkerRepository->deleteByDispatchRouteCode($dispatchRouteCode);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
