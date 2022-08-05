<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Product\Helpers\ProductCategoryHelper;
use App\Modules\Product\Resources\MinimalProductCollection;
use App\Modules\Product\Services\ProductCategoryService;
//use App\Modules\Product\Services\ProductPriceService;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Services\StoreService;
use Exception;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    protected $productCategoryService, $categoryService,$storeService;

    public function __construct(
        ProductCategoryService $productCategoryService,
        CategoryService $categoryService,
        StoreService $storeService
    ) {
        $this->productCategoryService = $productCategoryService;
        $this->categoryService = $categoryService;
        $this->storeService = $storeService;
    }

    public function getProductsOfCategories(Request $request)
    {
        try {
            $requestedCategories = $request->get('cat_selected');

            if (!$request->filled('cat_selected')) {
                throw new Exception('No Categories Selection');
            }

            $requestedCategories = convertToArray($requestedCategories);

            $minPrice = $request->get('min_price');
            $maxPrice = $request->get('max_price');

            $categories = $this->categoryService->getCategoriesBySlugs($requestedCategories);
            $categoryCodes = $categories->pluck('category_code')->toArray();

            $filterParameters =[
                'category_codes' =>$categoryCodes,
                'min_price'=>$minPrice,
                'max_price'=>$maxPrice
            ];

            if ((auth('api')->check()) && auth('api')->user()->isStoreUser()) {
                $store = $this->storeService->findStoreByCode(getAuthGuardStoreCode());
                if(!($store->status == "approved")){
                    throw new Exception('The store is not approved yet');
                }
                $warehouseCode = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());
                $warehouseCodes = convertToArray($warehouseCode);
                $filterParameters['warehouse_codes']= $warehouseCodes;
                $products = ProductCategoryHelper::filterWarehouseProductsByParameters($filterParameters);
            }else{
                $products =ProductCategoryHelper::filterProductsByParameters($filterParameters);
            }

            return  new MinimalProductCollection($products);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(),  400);
        }
    }
}
