<?php

namespace App\Modules\Brand\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Brand\Helpers\BrandProductsHelper;
use App\Modules\Brand\Resources\BrandDetailsCollection;
use App\Modules\Brand\Resources\BrandDetailsResource;
use App\Modules\Brand\Resources\BrandResource;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Product\Resources\MinimalProductResource;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index()
    {
        try{
            $brands = $this->brandService->getAllBrands();
            return sendSuccessResponse('Data Found',  $brands);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }

    }
    public function featuredBrand(Request $request){
        $limit=$request->get('limit') ?? 8;
        $filterParameters=[
            'limit'=>$limit,
        ];
        try{
            if ((auth('api')->check()) && auth('api')->user()->isStoreUser()) {
                $warehouseCodes = StoreWarehouseHelper::getActiveWarehousesCodeAssociatedWithStore(getAuthGuardStoreCode());
                $filterParameters['warehouseCodes']=$warehouseCodes;
            }
            $featuredBrand=BrandProductsHelper::getBrandProductsCount($filterParameters);
//            return $featuredBrand;
            $brands = BrandResource::collection($featuredBrand);
            return sendSuccessResponse('Data Found',  $brands);
        }
        catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
    public function brandDetails($brandSlug){
        try{
            $brandDetails= $this->brandService->brandDetails($brandSlug);
            $details= new BrandDetailsResource($brandDetails);
            return sendSuccessResponse('Data Found',  $details);
        }
        catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }

    }

}
