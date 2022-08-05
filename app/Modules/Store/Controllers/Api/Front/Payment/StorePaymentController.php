<?php

namespace App\Modules\Store\Controllers\Api\Front\Payment;

use App\Http\Controllers\Controller;
use App\Modules\PaymentMethod\Helpers\PaymentFilterHelper;
use App\Modules\PaymentMethod\Resources\PaymentListCollection;
use App\Modules\PaymentMethod\Services\PaymentMethodService;
use Illuminate\Http\Request;
use Exception;

class StorePaymentController extends Controller
{
    protected $paymentMethodService;
    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    public function getAllListsOfPayments(Request $request){
        try{
            $filterParameters = [
                'verification_status' => $request->get('verification_status'),
                'payment_type' => $request->get('payment_type'),
                'payment_method'=> $request->get('payment_method'),
                'paymentDateFrom' => $request->get('payment_date_from'),
                'paymentDateTo' => $request->get('payment_date_to'),
                'amountCondition' => $request->get('amount_condition'),
                'amount' => $request->get('amount'),
                'recordsPerPage' => $request->get('records_per_page'),
                'globalSearchKeyword' => $request->get('search')
            ];
            $storeCode = getAuthStoreCode();
            $storePayments = PaymentFilterHelper::getPaymentAllLists($storeCode,$filterParameters);

           return new PaymentListCollection($storePayments);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function showPaymentDetails($paymentMethod,$paymentCode){
        try{
            $storeCode = getAuthStoreCode();
            $payment = $this->paymentMethodService->getDetailsOfPayment($paymentMethod,$paymentCode,$storeCode);
            return sendSuccessResponse('Payment details Found',$payment);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }


}
