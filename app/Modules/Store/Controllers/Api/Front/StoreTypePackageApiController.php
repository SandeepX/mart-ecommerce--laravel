<?php


namespace App\Modules\Store\Controllers\Api\Front;


use App\Http\Controllers\Controller;
use App\Modules\Store\Resources\StoreTypePackage\StoreTypePackagesResource;
use App\Modules\Store\Services\StorePackageTypes\StoreTypePackageApiService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class StoreTypePackageApiController extends Controller
{

    private $storeTypePackageApiService;

    public function __construct(
        StoreTypePackageApiService $storeTypePackageApiService
    ){
        $this->storeTypePackageApiService = $storeTypePackageApiService;
    }

    public function getStoreTypePackageOfStoreType($storeTypeCode,Request $request){
        try{
            $storeTypePackages = $this->storeTypePackageApiService->getStoreTypePackageOfStoreType($storeTypeCode);
            $storeTypePackages = StoreTypePackagesResource::collection($storeTypePackages);
            return sendSuccessResponse('Data Found', $storeTypePackages);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}


