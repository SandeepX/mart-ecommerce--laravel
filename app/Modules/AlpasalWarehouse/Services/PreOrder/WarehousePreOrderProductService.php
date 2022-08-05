<?php


namespace App\Modules\AlpasalWarehouse\Services\PreOrder;


use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderProductRepository;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderDetailRepository;
use Illuminate\Support\Facades\DB;

use Exception;
class WarehousePreOrderProductService
{
    private $warehousePreOrderRepository,$warehousePreOrderProductRepository,$storePreOrderDetailRepository;

    public function __construct(WarehousePreOrderRepository $warehousePreOrderRepository,
                                WarehousePreOrderProductRepository $warehousePreOrderProductRepository,
                                StorePreOrderDetailRepository $storePreOrderDetailRepository
    ){
        $this->warehousePreOrderRepository= $warehousePreOrderRepository;
        $this->warehousePreOrderProductRepository = $warehousePreOrderProductRepository;
        $this->storePreOrderDetailRepository = $storePreOrderDetailRepository;
    }

    public function updateActiveStatus($warehousePreOrderCode,$preOrderProductCode)
    {

        try {

            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode, getAuthWarehouseCode());
            if ($warehousePreOrder->isFinalized()){
                throw new Exception('Cannot update after finalization.');
            }
            $warehousePreOrderProduct = $this->warehousePreOrderProductRepository->findOrFailPreOrderProductByPreOrderCode($preOrderProductCode,$warehousePreOrderCode);


            DB::beginTransaction();
            $warehousePreOrderProduct->is_active == 1 ? $data['is_active'] = 0 : $data['is_active'] = 1;
            $this->warehousePreOrderProductRepository->updateActiveStatus($warehousePreOrderProduct,$data);
            DB::commit();
            return $warehousePreOrderProduct;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }

    }


    public function deleteWarehousePreOrderProduct($warehousePreOrderCode,$preOrderProductCode){
        try{
            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode, getAuthWarehouseCode());
            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot delete product after finalization time.');
            }
            $with=['storePreOrderDetails'];
            $warehousePreOrderProduct = $this->warehousePreOrderProductRepository
                ->findOrFailPreOrderProductByPreOrderCode($preOrderProductCode,$warehousePreOrderCode,$with);
            if ($warehousePreOrderProduct->hasBeenOrderedByStore()){

                throw new Exception('Cannot delete:product has orders,you might want do deactivate instead.');
            }

            DB::beginTransaction();
            $this->warehousePreOrderProductRepository->deleteWarehousePreOrderProduct($warehousePreOrderProduct);
            DB::commit();
            return $warehousePreOrderProduct;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function cloneWarehouseProductsByListingCode($warehouseCode,$preOrderListingCode,$createdBy){
        return $this->warehousePreOrderProductRepository->cloneWarehouseProductsByListingCode($warehouseCode,$preOrderListingCode,$createdBy);
    }

    public function cloneProductsFromSourceToDestinationListingCode($validatedData){
           return $this->warehousePreOrderProductRepository->cloneProductsFromSourceToDestinationListingCode($validatedData);
    }
    public function cloneProductsFromVendorToPreOrderListing($validatedData){
        return $this->warehousePreOrderProductRepository->cloneProductsFromVendorToPreOrderListing($validatedData);
    }

    public function deletePreOrderProductByProductCode($warehousePreOrderListingCode,$preOrderProductCode)
    {
        DB::beginTransaction();
        try{
            $warehouseProductOrderedByStore = $this->warehousePreOrderProductRepository->getWarehousePreorderProductOrderedByStore($warehousePreOrderListingCode,$preOrderProductCode);
            if($warehouseProductOrderedByStore != 0){
                throw new Exception('cannot remove the product from warehouse preorder listing,
                                            product is already ordered'
                );
            }
            $deletePreorderProduct = $this->warehousePreOrderProductRepository->deletePreOrderProductByProductCode($warehousePreOrderListingCode,$preOrderProductCode);
            DB::commit();
            return $deletePreorderProduct;
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteAllPreOrderProducts($warehousPreOrderListingCode)
    {
        return $this->warehousePreOrderProductRepository->massDeletePreorderProduct($warehousPreOrderListingCode);
    }

    public function changeAllWarehousePreorderProductStatus($validatedData)
    {
        try{
            DB::beginTransaction();
            $status  = $this->warehousePreOrderProductRepository->changeStatusOfPreOrderproductsByWarehousePreorderListingCode($validatedData);
            DB::commit();
            return $status;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeAllWarehousePreorderProductStatusofVendor($warehousePreOrderListingCode,$vendorCode,$status){

        try{
            $warehousePreOrderListing =  $this->warehousePreOrderRepository->findOrFailPreOrderByCode($warehousePreOrderListingCode);

            if($warehousePreOrderListing->isFinalized()){
                throw new Exception('Cannot Change Status After Pre Order Listing Is Finalized');
            }
            if($status == 'active'){
                $status = 1;
            }else{
                $status = 0;
            }
            DB::beginTransaction();
              $preOrderProducts = $this->warehousePreOrderProductRepository->changeAllWarehousePreorderProductStatusofVendor($warehousePreOrderListingCode,$vendorCode,$status);
            DB::commit();

            return $preOrderProducts;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeStatusOfallVariantsinProduct($warehousePreOrderCode,$productCode,$status){

        try{
            $warehousePreOrderListing =  $this->warehousePreOrderRepository->findOrFailPreOrderByCode($warehousePreOrderCode);

            if($warehousePreOrderListing->isFinalized()){
                throw new Exception('Cannot Change Status After Pre Order Listing Is Finalized');
            }
            if($status == 'active'){
                $status = 1;
            }else{
                $status = 0;
            }

            DB::beginTransaction();
            $preOrderProducts = $this->warehousePreOrderProductRepository->changeStatusOfallVariantsinProduct($warehousePreOrderCode,$productCode,$status);
            DB::commit();

            return $preOrderProducts;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }


//    public function deletePreOrderProductByProductCode($warehousePreOrderListingCode,$preOrderProductCode)
//    {
//        DB::beginTransaction();
//        try{
//            $select = ['warehouse_preorder_product_code'];
//            $warehousePreorderProduct = $this->warehousePreOrderProductRepository->getWarehousePreorderProductByWarehouseListingCodeAndProductCode(
//                $warehousePreOrderListingCode,
//                $preOrderProductCode,
//                $select
//            );
//            if($warehousePreorderProduct) {
//                $count = 0;
//                foreach ($warehousePreorderProduct as $key => $value) {
//                    $storePreorderDetailCount = $this->storePreOrderDetailRepository->getStorePreoderDetailByWarehousePreorderProductCode($value->warehouse_preorder_product_code);
//                    $count = $count + $storePreorderDetailCount;
//                }
//            }
//           if($count != 0){
//               throw new Exception('cannot remove the product from warehouse preorder listing,
//                                            product is already ordered'
//               );
//           }
//
//             $deletePreorderProduct = $this->warehousePreOrderProductRepository->deletePreOrderProductByProductCode($warehousePreOrderListingCode,$preOrderProductCode);
//            DB::commit();
//             return $deletePreorderProduct;
//        }catch(Exception $exception){
//            DB::rollBack();
//            throw $exception;
//        }
//
//    }
}
