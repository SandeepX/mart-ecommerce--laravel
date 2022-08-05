<?php


namespace App\Modules\SupportAdmin\Controllers\stores;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Services\StoreService;
use App\Modules\Wallet\Helpers\WalletHelper;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Services\StoreWalletTransactionControlService;
use App\Modules\Wallet\Services\WalletService;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use App\Modules\Wallet\Services\WalletTransactionService;
use Exception;
use Illuminate\Http\Request;

class StoreTransactionStatementForSupportAdminController extends BaseController
{
    public $title = 'Store Detail For Admin Support';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    private $view = 'stores.transaction-statement.';

    public $storeService;
    public $walletService;
    public $storeWalletTransactionControlService;
    public $storeBalance;
    public $walletTransactionPurposeService;
    public $walletTransactionService;

    public function __construct(StoreService $storeService,
                                WalletService $walletService,
                                StoreWalletTransactionControlService $storeWalletTransactionControlService,
                                StoreBalance $storeBalance,
                                WalletTransactionPurposeService $walletTransactionPurposeService,
                                WalletTransactionService $walletTransactionService
    )
    {
        $this->storeService = $storeService;
        $this->walletService = $walletService;
        $this->storeWalletTransactionControlService = $storeWalletTransactionControlService;
        $this->storeBalance = $storeBalance;
        $this->walletTransactionPurposeService = $walletTransactionPurposeService;
        $this->walletTransactionsService = $walletTransactionService;
    }

    public function getStoreTransactionStatements($storeCode,Request $request)
    {
        try {
            $store = $this->storeService->findStoreByCode($storeCode);
            $walletCode = $store->wallet->wallet_code;
            $filterParameters = [
                'transaction_type' => $request->get('transaction_type'),
                'transaction_date_from' => $request->get('transaction_date_from'),
                'transaction_date_to' => $request->get('transaction_date_to'),
                'wallet_transaction_code' => $request->get('wallet_transaction_code'),
                'records_per_page' => 20,
            ];

            $wallet = $this->walletService->findOrFailByWalletCode($walletCode);

            if($wallet->wallet_type != 'store'){
                throw new Exception('The detail you are searching is not of Wallet Type Store)');
            }
            $activeBalance = $this->storeBalance->getStoreActiveBalance($wallet->walletable);
            $frozenBalanceDetails = $this->storeBalance->getStoreFreezeBalanceDetails($wallet->walletable);
            $wallet->holder_name = WalletHelper::getWalletHolderName($wallet);
            $userTypeCode = $wallet->walletable->storeUserTypeCode();
            $transactionPurposes = $this->walletTransactionPurposeService->getWalletTransactionPurposeByUserTypeCode($userTypeCode);
            $allTransactionByWalletCode = WalletTransactionHelper::getWalletTransactionDetailsWithParameters($walletCode,$filterParameters);

            $response  = [];
            $response['html'] = view($this->module . $this->view . 'index',
                compact(
                    'allTransactionByWalletCode',
                    'wallet',
                    'transactionPurposes',
                    'filterParameters',
                    'activeBalance',
                    'frozenBalanceDetails',
                    'storeCode'
                    )
            )->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function viewRemark($transactionWalletCode)
    {
        try{
            $with = ['extraRemarks'];
            $walletTransaction = $this->walletTransactionsService->findOrfailByWalletTransactionCode($transactionWalletCode);
            $remarks = $walletTransaction->extraRemarks;

            $response  = [];
            $response['html'] = view($this->module . $this->view . 'extra-remark-modal',
                compact(
                    'remarks','walletTransaction'
                )
            )->render();
            return response()->json($response);

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

}
