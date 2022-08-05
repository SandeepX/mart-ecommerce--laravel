<?php


namespace App\Modules\AlpasalWarehouse\Services\WarehouseDispatchRoute;


use App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch\WarehouseDispatchRouteHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch\WarehouseDispatchRouteStoreHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch\WarehouseDispatchRouteStoreOrderHelper;
use App\Modules\AlpasalWarehouse\Repositories\Bill\WarehouseBillMergeRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteMarkerRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteStoreOrderRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteStoreRepository;
use App\Modules\SalesManager\Repositories\ManagerStoreReferralRepository;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderStatusLogRepository;
use App\Modules\Store\Repositories\StoreOrderRepository;
use App\Modules\Store\Repositories\StoreRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Exception;

class WarehouseDispatchRouteService
{
    private $warehouseDispatchRouteRepository, $warehouseDispatchRouteStoreRepository;

    private $storeOrderRepository,$storePreOrderRepository;

    private $storePreOrderStatusLogRepository,$warehouseBillMergeRepository;

    private $warehouseDispatchRouteStoreOrderRepository,$warehouseDispatchRouteMarkerRepository;
    private $salesManagerService;
    private $storeRepository;
    private $managerStoreReferralRepository;

    public function __construct(
        WarehouseDispatchRouteRepository $warehouseDispatchRouteRepository,
        WarehouseDispatchRouteStoreRepository $warehouseDispatchRouteStoreRepository,
        WarehouseDispatchRouteStoreOrderRepository $warehouseDispatchRouteStoreOrderRepository,
        WarehouseDispatchRouteMarkerRepository $warehouseDispatchRouteMarkerRepository,
        StoreOrderRepository $storeOrderRepository,
        StorePreOrderRepository $storePreOrderRepository,
        StorePreOrderStatusLogRepository $storePreOrderStatusLogRepository,
        WarehouseBillMergeRepository $warehouseBillMergeRepository,
        SalesManagerService $salesManagerService,
        StoreRepository $storeRepository,
        ManagerStoreReferralRepository $managerStoreReferralRepository
    )
    {
        $this->warehouseDispatchRouteRepository = $warehouseDispatchRouteRepository;
        $this->warehouseDispatchRouteStoreRepository = $warehouseDispatchRouteStoreRepository;
        $this->warehouseDispatchRouteStoreOrderRepository = $warehouseDispatchRouteStoreOrderRepository;
        $this->warehouseDispatchRouteMarkerRepository = $warehouseDispatchRouteMarkerRepository;
        $this->storeOrderRepository = $storeOrderRepository;
        $this->storePreOrderRepository = $storePreOrderRepository;
        $this->storePreOrderStatusLogRepository = $storePreOrderStatusLogRepository;
        $this->warehouseBillMergeRepository = $warehouseBillMergeRepository;
        $this->salesManagerService = $salesManagerService;
        $this->storeRepository = $storeRepository;
        $this->managerStoreReferralRepository = $managerStoreReferralRepository;
    }

