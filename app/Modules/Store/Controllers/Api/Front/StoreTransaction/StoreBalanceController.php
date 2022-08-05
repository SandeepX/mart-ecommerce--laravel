<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 12/21/2020
 * Time: 4:00 PM


 * */

namespace App\Modules\Store\Controllers\Api\Front\StoreTransaction;


use App\Http\Controllers\Controller;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Resources\StoreBalanceResource\StoreBalanceTransactionCollection;
use App\Modules\Store\Requests\BalanceManagement\BalanceWithdrawRequest;
use App\Modules\Store\Services\BalanceManagement\TransactionService;
use Exception;
use Illuminate\Http\Request;

class StoreBalanceController extends Controller
{
    private $balanceService;

    public function __construct(TransactionService $transactionService)
    {
        $this->balanceService = $transactionService;
    }

    public function saveBalanceWithdrawRequest(BalanceWithdrawRequest $withdrawRequest){

        try{
            $validatedData = $withdrawRequest->validated();
            $this->balanceService->saveBalanceWithdrawRequest($validatedData);
            return sendSuccessResponse('Withdraw Request submitted successfully');
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getAllTransactions(Request $request)
    {

       // return ('hello');
         try{
            $filterParameters = [
                'store_code' => getAuthStoreCode(),
                'records_per_page' => $request->get('records_per_page'),
                'transaction_type' => $request->get('transaction_type'),
                'transaction_date_from' => $request->get('transaction_date_from'),
                'transaction_date_to' => $request->get('transaction_date_to'),
            ];


            $allTransactionByStoreCode = StoreTransactionHelper::filterPaginatedStoreAllTransactionByParameters($filterParameters);


            return new StoreBalanceTransactionCollection($allTransactionByStoreCode);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function getStoreCurrentBalance()
    {
        try{
            $storeCode = getAuthStoreCode();
            $storeCurrentBalance = StoreTransactionHelper::getStoreCurrentBalance($storeCode);
            return sendSuccessResponse('Store Balance Found',$storeCurrentBalance);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }


    }




}
