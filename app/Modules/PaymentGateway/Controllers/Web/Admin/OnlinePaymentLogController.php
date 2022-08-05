<?php

namespace App\Modules\PaymentGateway\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\PaymentGateway\Helpers\OnlinePaymentHelper;
use App\Modules\PaymentGateway\Services\ConnectIpsService;
use Exception;
use Illuminate\Http\Request;

class OnlinePaymentLogController extends BaseController
{
    public $title = 'Online Payment Log';
    public $base_route = 'admin.connect-ips';
    public $sub_icon = 'file';
    public $module = 'PaymentGateway::';
    public $view = 'admin.online-payments.';

    private $connectIpsService;


    public function __construct(
        ConnectIpsService  $connectIpsService
    )
    {
        $this->middleware('permission:View Online Payment Lists', ['only' => ['paymentLists']]);
        $this->middleware('permission:Verify Online Payment', ['only' => ['reValidateIpsPayment']]);

        $this->connectIpsService = $connectIpsService;
    }

    public function paymentLists(Request $request){
        // dd('done');
        try{
            $storeName = $request->get('store_name');
            $transactionId = $request->get('transaction_id');
            $status = $request->get('status');

            $filterParameters = [
                'store_name' => $storeName,
                'transaction_id' => $transactionId,
                'status' => $status,
            ];

            $paymentLists = OnlinePaymentHelper::filterPaginatedOnlinePayments($filterParameters,20);


            $paymentLists->getCollection()->transform(function ($payment,$key){
                $payment->holder_name = OnlinePaymentHelper::getOnlinePaymentHolderName($payment);
                return $payment;
            });

            $paymentLists->getCollection()->transform(function ($walletLink){
                $walletLink->link = OnlinePaymentHelper::getLinkToWalletByInitiator(
                    $walletLink->initiator_code
                 );
                return $walletLink;
            });

            return view(Parent::loadViewData($this->module.$this->view.'payment-lists'),compact('paymentLists','filterParameters'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function reValidateIpsPayment($storeCode,$transactionId){
        try{
            throw new Exception('This feature is unavailable for the moment');
            $onlinePaymentMaster = $this->connectIpsService->validateIpsPaymentWithNotification($transactionId);

            return redirect()->back()->with('success', 'Payment Re-verfied successfully Status:'.$onlinePaymentMaster->status.'');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getOnlinePaymentsLists(Request $request,$paymentHolderType,$paymentFor)
    {
        try {

            $storeName = $request->get('store_name');
            $transactionId = $request->get('transaction_id');
            $status = $request->get('status');

            $filterParameters = [
                'store_name' => $storeName,
                'transaction_id' => $transactionId,
                'status' => $status,
                'transaction_type' => $paymentFor
            ];

            if($paymentHolderType == 'store'){
                 $filterParameters =  array_merge($filterParameters,['payment_initiator'=>'App\Modules\Store\Models\Store']);
            }

            if($paymentFor != 'load_balance'){
              throw new Exception('only load balance is handled from here:(');
            }

            $paymentLists = OnlinePaymentHelper::filterPaginatedOnlinePayments($filterParameters,20);


            $paymentLists->getCollection()->transform(function ($payment,$key){
                $payment->holder_name = OnlinePaymentHelper::getOnlinePaymentHolderName($payment);
                return $payment;
            });

            $paymentLists->getCollection()->transform(function ($walletLink){
                $walletLink->link = OnlinePaymentHelper::getLinkToWalletByInitiator(
                    $walletLink->initiator_code
                );
                return $walletLink;
            });
            return view(Parent::loadViewData($this->module.$this->view.'payment-lists-details'),
                compact('paymentLists','filterParameters','paymentHolderType','paymentFor'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
