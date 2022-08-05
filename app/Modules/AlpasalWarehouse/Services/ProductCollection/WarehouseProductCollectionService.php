<?php

namespace App\Modules\AlpasalWarehouse\Services\ProductCollection;


use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductCollectionHelper;
use App\Modules\AlpasalWarehouse\Repositories\ProductCollection\WarehouseProductCollectionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseProductCollectionService
{
    private $productCollectionRepo;

    public function __construct(WarehouseProductCollectionRepository $warehouseProductCollectionRepository)
    {
        $this->productCollectionRepo = $warehouseProductCollectionRepository;
    }

    public function findOrFailWHProductCollectionByCode($warehouse_code, $warehouseproductCollectionCode)
    {

        return $this->productCollectionRepo->findOrFailWHProductCollectionByCode($warehouse_code, $warehouseproductCollectionCode);
    }

    public function storeProductCollection($validated)
    {
        try {
            $warehouseproductCollection = $this->productCollectionRepo->createProductCollection($validated);
            return $warehouseproductCollection;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateProductCollection($validated, $warehouseproductCollectionCode)
    {
        try {
            $warehouse_code = getAuthWarehouseCode();
            $warehouseproductCollection = $this->findOrFailWHProductCollectionByCode($warehouse_code, $warehouseproductCollectionCode);
            $warehouseproductCollection = $this->productCollectionRepo->updateProductCollection($validated, $warehouseproductCollection);
            return $warehouseproductCollection;
        } catch (Exception $exception) {
            throw $exception;

        }
    }

    public function deleteProductCollection($warehouseproductCode)
    {
        try {
            $warehouse_code = getAuthWarehouseCode();
            $warehouseproductCollection = $this->productCollectionRepo->findOrFailWHProductCollectionByCode($warehouse_code, $warehouseproductCode);
            return $this->productCollectionRepo->deleteProductCollection($warehouseproductCollection);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getProductsInCollection($warehouseproductCollection)
    {
//        dd($warehouseproductCollection);
        return $this->productCollectionRepo->getProductsInCollection($warehouseproductCollection);
    }



    public function addProductsToCollection($warehouseproductCollection, array $productCodes,$productsInCollection)
    {
        try {
            $this->productCollectionRepo->addProductsToCollection($warehouseproductCollection, $productCodes,$productsInCollection);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function removeProductsFromCollection($warehouseproductCollectionCode, $productMasterCode)
    {
        try {
            $warehouse_code = getAuthWarehouseCode();
            $warehouseproductCollection = $this->findOrFailWHProductCollectionByCode($warehouse_code, $warehouseproductCollectionCode);
            if (!in_array($productMasterCode, $warehouseproductCollection->warehouseProductMasters->pluck('warehouse_product_master_code')->toArray())) {
                throw new Exception('No Such Product in Collection !');
            }
            $this->productCollectionRepo->removeProductsFromCollection($warehouseproductCollectionCode, $productMasterCode);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateActiveStatus($warehouse_code,$productCollectionCode, $productMasterCode)
    {

        try {
            $product = $this->productCollectionRepo->findWHProductByCode($warehouse_code,$productCollectionCode, $productMasterCode);
//            dd($product);
            DB::beginTransaction();
            $product->is_active == 1 ? $data['is_active'] = 0 : $data['is_active'] = 1;
            $this->productCollectionRepo->updateActiveStatus($data,$productCollectionCode, $productMasterCode);
            DB::commit();
            return $product;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function getWarehouseProductCollections($warehouse_code)
    {
        try {
            return $this->productCollectionRepo->getWarehouseProductCollections($warehouse_code);
        } catch (Exception $exception) {
            throw $exception;
        }
    }



    public function getWarehouseProductCollectionBySlug($product_collection_slug, $warehouse_code)
    {
        try {
            return $this->productCollectionRepo->getWarehouseProductCollectionBySlug($product_collection_slug, $warehouse_code);
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public function getWHProductsOfCollectionWithPagination($warehouseProductCollection)
    {
        return WarehouseProductCollectionHelper::getWHProductsOfCollectionWithPagination($warehouseProductCollection);
    }
    public function updateWHProductCollectionStatus($warehouse_code,$productCollectionCode){

        try {
            $productCollection = $this->productCollectionRepo->findWHProductCollectionByCode($warehouse_code, $productCollectionCode);
//            dd($productCollection);
            DB::beginTransaction();
            $productCollection->is_active == 1 ? $data['is_active'] = 0 : $data['is_active'] = 1;
            $this->productCollectionRepo->updateWHProductCollectionActiveStatus($data, $productCollection);
            DB::commit();
            return $productCollection;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
}
}
