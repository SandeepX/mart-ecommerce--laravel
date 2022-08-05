<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 12:51 PM
 */

namespace App\Modules\Store\Controllers\Api\Front\Payment;


use App\Http\Controllers\Controller;

use App\Modules\Store\Requests\Payment\StoreOrderOfflinePaymentRequest;
use App\Modules\Store\Resources\StorePayment\StoreOrderOfflinePaymentDocumentResource;
use App\Modules\Store\Resources\StorePayment\StoreOrderOfflinePaymentResource;
use App\Modules\Store\Services\Payment\StoreOrderOfflinePaymentService;
use Exception;

class StoreOrderOfflinePaymentController extends Controller
{
    private $storeOrderOfflinePaymentService;

    public function __construct(StoreOrderOfflinePaymentService $storeOrderOfflinePaymentService)
    {
        $this->storeOrderOfflinePaymentService = $storeOrderOfflinePaymentService;
    }

    public function getStoreOrderPayments(){
        try{
            $authStoreCode = getAuthStoreCode();
            $storeOrderPayments = $this->storeOrderOfflinePaymentService->getPaginatedStoreOrderPayments($authStoreCode);

            $storeOrderPayments= StoreOrderOfflinePaymentResource::collection($storeOrderPayments);
            return $storeOrderPayments;
            //return sendSuccessResponse('Data Found',  $storeOrderPayments);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


    public function getOfflinePaymentsListByOrderCode($storeOrderCode){
        try{
            $authStoreCode = getAuthStoreCode();
            $storeOrderPayments = $this->storeOrderOfflinePaymentService->getPaymentsByStoreOrderCode($authStoreCode,$storeOrderCode);

            $storeOrderPayments= StoreOrderOfflinePaymentResource::collection($storeOrderPayments);
            return $storeOrderPayments;
            //return sendSuccessResponse('Data Found',  $storeOrderPayments);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function showStoreOrderPayment($storePaymentCode){
        try{

            $authStoreCode = getAuthStoreCode();
            $storePaymentCode = $this->storeOrderOfflinePaymentService->findOrFailStoreOrderPaymentWithEager($storePaymentCode,$authStoreCode);

            $storePaymentCode= new StoreOrderOfflinePaymentResource($storePaymentCode);
            return sendSuccessResponse('Data Found',  $storePaymentCode);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function saveStoreOrderPayment(StoreOrderOfflinePaymentRequest $paymentRequest,$storeOrderCode){

        try{
            $validatedData = $paymentRequest->validated();
            $this->storeOrderOfflinePaymentService->savePayment($validatedData,$storeOrderCode);
            return sendSuccessResponse('Payment for the order submitted successfully');
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}