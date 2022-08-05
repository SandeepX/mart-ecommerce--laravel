<?php


namespace App\Modules\AlpasalWarehouse\Repositories\WarehouseStoreGroup;


use App\Modules\AlpasalWarehouse\Models\WhStoreGroup\WarehouseStoreGroup;
use App\Modules\Application\Abstracts\RepositoryAbstract;

class WarehouseStoreGroupRepository extends RepositoryAbstract
{
    public function findByWarehouseCode($warehouseCode,$warehouseStoreGroupCode){
        $warehouseStoreGroup =WarehouseStoreGroup::with($this->with)->select($this->select)
            ->orderBy($this->orderByColumn,$this->orderDirection)
            ->where('warehouse_code',$warehouseCode)
            ->where('wh_store_group_code',$warehouseStoreGroupCode)->first();

        return $warehouseStoreGroup;
    }

    public function create($validatedData){
       $warehouseStoreGroup= WarehouseStoreGroup::create($validatedData);
       return $warehouseStoreGroup->fresh();
    }

    public function update(WarehouseStoreGroup $warehouseStoreGroup,$validatedData){
        $warehouseStoreGroup->update($validatedData);
        return $warehouseStoreGroup->fresh();
    }

    public function delete(WarehouseStoreGroup $warehouseStoreGroup){
        $warehouseStoreGroup->delete();
        return $warehouseStoreGroup;
    }

}
