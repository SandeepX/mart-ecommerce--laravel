<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Modules\Brand\Models\Brand;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Product\Helpers\ProductBrandHelper;
use App\Modules\Product\Resources\MinimalProductCollection;
use App\Modules\Product\Services\ProductService;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Services\StoreService;
use Exception;

class ProductBrandController
{
    protected $productService;
    protected $storeService;
    protected $brandService;
    public function __construct(ProductService $productService,StoreService $storeService,BrandService $brandService){
        $this->productService=$productService;
        $this->storeService=$storeService;
        $this->brandService=$brandService;
    }
    public function productsByBrandSlug($brand,$paginated=12){
        $filterParameters =[
            'paginated'=>$paginated,
        ];
        if ((auth('api')->check()) && auth('api')->user()->isStoreUser()) {
            $store = $this->storeService->findStoreByCode(getAuthGuardStoreCode());
            if(!($store->status == "approved")){
                throw new Exception('The store is not approved yet');
            }
            $brand=$this->brandService->findOrFailBrandBySlug($brand);
            $filterParameters['brand_code']=$brand->brand_code;
            $warehouseCodes = StoreWarehouseHelper::getActiveWarehousesCodeAssociatedWithStore(getAuthGuardStoreCode());
            $filterParameters['warehouse_codes']= $warehouseCodes;
            $products=ProductBrandHelper::getWarehouseProductBrandByCode($filterParameters);
        }else{
            $products= $this->productService->getProductsByBrandSlug($brand,$paginated);
        }
        return new MinimalProductCollection($products);
    }
}
