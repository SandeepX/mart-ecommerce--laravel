<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 12/21/2020
 * Time: 4:00 PM


 * */

namespace App\Modules\Store\Controllers\Api\Front\StoreTransaction\Withdraw;


use App\Http\Controllers\Controller;
use App\Modules\Store\Repositories\Kyc\FirmKycRepository;
use App\Modules\Store\Repositories\Kyc\IndividualKycRepository;
use App\Modules\Store\Requests\BalanceManagement\BalanceWithdrawRequest;
use App\Modules\Store\Requests\BalanceManagement\WithdrawRequestUpdateRequest;
use App\Modules\Store\Resources\Withdraw\WithdrawRequestListsCollection;
use App\Modules\Store\Resources\Withdraw\WithdrawRequestDetailResource;
use App\Modules\Store\Resources\Withdraw\WithdrawRequestListsResource;
use App\Modules\Store\Resources\Withdraw\WithdrawRequestVerificationDetailCollection;
use App\Modules\Store\Resources\Withdraw\WithdrawRequestVerificationDetailResource;
use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Services\Withdraw\WithdrawRequestService;
use Exception;
use Illuminate\Http\Request;

class WithdrawRequestApiController extends Controller
{
    private $withdrawRequestService,$storeService;


    public function __construct(WithdrawRequestService $withdrawRequestService,
    StoreService $storeService

    )
    {
        $this->withdrawRequestService = $withdrawRequestService;
        $this->storeService = $storeService;

    }
    public function saveBalanceWithdrawRequest(BalanceWithdrawRequest $withdrawRequest){

        try{
           // throw new Exception('Withdraw request feature has been halted for short time , we are working on it. !');
            $validatedData = $withdrawRequest->validated();
            $store = $this->storeService->findStoreByCode(getAuthStoreCode());
            if(!($store->status == "approved")){
                throw new Exception('The store is not approved yet');
            }
            $withdrawRequest = $this->withdrawRequestService->saveBalanceWithdrawRequest($validatedData);
            $withdrawRequest = new WithdrawRequestListsResource($withdrawRequest);
            return sendSuccessResponse('Withdraw Request submitted successfully',$withdrawRequest);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

//done by Govinda

    public function getWithdrawRequestLists(Request $request)
    {
        try{
            $paginatedBy=$request->get('records_per_page');
            $storeCode = getAuthStoreCode();
            $withdrawRequestLists = $this->withdrawRequestService->getWithdrawRequestLists($storeCode,$paginatedBy);
            return new WithdrawRequestListsCollection($withdrawRequestLists);
            //return sendSuccessResponse('Store Balance Found',$paymentLists);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getWithdrawRequestListDetail($withdrawRequestCode)
    {
        try{
            $storeCode = getAuthStoreCode();
            $withdrawRequestDetail = $this->withdrawRequestService->getWithdrawRequestListDetail($storeCode,$withdrawRequestCode);
            $withdrawRequestDetail=new WithdrawRequestDetailResource($withdrawRequestDetail);
            return sendSuccessResponse('Withdraw Request Detail Found',$withdrawRequestDetail);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
    public function getWithdrawRequestVerificationDetail($withdrawRequestCode,Request $request)
    {
        try{
            $paginatedBy=$request->get('records_per_page');
            $withdrawRequestVerificationDetail = $this->withdrawRequestService->getWithdrawRequestVerificationDetail($withdrawRequestCode,$paginatedBy);

            return new WithdrawRequestVerificationDetailCollection($withdrawRequestVerificationDetail);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function cancelBalanceWithdrawRequestByStore(WithdrawRequestUpdateRequest $request)
    {
        try{
            $withdrawRequestCode = $request->validated();
            $this->withdrawRequestService->cancelBalanceWithdrawRequestBystore($withdrawRequestCode);
            return sendSuccessResponse('Withdraw Request Cancelled Successfully');
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
