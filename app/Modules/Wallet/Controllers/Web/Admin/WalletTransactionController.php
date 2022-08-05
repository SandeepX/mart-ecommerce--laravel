<?php

namespace App\Modules\Wallet\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Requests\StoreWalletTransactionRemarksRequest;
use App\Modules\Wallet\Services\WalletTransactionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletTransactionController extends BaseController
{
    public $title = 'Wallet Transaction';
    public $base_route = 'admin.wallets';
    public $sub_icon = 'file';
    public $module = 'Wallet::';

    private $view='wallets.transactions.';

    private $walletTransactionsService;

    public function __construct(
     WalletTransactionService $walletTransactionService
    )
    {
        $this->walletTransactionsService = $walletTransactionService;
    }

    public function viewRemarks($walletTransactionCode){
        try{
            $with = ['extraRemarks'];
            $walletTransaction = $this->walletTransactionsService->findOrfailByWalletTransactionCode($walletTransactionCode);
            $remarks = $walletTransaction->extraRemarks;
            return view(Parent::loadViewData($this->module.$this->view.'.extra-remarks.view'),compact('remarks','walletTransaction'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function createRemarks($walletTransactionCode){
        try{
            $walletTransaction = $this->walletTransactionsService->findOrfailByWalletTransactionCode($walletTransactionCode);
            return view(Parent::loadViewData($this->module.$this->view.'.extra-remarks.create'),compact('walletTransaction'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function saveRemarks(StoreWalletTransactionRemarksRequest  $request,$walletTransactionCode){
        try{
            DB::beginTransaction();
            $validatedData = $request->validated();
            $this->walletTransactionsService->saveExtraRemarks($validatedData,$walletTransactionCode);
            DB::commit();
            return $request->session()->flash('success','Remarks add for wallet transaction('.$walletTransactionCode.') successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }








}
