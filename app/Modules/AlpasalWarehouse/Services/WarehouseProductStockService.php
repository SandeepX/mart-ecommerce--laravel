<?php


namespace App\Modules\AlpasalWarehouse\Services;


use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductStockRepository;

use Exception;
class WarehouseProductStockService
{

    private $warehouseProductStockRepository,$warehouseProductMasterRepository;
    public function __construct(WarehouseProductStockRepository $warehouseProductStockRepository,
                                WarehouseProductMasterRepository $warehouseProductMasterRepository ){
        $this->warehouseProductStockRepository= $warehouseProductStockRepository;
        $this->warehouseProductMasterRepository= $warehouseProductMasterRepository;
    }

    public function getWarehouseProductStockHistories($warehouseProductMasterCode,$warehouseCode){
        try{
            $with=['product','productVariant'];
            $warehouseProductMaster = $this->warehouseProductMasterRepository->findOrFailProductByCode($warehouseProductMasterCode,
                $warehouseCode,$with);
            $warehouseProductStockHistories =$this->warehouseProductStockRepository->getProductStockHistories($warehouseProductMasterCode);


            return [
                'product_detail' =>$warehouseProductMaster,
                'stock_histories' => $warehouseProductStockHistories
            ];
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function findCurrentProductStockInWarehouse($warehouseProductMasterCode){

        try{
            return $this->warehouseProductStockRepository->findCurrentProductStockInWarehouse($warehouseProductMasterCode);
        }catch (Exception $exception){
            throw $exception;
        }

    }

}
