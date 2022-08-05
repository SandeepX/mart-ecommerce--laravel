<?php


namespace App\Modules\Wallet\Controllers\Web\Admin\Store;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Wallet\Exports\StoreWalletDetailExcelExport;
use App\Modules\Wallet\Helpers\WalletHelper;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Models\WalletTransaction;
use App\Modules\Wallet\Requests\StoreWalletTransactionControlRequest;
use App\Modules\Wallet\Services\StoreWalletTransactionControlService;
use App\Modules\Wallet\Services\WalletService;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminWalletStoreTransactionControlController extends BaseController
{
    public $title = 'Transaction Control';
    public $base_route = 'admin.wallets';
    public $sub_icon = 'file';
    public $module = 'Wallet::';

    private $view = 'wallets.transactions-control.store';
    private $walletService;
    private $storeWalletTransactionControlService;
    private $storeBalance;
    private $walletTransactionPurposeService;


    public function __construct(
        WalletService $walletService,
        StoreWalletTransactionControlService $storeWalletTransactionControlService,
        StoreBalance $storeBalance,
        WalletTransactionPurposeService $walletTransactionPurposeService
    ){
        $this->middleware('permission:View Store Wallet Transaction Detail', ['only' => ['storeWalletTransactionDetail']]);
        $this->middleware('permission:Create Store Wallet Transaction', ['only' => ['create', 'store']]);

        $this->walletService = $walletService;
        $this->storeWalletTransactionControlService = $storeWalletTransactionControlService;
        $this->storeBalance = $storeBalance;
        $this->walletTransactionPurposeService = $walletTransactionPurposeService;
    }

    public function create($walletCode)
    {
        try{
            $wallet =  $this->walletService->findOrFailByWalletCode($walletCode);
            $wallet->holder_name = WalletHelper::getWalletHolderName($wallet);
            $wallet->holder_type = $wallet->getRelTypeAttribute($wallet->wallet_holder_type);
            $userTypeCode = $wallet->walletable->storeUserTypeCode();

//            $currentActiveBalance = $this->storeBalance->getStoreActiveBalance($wallet->walletable);
            return view(Parent::loadViewData($this->module.$this->view.'.create'),compact('wallet','userTypeCode'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function store(StoreWalletTransactionControlRequest $request,$walletCode){

        try{
            DB::beginTransaction();
            $validated = $request->validated();
            $validated['wallet_code'] = $walletCode;
            $this->storeWalletTransactionControlService->saveStoreWalletTransaction($validated);
            DB::commit();
            return $request->session()->flash('success','Balance Control added in Wallet Code: '.$walletCode.', Transaction Amount: '.$validated['transaction_amount'].' successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function storeWalletTransactionDetail(Request $request,$walletCode){

        try{
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


            $allTransactionByWalletCode->getCollection()->transform(function ($walletTransaction,$key){
                $walletTransaction->link = WalletTransactionHelper::generateTransactionReferenceLink(
                    $walletTransaction->walletTransactionPurpose->slug,
                  'store',
                     ['transactionReferenceCode' => $walletTransaction->transaction_purpose_reference_code]
                );
                return $walletTransaction;
            });



            return view(Parent::loadViewData($this->module.$this->view.'.detail-transactions'),compact('allTransactionByWalletCode','wallet',
                'transactionPurposes','filterParameters','activeBalance','frozenBalanceDetails'));

        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function storeWalletDispatchTransactionLists(Request $request,$walletCode){
        try{
            $wallet = $this->walletService->findOrFailByWalletCode($walletCode);
            $filterParameters   = [
                'wallet_transaction_code' =>$request->get('wallet_transaction_code'),
                'transaction_date_from' =>$request->get('transaction_date_from'),
                'transaction_date_to' =>$request->get('transaction_date_to'),
                'transaction_type' =>$request->get('transaction_type'),
                'store_code' => $wallet->wallet_holder_code,
                'perPage' => $request->get('per_page') ?? 10,
                'page' => $request->get('page') ?? 1
            ];
            //dd($filterParameters);


            if($wallet->wallet_type != 'store'){
                throw new Exception('The detail you are searching is not of Wallet Type Store)');
            }

            $activeBalance = $this->storeBalance->getStoreActiveBalance($wallet->walletable);
            $frozenBalanceDetails = $this->storeBalance->getStoreFreezeBalanceDetails($wallet->walletable);
            $wallet->holder_name = WalletHelper::getWalletHolderName($wallet);
            $userTypeCode = $wallet->walletable->storeUserTypeCode();
            $transactionPurposes = $this->walletTransactionPurposeService->getWalletTransactionPurposeByUserTypeCode($userTypeCode);

            $allTransactionWithDispatchByWalletCode = WalletTransactionHelper::getStoreWalletTransactionWithDispatchAmount($walletCode,$filterParameters);

            $allTransactionWithDispatchByWalletCode->getCollection()->transform(function ($walletTransaction,$key){
                $walletTransaction->link = WalletTransactionHelper::generateTransactionReferenceLink(
                    $walletTransaction->purpose,
                    'store',
                    ['transactionReferenceCode' => $walletTransaction->reference_code]
                );
                return $walletTransaction;
            });

            return view(Parent::loadViewData($this->module.$this->view.'.dispatch-txn-lists'),compact('allTransactionWithDispatchByWalletCode','wallet',
                'transactionPurposes','filterParameters','activeBalance','frozenBalanceDetails'));

        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function excelExport($walletCode)
    {
        try{
            $wallet = $this->walletService->findOrFailByWalletCode($walletCode);
            if($wallet->wallet_type != 'store'){
                throw new Exception('The detail you are searching is not of Wallet Type Store)');
            }
            $activeBalance = $this->storeBalance->getStoreActiveBalance($wallet->walletable);
            $frozenBalanceDetails = $this->storeBalance->getStoreFreezeBalanceDetails($wallet->walletable);
            $wallet->holder_name = WalletHelper::getWalletHolderName($wallet);
            $allTransactionByWalletCode = WalletTransactionHelper::getWalletTransactionDetailsForExcelExport($walletCode);
            return (new StoreWalletDetailExcelExport($allTransactionByWalletCode,$wallet,$activeBalance,$frozenBalanceDetails,$this->module, $this->view));

        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }




}
