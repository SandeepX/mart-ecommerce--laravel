<?php


namespace App\Modules\Wallet\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Services\StoreService;
use App\Modules\Wallet\Helpers\WalletHelper;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use App\Modules\Wallet\Services\WalletTransactionService;
use Illuminate\Http\Request;

class DaybookController extends BaseController
{
    public $title = 'Daybook';
    public $base_route = 'admin.daybook';
    public $sub_icon = 'file';
    public $module = 'Wallet::';
    private $view = 'daybook.';

    public $walletTransactionPurposeService;
    public $walletTransactionService;
    public $storeService;

    public function __construct(WalletTransactionPurposeService $walletTransactionPurposeService,
                                StoreService $storeService,
                                WalletTransactionService $walletTransactionService
    )
    {
        $this->walletTransactionPurposeService = $walletTransactionPurposeService;
        $this->storeService = $storeService;
        $this->walletTransactionService = $walletTransactionService;
    }

    public function index(Request $request)
    {
       try{
           $filterParameters = [
               'transaction_type' => $request->get('transaction_type'),
               'transaction_flow' => $request->get('transaction_flow'),
               'transaction_date_from' => $request->get('transaction_date_from'),
               'transaction_date_to' => $request->get('transaction_date_to'),
               'store_code' => $request->get('store_code'),
               'records_per_page' => 20,
               'include_exclude' => $request->get('include_exclude') ?? 'include'
           ];

           $select = ['slug','purpose'];

           $stores = $this->storeService->getAllActiveStores()->pluck('store_name','store_code');

           $transactionPurposes = $this->walletTransactionPurposeService->getAllWalletTransactionPurpose($select);

           if($request->ajax()){

               $allWalletTransactionsForDaybook = WalletTransactionHelper::getAllWalletTransactionsForDaybookWithParameters($filterParameters);

               $allWalletTransactionsForDaybook->getCollection()->transform(function ($walletTransactionDaybook){
                   $walletTransactionDaybook->link = WalletTransactionHelper::generateTransactionReferenceLink(
                       $walletTransactionDaybook->walletTransactionPurpose->slug,
                       $walletTransactionDaybook->wallet->wallet_type,
                       ['transactionReferenceCode' => $walletTransactionDaybook->transaction_purpose_reference_code]
                   );
                   return $walletTransactionDaybook;
               });

               $allWalletTransactionsForDaybook->getCollection()->transform(function ($walletDaybookUserName){
                   $walletDaybookUserName->holder_name = WalletHelper::getWalletHolderName($walletDaybookUserName->wallet);
                   return $walletDaybookUserName;
               });

               $response  = [];
               $response['html'] = view($this->module . $this->view . 'daybook-table',
                   compact('allWalletTransactionsForDaybook')
               )->render();

               return response()->json($response);
           }
           return view(Parent::loadViewData($this->module.$this->view.'index'),compact(
               'filterParameters',
                          'transactionPurposes',
                            'stores'

           ));
       }catch(\Exception $exception){
           if($request->ajax()){
               return sendErrorResponse($exception->getMessage(),$exception->getCode());
           }
           return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
       }
    }

    public function getTransactionPurposeByFlow(Request $request)
    {
        try{
            $select = ['slug','purpose'];
            $filterParameter = [
                'transaction_type' =>  $request->get('transaction_type'),
                'transaction_flow' =>  $request->get('transaction_flow')
                ];

            if($filterParameter['transaction_flow'] != ''){
                $filterParameter['transaction_flow_name'] = $filterParameter['transaction_flow'] == 'increment' ? 'decrement':'increment';
                $transactionPurposes = $this->walletTransactionPurposeService
                    ->getAllWalletTransactionPurposeByFlow($filterParameter['transaction_flow'],$select);
            }else{
                $filterParameter['transaction_flow_name'] = 'All';
                $transactionPurposes = $this->walletTransactionPurposeService
                    ->getAllWalletTransactionPurpose($select);
            }

            $data = $this->getTransactionTypeOption($transactionPurposes,$filterParameter);

           return sendSuccessResponse('Transaction Purpose',$data);
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getTransactionTypeOption($transactionPurposes,$filterParameter)
    {
        $html ='';
        $html.='<option value="">--Select '.$filterParameter['transaction_flow_name'].' Type--</option>';
        foreach ($transactionPurposes as $key => $datum) {
            $html .= '<option value = " '.$datum->slug.'" ';

            $html .=  (isset($filterParameter['transaction_type']) && in_array($datum,$filterParameter['transaction_type'])) ? 'selected' : '';

            $html .=  ">$datum->purpose</option>";
        }
        return $html;

    }

    public function viewRemarks($walletTransactionCode){
        try{
            $with = ['extraRemarks'];
            $walletTransaction = $this->walletTransactionService->findOrfailByWalletTransactionCode($walletTransactionCode);
            $remarks = $walletTransaction->extraRemarks;
            $response =[];
            $response['html'] = view($this->module . 'wallets.transactions.extra-remarks.view',
                compact('walletTransaction','remarks')
            )->render();
            return response()->json($response);
        }catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function createRemarks($walletTransactionCode){
        try{
            $walletTransaction = $this->walletTransactionService
                ->findOrfailByWalletTransactionCode($walletTransactionCode);
            $response =[];
            $response['html'] = view($this->module . 'wallets.transactions.extra-remarks.create',
                compact('walletTransaction')
            )->render();
            return response()->json($response);
        }catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

}
