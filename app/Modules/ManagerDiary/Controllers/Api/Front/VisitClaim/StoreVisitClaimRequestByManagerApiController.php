<?php

namespace App\Modules\ManagerDiary\Controllers\Api\Front\VisitClaim;

use App\Http\Controllers\Controller;
use App\Modules\ManagerDiary\Helpers\StoreVisitClaimRequestsFilter;
use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimRequestByManager;
use App\Modules\ManagerDiary\Requests\VisitClaim\CreateStoreVisitClaimByManagerRequest;
use App\Modules\ManagerDiary\Requests\VisitClaim\ScanStoreVisitClainByStoreRequest;
use App\Modules\ManagerDiary\Requests\VisitClaim\SubmitScannedStoreVisitClaimRequest;
use App\Modules\ManagerDiary\Resources\VisitClaim\StoreVisitClaimRequestCollection;
use App\Modules\ManagerDiary\Resources\VisitClaim\StoreVisitClaimRequestResource;
use App\Modules\ManagerDiary\Resources\VisitClaim\StoreVisitClaimScanResource;
use App\Modules\ManagerDiary\Services\VisitClaim\StoreVisitClaimRequestByManagerService;
use Exception;
use Illuminate\Http\Request;
use function getAuthManagerCode;
use function sendErrorResponse;
use function sendSuccessResponse;

class StoreVisitClaimRequestByManagerApiController extends Controller
{
   private $storeVisitClaimRequestByManagerService;
    public function __construct(StoreVisitClaimRequestByManagerService $storeVisitClaimRequestByManagerService)
    {
        $this->storeVisitClaimRequestByManagerService = $storeVisitClaimRequestByManagerService;
    }

    public function getAllStoreVisitClaimRequestsOfManager(Request $request){
        try{
            $managerCode = getAuthManagerCode();
            $filterParameters = [
                'manager_code' => $managerCode,
                'store_name' => $request->get('store_name'),
                'owner_name' => $request->get('owner_name'),
                'phone_no' => $request->get('phone_no'),
                'status' => $request->get('status'),
                'date_from' => $request->get('date_from'),
                'date_to' => $request->get('date_to'),
                'amount_from' => $request->get('amount_from'),
                'amount_to' => $request->get('amount_to'),
                'is_referred' => $request->get('is_referred'),
                'pan_no' => $request->get('pan_no'),
                'records_per_page' => $request->get('records_per_page')
            ];
            $paginateBy = StoreVisitClaimRequestByManager::PAGINATE_BY;
            $with = ['managerDiary'];
            $storeVisitClaimRequest = StoreVisitClaimRequestsFilter::filterStoreVisitClaimRequestOfManager($filterParameters,$paginateBy,$with);
            return new StoreVisitClaimRequestCollection($storeVisitClaimRequest);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function saveStoreVisitClaimRequestByManager(CreateStoreVisitClaimByManagerRequest $request,$managerDiaryCode){
        try{
            $validatedData = $request->validated();
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerService->saveStoreVisitClaimRequestByManagerDetails(
                                            $managerDiaryCode,
                                            $validatedData
                                        );
            $storeVisitClaimRequest = new StoreVisitClaimRequestResource($storeVisitClaimRequest);
            return sendSuccessResponse('Visit claim requested successfully',$storeVisitClaimRequest);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function scanStoreVisitClaimRequestByStore(
        ScanStoreVisitClainByStoreRequest $request,
        $storeVisitClaimRequestCode
    ){
        try{
            $validatedData = $request->validated();
            $storeCode = getAuthStoreCode();
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerService
                                            ->scanStoreVisitClaimRequestByStore($storeVisitClaimRequestCode,$storeCode,$validatedData);

            $storeVisitClaimRequest = new StoreVisitClaimScanResource($storeVisitClaimRequest);
            return sendSuccessResponse('Visit claim scan successfully',$storeVisitClaimRequest);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function submitScannedStoreVisitClaimRequestByManager(
        SubmitScannedStoreVisitClaimRequest $request,
        $storeVisitClaimRequestCode
    ){
        try{
            $validatedData = $request->validated();
            $managerCode = getAuthManagerCode();
            $storeVisitClaimRequest = $this->storeVisitClaimRequestByManagerService
                                            ->submitScannedStoreVisitClaimRequestDetailsByManager($storeVisitClaimRequestCode,$managerCode,$validatedData);
            $storeVisitClaimRequest = new StoreVisitClaimRequestResource($storeVisitClaimRequest);
            return sendSuccessResponse('Visit claim scan successfully',$storeVisitClaimRequest);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }


}
