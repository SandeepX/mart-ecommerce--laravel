<?php

namespace App\Modules\AlpasalWarehouse\Services;

use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseTypeRepository;

class WarehouseTypeService
{
    private $warehouseTypeRepository;
    public function __construct(WarehouseTypeRepository $warehouseTypeRepository)
    {
        $this->warehouseTypeRepository = $warehouseTypeRepository;
    }

    public function getAllWarehouseTypes(){
        return $this->warehouseTypeRepository->getAllWarehouseTypes();
    }
}

