<?php

namespace App\Modules\AlpasalWarehouse\Services;

use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;
use App\Modules\Store\Repositories\StoreWarehouseRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseService
{
    private $warehouseRepository,$userWarehouseService,$storeWarehouseRepository;
    public function __construct(WarehouseRepository $warehouseRepository,
                                UserWarehouseService $userWarehouseService,StoreWarehouseRepository $storeWarehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
        $this->userWarehouseService = $userWarehouseService;
        $this->storeWarehouseRepository= $storeWarehouseRepository;
    }

    public function getAllWarehouses(){
        return $this->warehouseRepository->getAllWarehouses();
    }

    public function getAllClosedWarehouses(){
        return $this->warehouseRepository->getAllWarehousesByType('closed');
    }

    public function getAllOpenWarehouses(){
        return $this->warehouseRepository->getAllWarehousesByType('open');
    }

    public function findWarehouseByCode($warehouseCode){
        return $this->warehouseRepository->findWarehouseByCode($warehouseCode);
    }

    public function findOrFailWarehouseByCode($warehouseCode,$with=[],$select='*'){
        return $this->warehouseRepository->findOrFailByCode($warehouseCode,$with,$select);
    }

    public function findOrFailWarehouseByCodeWith($warehouseCode,array $with){
        return $this->warehouseRepository->findOrFailByCode($warehouseCode,$with);
    }


    public function storeWarehouse($validatedWarehouse){
        DB::beginTransaction();
        try{
            $warehouse = $this->warehouseRepository->storeWarehouse($validatedWarehouse);
            DB::commit();
            return $warehouse;

        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }

    public function storeWarehouseWithAdmin($validatedWarehouse,$validatedAdmin){

        try{
            DB::beginTransaction();
            $warehouseWithUser = $this->userWarehouseService->storeWarehouseWithUserByAdmin($validatedWarehouse,$validatedAdmin,'warehouse-admin');
            DB::commit();
            return $warehouseWithUser;
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }


    public function updateWarehouse($validatedWarehouse, $warehouseCode){
        DB::beginTransaction();
        try{
            $warehouse = $this->warehouseRepository->findOrFailByCode($warehouseCode);
            $warehouse = $this->warehouseRepository->updateWarehouse($validatedWarehouse, $warehouse);
            if ($warehouse->isOpenWarehouseType()){
                $this->storeWarehouseRepository->updateConnectionStatusOfWarehouse($warehouse,0);
            }
            DB::commit();
            return $warehouse;

        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }

//    public function deleteWarehouse($warehouseCode){
//        DB::beginTransaction();
//        try{
//            $warehouse = $this->warehouseRepository->findWarehouseByCode($warehouseCode);
//            $warehouse = $this->warehouseRepository->deleteWarehouse($warehouse);
//            DB::commit();
//            return $warehouse;
//        }catch(Exception $exception){
//            DB::rollBack();
//            throw($exception);
//        }
//    }

//    public function findorFailStoreOrderDispatchDetail($storeOrderCode){
//        return $this->warehouseRepository->findorFailStoreOrderDispatchDetail($storeOrderCode);
//    }

    public function findOrFailStoreByCode($storeCode,$warehouseCode)
    {
        return $this->warehouseRepository->findOrFailStoreByCode($storeCode,$warehouseCode);
    }
    public function getWarehouseUser($warehouseCode)
    {
        return $this->warehouseRepository->getWarehouseUser($warehouseCode);
    }

    public function getOtherWarehouses()
    {
        return $this->warehouseRepository->getOtherWarehouses();
    }
}

