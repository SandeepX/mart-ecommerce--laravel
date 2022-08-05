<?php


namespace App\Modules\AlpasalWarehouse\Services;


use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductPriceRepository;
use Exception;
class WarehouseProductPriceService
{

    private $warehouseProductPriceRepository,$warehouseProductMasterRepository;
    public function __construct(WarehouseProductPriceRepository $warehouseProductPriceRepository,
                                WarehouseProductMasterRepository $warehouseProductMasterRepository ){
        $this->warehouseProductPriceRepository= $warehouseProductPriceRepository;
        $this->warehouseProductMasterRepository= $warehouseProductMasterRepository;
    }

    public function getWarehouseProductPriceHistories($warehouseProductMasterCode,$warehouseCode){
        try{
            $with=['product','productVariant'];
            $warehouseProductMaster = $this->warehouseProductMasterRepository->findOrFailProductByCode($warehouseProductMasterCode,
                $warehouseCode,$with);
            $warehouseProductPriceHistories =$this->warehouseProductPriceRepository->getProductPriceHistories($warehouseProductMasterCode);


            return [
                'product_detail' =>$warehouseProductMaster,
                'price_histories' => $warehouseProductPriceHistories
            ];
            //return $warehouseProductPriceHistories;
        }catch (Exception $exception){
            throw $exception;
        }
    }
    public function getWarehouseProductPriceInfo($warehouseProductMasterCode,$warehouseCode){
        try{
            $with=[
                'product',
                'product.package.packageType',
                'product.vendor',
                'product.brand',
                'product.category',
                'productVariant',
                'warehouseProductPriceMaster',
                'warehouseProductStockView'
            ];
            $hasProductVariants = false;
            $warehouseProduct = $this->warehouseProductMasterRepository->findOrFailProductByCode($warehouseProductMasterCode,
                $warehouseCode,$with);

            $productVariants= $this->warehouseProductMasterRepository->getProductVariants($warehouseCode,$warehouseProduct->product_code);

            if (count($productVariants) > 0){
                $hasProductVariants=true;
            }

            $warehouseProduct['product_variants']=$productVariants;
            $warehouseProduct['has_product_variants']=$hasProductVariants;

            ///$warehouseProduct->setAttribute('product_variants', $productVariants);

            return $warehouseProduct;
        }catch (Exception $exception){
            throw $exception;
        }

    }
}
