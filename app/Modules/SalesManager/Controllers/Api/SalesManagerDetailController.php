<?php


namespace App\Modules\SalesManager\Controllers\Api;

use App\Modules\SalesManager\Resources\ListOfStoresReferredByManagerCollection;
use App\Modules\SalesManager\Resources\ManagerDetailResource;
use App\Modules\SalesManager\Resources\StoreStatusDetailResource;
use App\Modules\SalesManager\Resources\VendorTargetIncentative\VendorTargetIncentativeCollectionForSalesmanager;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Vendor\Resources\VendorTargetMaster\VendorTargetListCollection;
use App\Modules\SalesManager\Services\VendorTargetService;
use Exception;
use Illuminate\Http\Request;


class SalesManagerDetailController
{
    private $salesManagerService;

    public function __construct(SalesManagerService $salesManagerService

    ){
        $this->salesManagerService = $salesManagerService;
    }

    public function getManagerDetail()
    {
        try{
//            $managerDetail = $this->salesManagerService->findOrFailSalesManagerByCodeWith(getAuthUserCode());
            $data = new ManagerDetailResource(auth()->user()->manager);
            return sendSuccessResponse('Data Found', $data);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
