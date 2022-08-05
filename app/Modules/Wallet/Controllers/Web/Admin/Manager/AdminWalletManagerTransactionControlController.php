<?php


namespace App\Modules\Wallet\Controllers\Web\Admin\Manager;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Wallet\Helpers\WalletHelper;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Services\WalletService;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use Illuminate\Http\Request;
use Exception;

class AdminWalletManagerTransactionControlController extends BaseController
{
    public $title = 'Wallet Transaction';
    public $base_route = 'admin.wallets';
    public $sub_icon = 'file';
    public $module = 'Wallet::';

    private $view='wallets.transactions-control.sales-manager';

    private $walletService;
    private $walletTransactionPurposeService;

    public function __construct(
        WalletService $walletService,
        WalletTransactionPurposeService $walletTransactionPurposeService
    ){
        $this->middleware('permission:View Manager Wallet Transaction Detail', ['only' => ['managerWalletTransactionDetail']]);

        $this->walletService = $walletService;
        $this->walletTransactionPurposeService = $walletTransactionPurposeService;
    }


    public function managerWalletTransactionDetail(Request $request,$walletCode){

        try{
            $filterParameters = [
                'transaction_type' => $request->get('transaction_type'),
                'transaction_date_from' => $request->get('transaction_date_from'),
                'transaction_date_to' => $request->get('transaction_date_to'),
                'wallet_transaction_code' => $request->get('wallet_transaction_code'),
                'records_per_page' => 20,
            ];

            $wallet = $this->walletService->findOrFailByWalletCode($walletCode);

            if($wallet->wallet_type != 'manager'){
                throw new Exception('The detail you are searching is not of Wallet Type Sales Manager');
            }
            $wallet->holder_name = WalletHelper::getWalletHolderName($wallet);
            $userTypeCode = $wallet->walletable->managerUserTypeCode();
            //dd($userTypeCode);
            $transactionPurposes = $this->walletTransactionPurposeService->getWalletTransactionPurposeByUserTypeCode($userTypeCode);

            $allTransactionByWalletCode = WalletTransactionHelper::getWalletTransactionDetailsWithParameters($walletCode,$filterParameters);

            $allTransactionByWalletCode->getCollection()->transform(function ($walletTransaction,$key){
                $walletTransaction->link = WalletTransactionHelper::generateTransactionReferenceLink(
                    $walletTransaction->walletTransactionPurpose->slug,
                    'sales-manager',
                    ['transactionReferenceCode' => $walletTransaction->transaction_purpose_reference_code]
                );
                return $walletTransaction;
            });

            return view(Parent::loadViewData($this->module.$this->view.'.detail-transactions'),compact('allTransactionByWalletCode','wallet',
                'transactionPurposes','filterParameters'));

        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}
