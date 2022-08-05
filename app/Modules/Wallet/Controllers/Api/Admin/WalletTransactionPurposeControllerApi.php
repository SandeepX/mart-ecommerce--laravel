<?php


namespace App\Modules\Wallet\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use App\Modules\Wallet\Resources\WalletTransactionPurposeResource;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use Exception;

class WalletTransactionPurposeControllerApi extends Controller
{
    private $walletTransactionPurposeService;

    public function __construct(WalletTransactionPurposeService $walletTransactionPurposeService)
    {
        $this->walletTransactionPurposeService = $walletTransactionPurposeService;
    }

    public function getAllTransactionPurposesByPurposeAndUserType($purposeType,$userTypeCode){

        try{
            $transactionPurposes  = $this->walletTransactionPurposeService->getAllTransactionPurposesByPurposeAndUserType($purposeType,$userTypeCode);
            $transactionPurposes = WalletTransactionPurposeResource::collection($transactionPurposes);
            return sendSuccessResponse('Data Found', $transactionPurposes);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


}
