<?php


namespace App\Modules\AlpasalWarehouse\Repositories;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Abstracts\RepositoryAbstract;

class WarehouseUserRepository extends RepositoryAbstract
{
    public function findWarehouseByUserCode($userCode){
        $warehouse = Warehouse::with($this->with)->select($this->select)
            ->whereHas('warehouseUser',function ($query) use ($userCode){
                $query->where('user_code',$userCode);
            })->first();

        return $warehouse;
    }
}
