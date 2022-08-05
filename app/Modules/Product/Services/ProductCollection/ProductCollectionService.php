<?php

namespace App\Modules\Product\Services\ProductCollection;

use App\Modules\Product\Helpers\ProductCollectionHelper;
use App\Modules\Product\Repositories\ProductCollection\ProductCollectionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductCollectionService
{
    private $productCollectionRepo;

    public function __construct(ProductCollectionRepository $productCollectionRepository)
    {
        $this->productCollectionRepo = $productCollectionRepository;
    }

    public function getAllProductCollections()
    {
        return $this->productCollectionRepo->getAllProductCollections();
    }

    public function getActiveProductCollections()
    {
        return $this->productCollectionRepo->getActiveProductCollections();
    }

    public function getActiveProductCollectionsWithProducts()
    {
        return ProductCollectionHelper::getActiveCollectionsWithProducts();
    }

    public function findProductCollectionByCode($productCollectionCode)
    {
        return $this->productCollectionRepo->findProductCollectionByCode($productCollectionCode);
    }

    public function findProductCollectionBySlug($productCollectionSlug)
    {
        return $this->productCollectionRepo->findProductCollectionBySlug($productCollectionSlug);
    }

    public function findOrFailProductCollectionByCode($productCollectionCode)
    {
      return $this->productCollectionRepo->findOrFailProductCollectionByCode($productCollectionCode);
    }

    public function findOrFailProductCollectionBySlug($productCollectionSlug)
    {
        return $this->productCollectionRepo->findOrFailProductCollectionBySlug($productCollectionSlug);
    }

    public function storeProductCollection($validated){
        try{
            $productCollection = $this->productCollectionRepo->createProductCollection($validated);
            return $productCollection;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function updateProductCollection($validated,$productCollectionCode){
        try{
            $productCollection = $this->findOrFailProductCollectionByCode($productCollectionCode);
            $productCollection = $this->productCollectionRepo->updateProductCollection($validated,$productCollection);
            return $productCollection;
        }catch(Exception $exception){
            throw $exception;

        }
    }

    public function deleteProductCollection($productCode){
        try{
            $productCollection = $this->productCollectionRepo->findOrFailProductCollectionByCode($productCode);
            return $this->productCollectionRepo->deleteProductCollection($productCollection);
        }catch(Exception $exception){
            throw $exception;
        }
    }



     /*  ------------ Adding Products to Collection -----------------*/

     public function getProductsOfCollectionWithPagination($collection){
        $collectionCode = $collection->product_collection_code;
        return ProductCollectionHelper::getProductsOfCollectionWithPagination($collectionCode);
       }

     public function getProductsInCollection($productCollection){
      return $this->productCollectionRepo->getProductsInCollection($productCollection);
     }

     public function addProductsToCollection($productCollection,array $productCodes,$productsInCollection)
     {
         try{
            $this->productCollectionRepo->addProductsToCollection($productCollection,$productCodes,$productsInCollection);
         }catch(Exception $exception){
             throw $exception;
         }
     }


     public function removeProductsFromCollection($productCollectionCode,$productCode)
     {
        try{
            $productCollection = $this->findOrFailProductCollectionByCode($productCollectionCode);
            if(!in_array($productCode,$productCollection->products->pluck('product_code')->toArray())){
               throw new Exception('No Such Product in Collection !');
            }
            $this->productCollectionRepo->removeProductsFromCollection($productCollection,$productCode);
         }catch(Exception $exception){
             throw $exception;
         }
     }
    public function updateProductCollectionStatus($productCollectionCode){

        try {
            $productCollection = $this->productCollectionRepo->findProductCollectionByCode($productCollectionCode);
            DB::beginTransaction();
            $productCollection->is_active == 1 ? $data['is_active'] = 0 : $data['is_active'] = 1;
            $this->productCollectionRepo->updateProductCollectionActiveStatus($data, $productCollection);
            DB::commit();
            return $productCollection;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }
    public function updateActiveStatus($productCollectionCode, $productCode)
    {

        try {
            $product = $this->productCollectionRepo->findProductByCode($productCollectionCode,$productCode);
            DB::beginTransaction();
            $product->is_active == 1 ? $data['is_active'] = 0 : $data['is_active'] = 1;
            $this->productCollectionRepo->updateActiveStatus($data,$productCollectionCode, $productCode);
            DB::commit();
            return $product;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }
}
