<?php

namespace App\Modules\Wallet\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\OfflinePayment\Requests\SaveOfflinePaymentRequest;
use App\Modules\Wallet\Services\WalletLoadBalanceService;
use Exception;

class WalletLoadBalanceApiController extends Controller
{

    protected $walletLoadBalanceService;

    public function __construct(WalletLoadBalanceService $walletLoadBalanceService)
    {
        $this->walletLoadBalanceService = $walletLoadBalanceService;
    }

    public function saveOfflineLoadBalance(SaveOfflinePaymentRequest $request){
        try{
            $validatedData = $request->validated();
            $validatedData['amount'] = roundPrice($validatedData['amount']);

            $offlinePayment = $this->walletLoadBalanceService->saveOfflineLoadBalance($validatedData);

            if($offlinePayment->has_matched == 1){
                $message = 'Congratulation your data is matched, It will take upto 3 office  hours to verify your transaction';
            }else{
                $message = 'Thanks for the payment . It may take upto 12 working hours for the verification';
            }
            return sendSuccessResponse($message);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }



}
