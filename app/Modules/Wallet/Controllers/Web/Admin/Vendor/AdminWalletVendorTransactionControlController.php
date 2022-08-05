<?php


namespace App\Modules\Wallet\Controllers\Web\Admin\Vendor;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Wallet\Helpers\WalletHelper;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Services\WalletService;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use Illuminate\Http\Request;
use Exception;

class AdminWalletVendorTransactionControlController extends BaseController
{

    public $title = 'Wallet Transaction';
    public $base_route = 'admin.wallets';
    public $sub_icon = 'file';
    public $module = 'Wallet::';

    private $view='wallets.transactions-control.vendor';

    private $walletService;
    private $walletTransactionPurposeService;

    public function __construct(
        WalletService $walletService,
        WalletTransactionPurposeService $walletTransactionPurposeService
    ){
        $this->middleware('permission:View Vendor Wallet Transaction Detail',
            ['only' => ['vendorWalletTransactionDetail']]);

        $this->walletService = $walletService;
        $this->walletTransactionPurposeService = $walletTransactionPurposeService;
    }

    public function vendorWalletTransactionDetail(Request $request,$walletCode){

        try{
            $filterParameters = [
                'transaction_type' => $request->get('transaction_type'),
                'wallet_transaction_code' => $request->get('wallet_transaction_code'),
                'transaction_date_from' => $request->get('transaction_date_from'),
                'transaction_date_to' => $request->get('transaction_date_to'),
                'records_per_page' => 20
            ];

            $wallet = $this->walletService->findOrFailByWalletCode($walletCode);

            if($wallet->wallet_type != 'vendor'){
                throw new Exception('The detail you are searching is not of Wallet Type Vendor');
            }

            $wallet->holder_name = WalletHelper::getWalletHolderName($wallet);

            $userTypeCode = $wallet->walletable->vendorUserTypeCode();
            $transactionPurposes = $this->walletTransactionPurposeService->getWalletTransactionPurposeByUserTypeCode($userTypeCode);


            $allTransactionByWalletCode = WalletTransactionHelper::getWalletTransactionDetailsWithParameters($walletCode,$filterParameters);

            return view(Parent::loadViewData($this->module.$this->view.'.detail-transactions'),compact('allTransactionByWalletCode','wallet',
                'transactionPurposes','filterParameters'));

        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}
