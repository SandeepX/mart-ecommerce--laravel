<?php


namespace App\Modules\SalesManager\Controllers\Api\Front\SalesManagerTransaction;


use App\Modules\SalesManager\Resources\SalesManagerWallet\SalesManagerWalletTransactionCollection;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SalesManagerBalanceApiController
{

    private $salesManagerService;
    private $walletTransactionPurposeService;


    public function __construct(
        SalesManagerService $salesManagerService,
        WalletTransactionPurposeService $walletTransactionPurposeService
    ){
      $this->salesManagerService = $salesManagerService;
      $this->walletTransactionPurposeService = $walletTransactionPurposeService;
    }

    public function getAllTransactions(Request $request){

        try{
            $filterParameters = [
                'records_per_page' => $request->get('records_per_page'),
                'transaction_type' => $request->get('transaction_type'),
                'transaction_date_from' => $request->get('transaction_date_from'),
                'transaction_date_to' => $request->get('transaction_date_to'),
            ];

            $manager = $this->salesManagerService->findOrFailSalesManagerByCodeWith(getAuthManagerCode(),['wallet']);
            $wallet = $manager->wallet;

            if(!$wallet){
                throw new Exception('Wallet not found for Authenticated Sales Manager');
            }

            $allTransactionOfManager = WalletTransactionHelper::getWalletTransactionDetailsWithParameters($wallet->wallet_code,$filterParameters);
            return new SalesManagerWalletTransactionCollection($allTransactionOfManager);

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getDataForManagerTransactionFilter(){

        try{
          //  $userCode = getAuthUserCode();
            $manager = $this->salesManagerService->findOrFailSalesManagerByCodeWith(getAuthManagerCode(),['user.userType']);
            $userTypeCode = $manager->user->userType->user_type_code;
            $walletTransactionPurposes = $this->walletTransactionPurposeService->getWalletTransactionPurposeByUserTypeCode($userTypeCode);
            $storeTransactionPurposesReformed = [];
            foreach ($walletTransactionPurposes as $walletTransactionPurpose) {
                array_push($storeTransactionPurposesReformed,['code'=>$walletTransactionPurpose->slug, 'name'=>$walletTransactionPurpose->purpose]);
            }

            $data = [
                'transaction_purposes' => $storeTransactionPurposesReformed
            ];
            return sendSuccessResponse('Transaction Purposes - Success', $data);

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
