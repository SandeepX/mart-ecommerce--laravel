<?php

namespace App\Modules\PaymentMedium\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\PaymentMedium\Models\DigitalWallet;
use App\Modules\PaymentMedium\Models\Remit;
use function sendErrorResponse;
use function sendSuccessResponse;

class PaymentMediumController extends Controller
{

    public function getRemitsList()
    {
        try{
            $remits = Remit::active()->latest()->get();
            return sendSuccessResponse('Data Found !',  $remits);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


    public function getDigitalWalletsList()
    {
        try{
            $remits = DigitalWallet::active()->latest()->get();
            return sendSuccessResponse('Data Found !',  $remits);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
