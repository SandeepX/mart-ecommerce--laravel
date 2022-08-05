<?php


namespace App\Modules\Wallet\Controllers\Api\Front;


use App\Http\Controllers\Controller;
use App\Modules\Wallet\Services\WalletService;
use Exception;

class WalletController extends Controller
{
    private $walletService;

    public function __construct(
         WalletService $walletService
    ){
        $this->walletService = $walletService;

    }

    public function getCurrentBalance(){

        try{
            $authUser = auth()->user();
            $currentBalanceDetails = $this->walletService->getWalletCurrentBalance($authUser);

            return sendSuccessResponse('Current Balance Found',$currentBalanceDetails);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

}
