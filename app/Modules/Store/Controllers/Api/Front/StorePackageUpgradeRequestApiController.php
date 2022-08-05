<?php


namespace App\Modules\Store\Controllers\Api\Front;


use App\Http\Controllers\Controller;
use App\Modules\Store\Requests\StorePackageTypes\StorePackageUpgradeRequestCreateRequest;
use App\Modules\Store\Services\StorePackageTypes\StorePackageUpgradeRequestApiService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class StorePackageUpgradeRequestApiController extends Controller
{

    private $storePackageUpgradeRequestApiService;

    public function __construct(
        StorePackageUpgradeRequestApiService $storePackageUpgradeRequestApiService
    ){
        $this->storePackageUpgradeRequestApiService = $storePackageUpgradeRequestApiService;
    }

//    public function getRequestedUpgradePackageByStore($storeTypeCode,Request $request){
//        try{
//            $storeTypePackages = $this->storeTypePackageApiService->getStoreTypePackageOfStoreType($storeTypeCode);
//            $storeTypePackages = StoreTypePackagesResource::collection($storeTypePackages);
//            return sendSuccessResponse('Data Found', $storeTypePackages);
//        }catch (Exception $exception){
//            return sendErrorResponse($exception->getMessage(), $exception->getCode());
//        }
//    }

    public function store(StorePackageUpgradeRequestCreateRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $requestedUpgradePackage = $this->storePackageUpgradeRequestApiService->storeRequestedPackageUpgrade($validatedData);
            return sendSuccessResponse('Package Upgrade Request Sent successfully.Wait For Admin to Approve',$requestedUpgradePackage);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}


