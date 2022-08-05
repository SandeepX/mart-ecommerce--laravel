<?php


namespace App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute;


use App\Modules\AlpasalWarehouse\Models\WhStoreDispatch\WarehouseDispatchRouteStoreOrder;
use App\Modules\Application\Abstracts\RepositoryAbstract;
use Carbon\Carbon;
use Exception;
class WarehouseDispatchRouteStoreOrderRepository extends RepositoryAbstract
{

    public function getByDispatchRouteCode($dispatchRouteCode){
        $storeOrders = WarehouseDispatchRouteStoreOrder::with($this->with)
            ->select($this->select)
            ->whereHas('warehouseDispatchRouteStore.warehouseDispatchRoute',function ($q) use ($dispatchRouteCode){
                $q->where('wh_dispatch_route_code',$dispatchRouteCode);
            })
            ->orderBy($this->orderByColumn,$this->orderDirection)
            ->skip($this->skip)
            ->take($this->take)
            ->get();

        return $storeOrders;
    }

    public function getByDispatchRouteStoresCode(array $dispatchRouteStoresCode){
        $storeOrders = WarehouseDispatchRouteStoreOrder::with($this->with)
            ->select($this->select)
            ->whereIn('wh_dispatch_route_store_code',$dispatchRouteStoresCode)
            ->orderBy($this->orderByColumn,$this->orderDirection)
            ->skip($this->skip)
            ->take($this->take)
            ->get();

        return $storeOrders;
    }

    public function createMany($validatedArrayData){
        try {
            $routeStoreOrder = new WarehouseDispatchRouteStoreOrder();
            $latestPrimaryCode = $routeStoreOrder->generatePrimaryCode();
            // dd($validatedArrayData);
            $validatedArrayData= array_map(function ($validatedData) use ($routeStoreOrder,&$latestPrimaryCode,&$toBeReturnedData){
                $currentDateTime =Carbon::now();
                $validatedData['wh_dispatch_route_store_order_code'] = $latestPrimaryCode;
                $validatedData['created_by'] = getAuthUserCode();
                $validatedData['created_at'] = $currentDateTime;
                $validatedData['updated_at'] = $currentDateTime;
                $latestPrimaryCode = $routeStoreOrder->incrementPrimaryCodeWithOutZeroPadding(
                    $latestPrimaryCode,WarehouseDispatchRouteStoreOrder::MODEL_PREFIX);

                //only fillables
                $routeStoreOrderArray = array_filter($validatedData, function ($k) use ($routeStoreOrder) {
                    return in_array($k, $routeStoreOrder->getFillables());
                }, ARRAY_FILTER_USE_KEY); //gettin only fillables array

                return $routeStoreOrderArray;

            },$validatedArrayData);
            WarehouseDispatchRouteStoreOrder::insert($validatedArrayData);
            return $validatedArrayData;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteByCodes(array $routeStoreOrderCode){
        WarehouseDispatchRouteStoreOrder::whereIn('wh_dispatch_route_store_order_code',$routeStoreOrderCode)->delete();
    }
}
