<?php


namespace App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute;


use App\Modules\AlpasalWarehouse\Models\WhStoreDispatch\WarehouseDispatchRoute;
use App\Modules\Application\Abstracts\RepositoryAbstract;

class WarehouseDispatchRouteRepository extends RepositoryAbstract
{
    public function findByWarehouseCode($warehouseCode,$routeCode){
        $dispatchRoute = WarehouseDispatchRoute::with($this->with)->select($this->select)
            ->where('warehouse_code',$warehouseCode)
            ->where('wh_dispatch_route_code',$routeCode)->first();
        return $dispatchRoute;
    }

    public function create($validatedData){
        return WarehouseDispatchRoute::create($validatedData)->fresh();
    }

    public function update(WarehouseDispatchRoute $warehouseDispatchRoute,$validatedData){
        $warehouseDispatchRoute->update($validatedData);
        return $warehouseDispatchRoute->fresh();
    }

    public function delete(WarehouseDispatchRoute $warehouseDispatchRoute){
        $warehouseDispatchRoute->delete();
    }
}
