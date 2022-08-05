<?php


namespace App\Modules\AlpasalWarehouse\Services;


use App\Modules\AlpasalWarehouse\Repositories\WarehouseUserRepository;

class WarehouseReadService
{
    private $warehouseUserRepository;
    public function __construct(WarehouseUserRepository $warehouseUserRepository)
    {
        $this->warehouseUserRepository = $warehouseUserRepository;
    }

    public function findWarehouseByUserCode($userCode){
        return $this->warehouseUserRepository->findWarehouseByUserCode($userCode);
    }
}
