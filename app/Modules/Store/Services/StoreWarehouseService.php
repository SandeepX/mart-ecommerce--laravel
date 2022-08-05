<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/3/2020
 * Time: 2:41 PM
 */

namespace App\Modules\Store\Services;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Store\Repositories\StoreWarehouseRepository;

use Exception;
use DB;

class StoreWarehouseService
{

    private $warehouseRepository,$storeRepository,$storeWarehouseRepository;

    public function __construct(WarehouseRepository $warehouseRepository,
                                StoreRepository $storeRepository,StoreWarehouseRepository $storeWarehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
        $this->storeRepository = $storeRepository;
        $this->storeWarehouseRepository= $storeWarehouseRepository;
    }

    public function toggleWarehouseStoreConnection(Store $store,Warehouse $warehouse){
        try{
            $storeWarehouseConnection = $this->storeWarehouseRepository->findOrFailStoreWarehouseConnection($store->store_code,$warehouse->warehouse_code);
            if ($warehouse->isOpenWarehouseType()){
                $data['connection_status']= 0; //turning off the connection
            }else{
                $storeWarehouseConnection->connection_status == 1?$data['connection_status'] =0 :$data['connection_status']=1;
            }

            $this->storeWarehouseRepository->updateStoreWarehouseConnection($store,$warehouse,$data['connection_status']);

        }catch (Exception $exception){

            throw $exception;
        }
    }
}