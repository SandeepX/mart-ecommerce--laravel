<?php

namespace App\Modules\Product\Controllers\Api\Front\RelatedProduct;

use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseHelper;
use App\Modules\Product\Helpers\ProductCategoryHelper;
use App\Modules\Product\Resources\MinimalProductResource;
use App\Modules\Product\Resources\ProductListResource;
use App\Modules\Product\Resources\RelatedProductResource;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\RelatedProduct\RelatedProductService;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use Exception;

use Auth;
class RelatedProductController extends Controller
{
    private $productService;
    private $relatedProductService;
    public function __construct(ProductService $productService, RelatedProductService $relatedProductService)
    {
        $this->productService = $productService;
        $this->relatedProductService = $relatedProductService;
    }

    public function relatedProducts($productSlug)
    {
       // dd($productSlug);
        try{
            $product = $this->productService->findOrFailProductBySlug($productSlug);

            $warehouseCodes=[];

            if ((Auth::guard('api')->check()) && Auth::guard('api')->user()->isStoreUser()){
                $warehouseCodes= StoreWarehouseHelper::getActiveWarehousesCodeAssociatedWithStore(getAuthGuardStoreCode());
                //dd($warehouseCodes);
                $openWarehouseCodes = WarehouseHelper::getAllOpenWarehousesCode();
                $warehouseCodes=array_unique(array_merge($warehouseCodes,$openWarehouseCodes));
                //dd($warehouseCodes);
            }

            $filterParameters =[
                'category_codes'=>[$product->category_code],
                'warehouse_codes'=>$warehouseCodes
                // 'has_price' => $request->get('has_price')
            ];

            $with=[
                'category:category_code,category_name',
                'brand:brand_code,brand_name'
            ];

            $relatedProducts = ProductCategoryHelper::filterPaginatedRelatedProducts($filterParameters,
                8,$product->product_code,$with);
            //$relatedProducts = $this->relatedProductService->relatedProducts($product);
            $relatedProducts = RelatedProductResource::collection($relatedProducts);

            return sendSuccessResponse('Data Found!', $relatedProducts);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }


    }
}
