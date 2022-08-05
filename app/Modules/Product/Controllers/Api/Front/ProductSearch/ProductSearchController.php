<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/25/2020
 * Time: 11:13 AM
 */

namespace App\Modules\Product\Controllers\Api\Front\ProductSearch;

use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseHelper;
use App\Modules\Product\Helpers\NavbarProductFilter;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Resources\MinimalProductCollection;
use App\Modules\Product\Resources\ProductListCollection;
use App\Modules\Product\Resources\ProductSearchCollection;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Services\StoreService;
use Illuminate\Http\Request;

use Exception;
use Auth;

class ProductSearchController extends Controller
{
    private $storeService;
    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function searchProductByName(Request $request){

        try{

            // $searchKeyWord = trim($request->keyword);
             $searchKeyWord = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $request->keyword)));

            if (empty($searchKeyWord)){
                return sendErrorResponse('Empty search keyword', 400);
            }

           $warehouseCodes=[];
            if ((Auth::guard('api')->check()) && Auth::guard('api')->user()->isStoreUser()){
                $store = $this->storeService->findStoreByCode(getAuthGuardStoreCode());
                if($store->status === "approved")
                {
                    $warehouseCodes= StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthGuardStoreCode());
                    $warehouseCodes=[$warehouseCodes];
                }else{
                    throw new Exception('The store is not approved yet');
                }
            }

            $filterParameters =[
                'search_keyword' => $searchKeyWord,
                'warehouse_codes'=>$warehouseCodes
            ];
            $products= NavbarProductFilter::newPaginatedSearchOfWarehouseProducts(
                $filterParameters,
                10,
                ['brand','category']
            );

            return new ProductSearchCollection($products);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function oldSearchProductByName(Request $request){

        try{

            $keyword = $request->name;

            if (empty($keyword)){
                return sendErrorResponse('Empty search keyword', 400);
            }

            $keywords = explode(' ', $keyword);

            $productsFromBrand =ProductMaster::verified()->active()->whereHas('brand',function ($brandQuery) use ($keywords){
                $brandQuery->whereIn('brand_name',$keywords);
            });

            $productsFromCategory =ProductMaster::verified()->active()->whereHas('category',function ($brandQuery) use ($keywords){
                $brandQuery->whereIn('category_name',$keywords);
            });
            $products = ProductMaster::verified()->active()->where('product_name','like','%'.$keyword . '%')
                ->union($productsFromBrand)->union($productsFromCategory)->latest()->paginate(8);

            return new MinimalProductCollection($products);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
