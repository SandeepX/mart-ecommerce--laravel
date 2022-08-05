<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse\ProductCollection;

use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Resources\WarehouseProductApiCollection;
use App\Modules\AlpasalWarehouse\Resources\WarehouseProductCollection;
use App\Modules\AlpasalWarehouse\Resources\WarehouseProductCollectionDetailResource;
use App\Modules\AlpasalWarehouse\Resources\WarehouseProductDetailCollection;
use App\Modules\AlpasalWarehouse\Services\ProductCollection\WarehouseProductCollectionService;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Services\StoreService;

class ProductCollectionApiController extends Controller
{
    protected $warehouseProductCollectionService,$storeService;

    public function __construct(WarehouseProductCollectionService $warehouseProductCollectionService,
    StoreService $storeService
    )
    {
        $this->warehouseProductCollectionService = $warehouseProductCollectionService;
        $this->storeService = $storeService;
    }

    public function index()
    {
        try{
            $store_code=getAuthStoreCode();
            $store = $this->storeService->findStoreByCode($store_code);
            if($store->status == "approved") {

                $warehouse_code = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($store_code);
                $warehouseProductCollections = $this->warehouseProductCollectionService->getWarehouseProductCollections($warehouse_code);
            }else{
                throw new \Exception('The store is not approved yet');
            }
            if(isset($warehouseProductCollections) && $warehouseProductCollections->count())
            {
                return new WarehouseProductCollection($warehouseProductCollections);
            }
            else{
                return sendSuccessResponse('Data is Empty',[]);
            }
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }

    }
    public function warehouseProductCollectionDetail($product_collection_slug)
    {
        try{
            $store_code=getAuthStoreCode();
            $store = $this->storeService->findStoreByCode($store_code);
            if($store->status == "approved") {
                $warehouse_code = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($store_code);
                $warehouseProductCollection = $this->warehouseProductCollectionService->getWarehouseProductCollectionBySlug($product_collection_slug, $warehouse_code);
            }
            else{
                throw new \Exception('The store is not approved yet');
            }
            if(isset($warehouseProductCollection) && $warehouseProductCollection->count())
            {
                $whProductCollection = new WarehouseProductCollectionDetailResource($warehouseProductCollection);
                return sendSuccessResponse('Data Found',$whProductCollection);
            }
            else{
                return sendSuccessResponse('Data is Empty');
            }

        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
    public function warehouseProductsInCollection($product_collection_slug)
    {
        try{
            $store_code=getAuthStoreCode();
            $store = $this->storeService->findStoreByCode($store_code);
            if($store->status == "approved") {
                $warehouse_code = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore($store_code);
                $warehouseProductCollection = $this->warehouseProductCollectionService->getWarehouseProductCollectionBySlug($product_collection_slug, $warehouse_code);
                $warehouseProducts = $this->warehouseProductCollectionService->getWHProductsOfCollectionWithPagination($warehouseProductCollection);
            }else{
                throw new \Exception('The store is not approved yet');
            }
            return new WarehouseProductApiCollection($warehouseProducts);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
