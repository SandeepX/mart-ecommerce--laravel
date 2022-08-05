<?php

namespace App\Modules\AlpasalWarehouse\Repositories;

use App\Modules\AlpasalWarehouse\Models\AlpasalWarehouseType;

class WarehouseTypeRepository
{
    public function getAllWarehouseTypes(){
        return AlpasalWarehouseType::latest()->get();
    }
}

