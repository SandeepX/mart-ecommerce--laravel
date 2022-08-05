<?php


namespace App\Modules\SalesManager\Controllers\Api;

use App\Modules\SalesManager\Helpers\SalesManagerFilter;
use App\Modules\SalesManager\Requests\ManagerProfileApiRequest\SalesManagerUpdateDocsRequest;
use App\Modules\SalesManager\Requests\ManagerProfileApiRequest\SalesManagerUpdateProfileRequest;
use App\Modules\SalesManager\Resources\ListOfReferredManagersByManagerCollection;
use App\Modules\SalesManager\Resources\ListOfReferredUserByManagerCollection;
use App\Modules\SalesManager\Resources\ListOfStoresReferredByManagerCollection;
use App\Modules\SalesManager\Resources\ManagerDetailResource;
use App\Modules\SalesManager\Resources\StoreStatusDetailResource;
use App\Modules\SalesManager\Resources\VendorTargetIncentative\VendorTargetIncentativeCollectionForSalesmanager;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\SalesManager\Services\UserSalesManagerService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Vendor\Resources\VendorTargetMaster\VendorTargetListCollection;
use App\Modules\SalesManager\Services\VendorTargetService;
use Exception;
use Illuminate\Http\Request;


class SalesManagerController
{
    private $salesManagerService;
    private $vendorTargetService;
    private $storeService;
    public  $userSalesManagerService;

//    public function __construct(SalesManagerService $salesManagerService,VendorTargetService $vendorTargetService){
//        $this->salesManagerService = $salesManagerService;
//        $this->vendorTargetService =$vendorTargetService;
//    }

    public function __construct(SalesManagerService $salesManagerService,
                                StoreService $storeService,
                                UserSalesManagerService $userSalesManagerService
    ){
        $this->salesManagerService = $salesManagerService;
        $this->storeService = $storeService;
        $this->userSalesManagerService = $userSalesManagerService;
    }

    public function getAllVendorTargetForSalesManager()
    {
        try{
            $vendorTargetsForManager = $this->salesManagerService->getVendorTargetsByLocationCode(getAuthUserCode());
            if(!is_null($vendorTargetsForManager)){
                $data = new VendorTargetListCollection($vendorTargetsForManager);
            }else{
                $data = null;
            }
            return sendSuccessResponse('Data Found', $data);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getAllVendorTargetIncentativeByVTMcode($VTMcode)
    {
        try{
            $vendorTargetIncentativeForManager = $this->vendorTargetService->showVendorTargetIncentative($VTMcode);
            if(!is_null($vendorTargetIncentativeForManager)){
                $data = new VendorTargetIncentativeCollectionForSalesmanager($vendorTargetIncentativeForManager);
            }else{
                $data = null;
            }
            return sendSuccessResponse('Data Found', $data);


        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getStoreByReferralCode(Request $request)
    {
        try{
            $paginateBy = $request->get('records_per_page') ?? 10;
            $referredBy = getAuthManagerCode();
            $storesReferrals = $this->salesManagerService->getStoreByReferralCode($referredBy,$paginateBy);
            return new ListOfStoresReferredByManagerCollection($storesReferrals);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getReferredManagersByReferralCode(Request $request)
    {
        try{
            $paginateBy = $request->get('records_per_page') ?? 10;
            $referredBy = getAuthManagerCode();
            $storesReferrals = $this->salesManagerService->getReferedManagersByReferralCode($referredBy,$paginateBy);
            return new ListOfReferredManagersByManagerCollection($storesReferrals);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getStoreStatusByStoreCode($storeCode)
    {
        try{
            $store = $this->storeService->findStoreByCode($storeCode);
            if(!is_null($store)){
                $data = new StoreStatusDetailResource($store);
            }else{
                $data = null;
            }
            return sendSuccessResponse('Data Found', $data);


        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }



    public function getManagersAllReferralsList(Request  $request){
        try{
            $filterParameters = [
                'user_type' => $request->get('user_type'),
                'user_name' => $request->get('user_name'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'phone_number' => $request->get('phone_number'),
                'manager_code' => getAuthManagerCode(),
                'records_per_page' => $request->get('records_per_page')
            ];
            $managerReferalsLists = SalesManagerFilter::filterPaginatedManagersReferrals($filterParameters,10);
            return (new ListOfReferredUserByManagerCollection($managerReferalsLists))->additional(['meta'=>['user_types'=> [
                'store','manager'
            ]]
            ]);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

}
