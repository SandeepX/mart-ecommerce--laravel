<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 12:49 PM
 */

namespace App\Modules\Store\Controllers\Api\Front\Payment;


use App\Http\Controllers\Controller;

use App\Modules\Store\Helpers\StoreMiscPaymentHelper;
use App\Modules\Store\Requests\Payment\StoreMiscellaneousPaymentRequest;
use App\Modules\Store\Requests\Payment\BalanceWithdrawRequest;
use App\Modules\Store\Resources\StorePayment\StoreMiscellaneousPaymentMinimalCollection;
use App\Modules\Store\Resources\StorePayment\StoreMiscellaneousPaymentMinimalResource;
use App\Modules\Store\Resources\StorePayment\StoreMiscellaneousPaymentResource;
use App\Modules\Store\Services\Payment\StorePaymentService;
use Exception;
use Illuminate\Http\Request;

class StoreMiscellaneousPaymentController extends Controller
{
    private $paymentService;

    public function __construct(StorePaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function getMiscellaneousPayments(Request $request){
        try{

            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
            $miscPaymentCode = $request->get('misc_payment_code');
            $verificationStatus = $request->get('verification_status');
            $paymentType = $request->get('payment_type');
            $paymentDateFrom = $request->get('payment_date_from');
            $paymentDateTo = $request->get('payment_date_to');
            $amountCondition = $request->get('amount_condition');
            $amount = $request->get('amount');
            $recordsPerPage = $request->get('records_per_page');

            $globalSearchKeyword = $request->get('search');
            $filterParameters = [
                'store_code' => getAuthStoreCode(),
                'misc_payment_code' => $miscPaymentCode,
                'verification_status' => $verificationStatus,
                'payment_type' => $paymentType,
                'payment_date_from' => $paymentDateFrom,
                'payment_date_to' => $paymentDateTo,
                'amount_condition' => $amountCondition,
                'amount' => $amount,
                'records_per_page'=>$recordsPerPage,
                'global_search_keyword'=>$globalSearchKeyword
            ];

          $storeMiscPayments= StoreMiscPaymentHelper::filterPaginatedStoreMiscPaymentByParameters($filterParameters,10);
           // $storeMiscPayments= StoreMiscPaymentHelper::filterPaginatedGroupedStoreMiscPaymentByParameters($filterParameters,10);
            //$storeMiscPayments = $this->paymentService->getStoreMiscellaneousPayments($authStoreCode);
            //$storeMiscPayments= StoreMiscellaneousPaymentResource::collection($storeMiscPayments);
            //$storeMiscPayments= StoreMiscellaneousPaymentMinimalResource::collection($storeMiscPayments);
            return new StoreMiscellaneousPaymentMinimalCollection($storeMiscPayments);
            //return sendSuccessResponse('Data Found',  $storeMiscPayments);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function showMiscellaneousPayment($miscPaymentCode){
        try{
            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
            $authStoreCode = getAuthStoreCode();
            $storeMiscPayment = $this->paymentService->findOrFailStoreMiscellaneousPaymentWithEager($miscPaymentCode,$authStoreCode);
            $storeMiscPayment= new StoreMiscellaneousPaymentResource($storeMiscPayment);
            return sendSuccessResponse('Data Found',  $storeMiscPayment);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function saveMiscellaneousPayment(StoreMiscellaneousPaymentRequest $paymentRequest){

        try{
            throw new Exception('Misc Payment has shifted to offline payments so respond from there :(');
            $validatedData = $paymentRequest->validated();
            $validatedData['amount'] = roundPrice($validatedData['amount'] );
            $storeMiscPayment = $this->paymentService->saveOfflinePayment($validatedData);

            if($storeMiscPayment->has_matched == 1){
                 $message = 'Congratulation your data is matched, It will take upto 3 office  hours to verify your transaction';
            }else{
                $message = 'Thanks for the payment . It may take upto 12 working hours for the verification';
            }
            return sendSuccessResponse($message);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
