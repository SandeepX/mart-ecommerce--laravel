<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 12/21/2020
 * Time: 4:00 PM


 * */

namespace App\Modules\Store\Controllers\Api\Front\StoreTransaction;


use App\Http\Controllers\Controller;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Store\Resources\StoreBalanceResource\StoreBalanceTransactionCollection;
use App\Modules\Store\Requests\BalanceManagement\BalanceWithdrawRequest;
use App\Modules\Store\Resources\Withdraw\WithdrawRequestListsCollection;
use App\Modules\Store\Resources\Withdraw\WithdrawRequestListsResource;
use App\Modules\Store\Resources\Withdraw\WithdrawRequestDetailResource;
use App\Modules\Store\Services\BalanceManagement\TransactionService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use Exception;
use Illuminate\Http\Request;

class StoreBalanceApiController extends Controller
{
    private $storeBalance;
    private $storeService;
    private $walletTransactionPurposeService;


    public function __construct(
        StoreBalance $storeBalance,
        StoreService $storeService,
        WalletTransactionPurposeService $walletTransactionPurposeService
    ){
        $this->storeBalance = $storeBalance;
        $this->storeService = $storeService;
        $this->walletTransactionPurposeService = $walletTransactionPurposeService;
    }

    public function getAllTransactions(Request $request)
    {
        try{
            $filterParameters = [
                'records_per_page' => $request->get('records_per_page'),
                'transaction_type' => $request->get('transaction_type'),
                'transaction_date_from' => $request->get('transaction_date_from'),
                'transaction_date_to' => $request->get('transaction_date_to'),
            ];

            $store = $this->storeService->findOrFailStoreByCode(getAuthStoreCode());
            $wallet = $store->wallet;

            if(!$wallet){
               throw new Exception('Wallet not found for Authenticated store');
            }

            $allTransactionOfStore = WalletTransactionHelper::getWalletTransactionDetailsWithParameters($wallet->wallet_code,$filterParameters);

            return new StoreBalanceTransactionCollection($allTransactionOfStore);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function getStoreCurrentBalance()
    {
        try{
            $balance=[];
            $storeCode = getAuthStoreCode();
            $store = $this->storeService->findOrFailStoreByCode($storeCode);

            $storeTotalBalance = $this->storeBalance->getStoreWalletCurrentBalance($store);
            $storeFreezeBalanceDetails = $this->storeBalance->getStoreFreezeBalanceDetails($store);

            $storeCurrentBalance = roundPrice($storeTotalBalance - $storeFreezeBalanceDetails['total_freeze_amount']);
            $balance['store_total_balance']=$storeTotalBalance;
            $balance['store_active_balance']=$storeCurrentBalance;
            $balance['store_freeze_balance']=$storeFreezeBalanceDetails;
            return sendSuccessResponse('Store Balance Found',$balance);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function getDataForStoreTransactionFilter(){

        try{

            $store = getAuthStore();
            $userTypeCode = $store->storeUserTypeCode();

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
