<?php


namespace App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute;


use App\Modules\AlpasalWarehouse\Models\WhStoreDispatch\WarehouseDispatchRouteStore;
use App\Modules\Application\Abstracts\RepositoryAbstract;
use Carbon\Carbon;
use Exception;

class WarehouseDispatchRouteStoreRepository extends RepositoryAbstract
{
    public function findByCode($whDispatchRouteStoreCode){

        $whDispatchRouteStore =WarehouseDispatchRouteStore::with($this->with)->select($this->select)
            ->where('wh_dispatch_route_store_code',$whDispatchRouteStoreCode)->first();

        return $whDispatchRouteStore;
    }

    public function getByDispatchRouteCode($dispatchRouteCode,$warehouseCode){

        $whDispatchRouteStores =WarehouseDispatchRouteStore::with($this->with)
            ->select($this->select)
            ->where('wh_dispatch_route_code',$dispatchRouteCode)
            ->whereHas('warehouseDispatchRoute',function ($query) use ($warehouseCode){
                $query->where('warehouse_code',$warehouseCode);
            })
            ->orderBy($this->orderByColumn,$this->orderDirection)
            ->skip($this->skip)
            ->take($this->take)
            ->get();

        return $whDispatchRouteStores;
    }

    public function getRouteStoresHavingZeroOrders($dispatchRouteCode){
        $whDispatchRouteStores =WarehouseDispatchRouteStore::with($this->with)
            ->select($this->select)
            ->where('wh_dispatch_route_code',$dispatchRouteCode)
            ->doesntHave('warehouseDispatchRouteStoreOrders')
            ->orderBy($this->orderByColumn,$this->orderDirection)
            ->skip($this->skip)
            ->take($this->take)
            ->get();

        return $whDispatchRouteStores;
    }
    public function createMany($validatedArrayData){
        try {
            $routeStore = new WarehouseDispatchRouteStore();
            $latestPrimaryCode = $routeStore->generatePrimaryCode();
            // dd($validatedArrayData);
            $validatedArrayData= array_map(function ($validatedData) use ($routeStore,&$latestPrimaryCode,&$toBeReturnedData){
                $currentDateTime =Carbon::now();
                $validatedData['wh_dispatch_route_store_code'] = $latestPrimaryCode;
                $validatedData['created_by'] = getAuthUserCode();
                $validatedData['updated_by'] = getAuthUserCode();
                $validatedData['created_at'] = $currentDateTime;
                $validatedData['updated_at'] = $currentDateTime;
                $latestPrimaryCode = $routeStore->incrementPrimaryCodeWithOutZeroPadding(
                    $latestPrimaryCode,WarehouseDispatchRouteStore::MODEL_PREFIX);

                //only fillables
                $routeStoreArray = array_filter($validatedData, function ($k) use ($routeStore) {
                    return in_array($k, $routeStore->getFillables());
                }, ARRAY_FILTER_USE_KEY); //gettin only fillables array

                return $routeStoreArray;

            },$validatedArrayData);
            //dd($validatedArrayData);
            WarehouseDispatchRouteStore::insert($validatedArrayData);
            return $validatedArrayData;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update(WarehouseDispatchRouteStore $warehouseDispatchRouteStore,$validatedData){
        $warehouseDispatchRouteStore->update($validatedData);
        $warehouseDispatchRouteStore->fresh();
    }

    public function deleteByDispatchRouteCode($dispatchRouteCode){
        WarehouseDispatchRouteStore::where('wh_dispatch_route_code',$dispatchRouteCode)->delete();
    }

    public function deleteByCodes(array $routeStoreCode){
        WarehouseDispatchRouteStore::whereIn('wh_dispatch_route_store_code',$routeStoreCode)->delete();
    }
}