    public function getAvailableStoresForDispatch()
    {
        try {
            $authWarehouseCode = getAuthWarehouseCode();
            $validStores = WarehouseDispatchRouteStoreHelper::getDispatchableStores($authWarehouseCode);
            return $validStores;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function filterWarehouseDispatchRoutes($filterParameters)
    {
        try {
            $filterParameters['warehouse_code'] = getAuthWarehouseCode();
            $with = [
                'warehouseDispatchRouteMarkers' => function ($query) {
                    $query->select(
                        'wh_dispatch_route_marker_code',
                        'wh_dispatch_route_code',
                        'latitude',
                        'longitude'
                    )->orderBy('sort_order');
                }
            ];
            $dispatchRoutes = WarehouseDispatchRouteHelper::filterDispatchRoutes($filterParameters, 20, $with);

            //$groupedRoutes = $dispatchRoutes->groupBy('wh_dispatch_route_code');
            $groupedRoutes = $dispatchRoutes->mapToGroups(function ($item, $key) {
                return [$item['wh_dispatch_route_code'] => $item];
            })->values();


            $dispatchRoutes = $dispatchRoutes->setCollection($groupedRoutes);

            return $dispatchRoutes;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getWarehouseDispatchRouteDetail($dispatchRouteCode){
        try{
            $authWarehouseCode = getAuthWarehouseCode();

            $with=[
                'warehouseDispatchRouteStores'=>function($q){
                    $q->orderBy('sort_order','asc');
                },
                'warehouseDispatchRouteStores.store:store_code,store_name,latitude,longitude,store_landmark_name',
                'createdBy:user_code,name',
                'updatedBy:user_code,name',
            ];

            $dispatchRoute = $this->warehouseDispatchRouteRepository->with($with)->findByWarehouseCode(
                $authWarehouseCode, $dispatchRouteCode);

            if (!$dispatchRoute) {
                throw new Exception('Dispatch route not found');
            }
            if ($dispatchRoute->isDispatched()){

                $dispatchRoute->load([
                    'warehouseDispatchRouteStores.warehouseDispatchRouteStoreOrders'
                ]);

            }else{
                $dispatchRoute->warehouseDispatchRouteStores->map(function ($routeStore) use ($authWarehouseCode){
                    $routeStore->store_orders = WarehouseDispatchRouteStoreOrderHelper::getDispatchableStoreOrdersWithExisting($authWarehouseCode,$routeStore->store_code);

                    return $routeStore;
                });
            }

            return $dispatchRoute;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function createDispatchRouteWithStores($validatedData)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $validatedData['store_code'] = array_filter($validatedData['store_code']);
            $inputDispatchRoute = [
                'route_name' => $validatedData['route_name'],
                'warehouse_code' => $authWarehouseCode,
                'status' => 'pending'
            ];

            $dispatchRoute = $this->warehouseDispatchRouteRepository->create($inputDispatchRoute);

            $validStoresCodeArr = WarehouseDispatchRouteStoreHelper::getDispatchableStores(
                $authWarehouseCode)->pluck('store_code')->toArray();

            $toBeStoredStores = [];
            foreach ($validatedData['store_code'] as $key => $storeCode) {
                if (!in_array($storeCode, $validStoresCodeArr)) {
                    throw new Exception('Invalid store');
                }
                array_push($toBeStoredStores, [
                    'wh_dispatch_route_code' => $dispatchRoute->wh_dispatch_route_code,
                    'store_code' => $storeCode,
                    'sort_order' => $key + 1
                ]);
            }

            $routesStores = $this->warehouseDispatchRouteStoreRepository->createMany($toBeStoredStores);
            DB::commit();
            return [
                'dispatch_route' => $dispatchRoute,
                'route_stores' => $routesStores
            ];
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateDispatchRouteName($dispatchRouteCode, $validatedData)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();

            $dispatchRoute = $this->warehouseDispatchRouteRepository->findByWarehouseCode(
                $authWarehouseCode, $dispatchRouteCode);

            if (!$dispatchRoute) {
                throw new Exception('Dispatch route not found');
            }

            if ($dispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }

            if ($validatedData['route_name'] !== $dispatchRoute->route_name) {
                $dispatchRoute = $this->warehouseDispatchRouteRepository->update($dispatchRoute, [
                    'route_name' => $validatedData['route_name']
                ]);
            }
            DB::commit();
            return $dispatchRoute;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function dispatchWarehouseRouteFinal($dispatchRouteCode, $validatedData)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();

            $dispatchRoute = $this->warehouseDispatchRouteRepository->findByWarehouseCode(
                $authWarehouseCode, $dispatchRouteCode);

            if (!$dispatchRoute) {
                throw new Exception('Dispatch route not found');
            }

            if ($dispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }

            $storesHavingZeroOrders=$this->warehouseDispatchRouteStoreRepository->getRouteStoresHavingZeroOrders($dispatchRouteCode);

            if (count($storesHavingZeroOrders) > 0){
                throw new Exception('Please add at least one order to store or remove the store from route');
            }

            $totalRouteStoreOrders = WarehouseDispatchRouteStoreOrderHelper::getDispatchRouteStoreOrders($dispatchRouteCode);

            $invalidRouteStoreOrders = $totalRouteStoreOrders->where('status','!=','ready_to_dispatch')->all();

            if (count($invalidRouteStoreOrders) > 0){
                throw new Exception('Invalid store orders: make sure all orders are ready to dispatch');
            }

            $normalOrders = $totalRouteStoreOrders->where('order_type','normal_order')->all();
            $preOrders = $totalRouteStoreOrders->where('order_type','pre_order')->all();
            $billMergedOrders = $totalRouteStoreOrders->where('order_type','bill_merge')->all();
            $statusData=[
                'status' => 'dispatched',
                'remarks' =>'vehicle route dispatched'
            ];
            if (count($normalOrders) > 0){

               // $normalOrdersCodeArr = $normalOrders->pluck('order_code')->toArray();
                $normalOrdersCodeArr = Arr::pluck($normalOrders,'order_code');

                $this->storeOrderRepository->massUpdateDeliveryStatus($normalOrdersCodeArr,$statusData);
            }

            if (count($preOrders) > 0){
              //  $preOrdersCodeArr = $preOrders->pluck('order_code')->toArray();
                $preOrdersCodeArr = Arr::pluck($preOrders,'order_code');
                $this->storePreOrderRepository->massUpdatePreOrderStatus($preOrdersCodeArr,$statusData);
                $this->storePreOrderStatusLogRepository->massSaveStatusLog($preOrdersCodeArr,$statusData);
            }

            if (count($billMergedOrders) > 0){
                //$billMergedOrdersCodeArr = $billMergedOrders->pluck('order_code')->toArray();
                $billMergedOrdersCodeArr = Arr::pluck($billMergedOrders,'order_code');
                foreach ($billMergedOrdersCodeArr as $billMergeCode){
                    $with=[
                        'billMergeDetails'
                    ];
                    $billMergeMaster =$this->warehouseBillMergeRepository->with($with)
                        ->findByCode($billMergeCode);
                    if (!$billMergeMaster){
                        throw new Exception('Bill merge code not found: '.$billMergeCode);
                    }
                    if ($billMergeMaster->status !=  'ready_to_dispatch'){
                        throw new Exception('Bill merge not ready to dispatch: '.$billMergeCode);
                    }
                    $billMergedNormalOrdersCodeArr = $billMergeMaster->billMergeDetails->where('bill_type','cart')
                        ->pluck('bill_code')->toArray();

                    if (count($billMergedNormalOrdersCodeArr) > 0){
                        $this->storeOrderRepository->massUpdateDeliveryStatus($billMergedNormalOrdersCodeArr,$statusData);
                    }

                    $billMergedPreOrdersCodeArr = $billMergeMaster->billMergeDetails->where('bill_type','preorder')
                        ->pluck('bill_code')->toArray();

                    if (count($billMergedPreOrdersCodeArr) > 0){
                        $this->storePreOrderRepository->massUpdatePreOrderStatus($billMergedPreOrdersCodeArr,$statusData);
                        $this->storePreOrderStatusLogRepository->massSaveStatusLog($billMergedPreOrdersCodeArr,$statusData);
                    }

                    $this->warehouseBillMergeRepository->updateBillMergeStatus($billMergeMaster,$statusData);
                }

            }

            $storesInDispatchRoutes = $this->warehouseDispatchRouteStoreRepository->with(['store'])->getByDispatchRouteCode($dispatchRouteCode,$authWarehouseCode);


            //stores incentive amount
            foreach($storesInDispatchRoutes as $storeInDispatchRoute){
                $store = $storeInDispatchRoute->store;
                $managerStoreReferral = $store->referredBy;
                if(is_null($store->referred_incentive_amount) && $store->referredBy){

                    $storeIncentiveAmount = $store->storeTypePackage->referal_registration_incentive_amount;
                    $incentiveMetaData = [];
                    $managerStoreReferralsData = [];
                    $managerStoreReferralsData['referred_incentive_amount'] = $storeIncentiveAmount;
                    if($storeIncentiveAmount > 0){
                        $this->salesManagerService->prepareWalletTransactionForSalesManagerStoreReferralCommission(
                            $store->referredBy->manager,
                            $store,
                            true
                        );
                        $orders = $store->orders->where('delivery_status','dispatched')->first();
                        $preOrders = $store->preOrders->where('status','dispatched')->first();
                        $currentTime = Carbon::now();
                        if(isset($orders->updated_at) &&  isset($preOrders->updated_at)){
                            if($orders->updated_at < $preOrders->updated_at){
                                $incentiveMetaData['source'] = 'normal_order';
                                $incentiveMetaData['source_code'] = $orders->store_order_code;
                                $incentiveMetaData['incentive_received_at'] = $currentTime;
                            }else{
                                $incentiveMetaData['source'] = 'preorder';
                                $incentiveMetaData['source_code'] = $preOrders->store_preorder_code;
                                $incentiveMetaData['incentive_received_at'] =  $currentTime;
                            }
                        }elseif(isset($orders->updated_at)){
                            $incentiveMetaData['source'] = 'normal_order';
                            $incentiveMetaData['source_code'] = $orders->store_order_code;
                            $incentiveMetaData['incentive_received_at'] =  $currentTime;
                        }elseif(isset($preOrders->updated_at)){
                            $incentiveMetaData['source'] = 'preorder';
                            $incentiveMetaData['source_code'] = $preOrders->store_preorder_code;
                            $incentiveMetaData['incentive_received_at'] =  $currentTime;
                        }
                        $managerStoreReferralsData['referred_incentive_amount_meta'] = json_encode($incentiveMetaData);
                    }
                    $this->managerStoreReferralRepository->updateReferralDetails($managerStoreReferral,$managerStoreReferralsData);
                }
            }

            $validatedData['status'] ='dispatched';
            $this->warehouseDispatchRouteRepository->update($dispatchRoute,$validatedData);
            DB::commit();
            return $dispatchRoute;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    public function deleteWarehouseDispatchRoute($dispatchRouteCode){
        try{
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();

            $with =[
                'warehouseDispatchRouteStores'
            ];

            $dispatchRoute = $this->warehouseDispatchRouteRepository->with($with)
                ->findByWarehouseCode($authWarehouseCode,$dispatchRouteCode);

            if (!$dispatchRoute){
                throw new Exception('Dispatch route not found');
            }
            if ($dispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }

            $existingRouteStoresOrderCodeArr = $this->warehouseDispatchRouteStoreOrderRepository->getByDispatchRouteCode($dispatchRouteCode)
                ->pluck('wh_dispatch_route_store_order_code')->toArray();

            if (count($existingRouteStoresOrderCodeArr) > 0){
                $this->warehouseDispatchRouteStoreOrderRepository->deleteByCodes($existingRouteStoresOrderCodeArr);
            }
            $this->warehouseDispatchRouteStoreRepository->deleteByDispatchRouteCode($dispatchRouteCode);

            $this->warehouseDispatchRouteMarkerRepository->deleteByDispatchRouteCode($dispatchRouteCode);

            $this->warehouseDispatchRouteRepository->delete($dispatchRoute);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
    //not working
    private function createOrUpdateDispatchRouteWithStores($validatedData)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            //  $validStores = WarehouseDispatchRouteHelper::getDispatchableStores($authWarehouseCode);
            //dd($validStores);
            $validatedData['store_code'] = array_filter($validatedData['store_code']);
            $inputDispatchRoute = [
                'route_name' => $validatedData['route_name'],
                'warehouse_code' => $authWarehouseCode,
                'status' => 'pending'
            ];
            if (isset($validatedData['route_code'])) {

                $dispatchRoute = $this->warehouseDispatchRouteRepository->findByWarehouseCode(
                    $authWarehouseCode, $validatedData['route_code']);

                if ($dispatchRoute->isDispatched()) {
                    throw new Exception('Route already dispatched');
                }

                if ($inputDispatchRoute['route_name'] !== $dispatchRoute->route_name) {
                    $dispatchRoute = $this->warehouseDispatchRouteRepository->update($dispatchRoute, $inputDispatchRoute);
                }

            } else {
                $dispatchRoute = $this->warehouseDispatchRouteRepository->create($inputDispatchRoute);

            }
            $validStoresCodeArr = WarehouseDispatchRouteStoreHelper::getDispatchableStores(
                $authWarehouseCode, $dispatchRoute->wh_dispatch_route_code)->pluck('store_code')->toArray();
            // dd($validStoresCodeArr);
            $toBeStoredStores = [];
            foreach ($validatedData['store_code'] as $key => $storeCode) {
                if (!in_array($storeCode, $validStoresCodeArr)) {
                    throw new Exception('Invalid store');
                }
                array_push($toBeStoredStores, [
                    'wh_dispatch_route_code' => $dispatchRoute->wh_dispatch_route_code,
                    'store_code' => $storeCode,
                    'sort_order' => $key + 1
                ]);
            }

            $this->warehouseDispatchRouteStoreRepository->createMany($toBeStoredStores);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
