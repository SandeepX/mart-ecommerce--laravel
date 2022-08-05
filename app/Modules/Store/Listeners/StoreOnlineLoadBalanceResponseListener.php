<?php


namespace App\Modules\Store\Listeners;

use App\Modules\PaymentGateway\Repositories\OnlinePaymentMasterRepository;
use App\Modules\PaymentGateway\Services\OnlinePaymentService;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Event\StoreOnlineLoadBalanceResponseEvent;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Store\Services\Payment\StorePaymentService;
use Exception;


class StoreOnlineLoadBalanceResponseListener
{
    public $storeRepo;
    public $storeLoadBalanceService;
    public $storeBalance;
    private $onlinePaymentMasterRepository;
    private $onlinePaymentService;

    public function __construct(
        StoreRepository $storeRepo,
        StorePaymentService $storeLoadBalanceService,
        StoreBalance $storeBalance,
        OnlinePaymentMasterRepository $onlinePaymentMasterRepository,
        OnlinePaymentService $onlinePaymentService
    )
    {
        $this->storeRepo = $storeRepo;
        $this->storeLoadBalanceService = $storeLoadBalanceService;
        $this->storeBalance = $storeBalance;
        $this->onlinePaymentMasterRepository = $onlinePaymentMasterRepository;
        $this->onlinePaymentService = $onlinePaymentService;
    }

    public function handle(StoreOnlineLoadBalanceResponseEvent $event)
    {
        try {
            $onlinePaymentData = $event->onlinePaymentData;
            $transactedAmount = convertPaisaToRs($onlinePaymentData->amount);
            $store = $this->storeRepo->findOrFailStoreByCode($onlinePaymentData->initiator_code);
            if ($onlinePaymentData['status'] == 'verified') {
                $paymentStatus = true;
            } else {
                $paymentStatus = false;
            }



            if ($paymentStatus) {
                $storeBalance = $this->storeBalance->getStoreWalletCurrentBalance($store);
                $payingAmount = $storeBalance + $transactedAmount;
                $walletTransactionDetails = $this->onlinePaymentService->prepareWalletTransactionForLoadBalance($store, $onlinePaymentData);

                /*----------updating store status : approval , enabling purchase power-------*/
                $storeRegChargeTransactions = $this->onlinePaymentService->storeStautusUpdateOnLoadBalance(
                    $store,
                    $payingAmount
                );
            }

            if ($onlinePaymentData->isVerified() && $paymentStatus) {
                $data['purpose'] = $walletTransactionDetails['wallet_transaction_purpose'];
                $data['purpose_code'] = $walletTransactionDetails->wallet_transaction_code;

                /* -------------------------------creating message for sms ----------------------------------*/
                $message = "You current account has been credited with Rs. {$transactedAmount} due to  Load Balance ";
                if (count($storeRegChargeTransactions) > 0) {
                    $message .= ", Note : Cleared Charges (";
                    foreach ($storeRegChargeTransactions as $key => $storeRegChargeTransactionCharge) {
                        $message .= " " . ucwords(str_replace("_", " ", $key)) . " Rs " . $storeRegChargeTransactionCharge . " ,";
                    }
                    $message = rtrim($message, ',');
                    $message .= ")";
                }
                $message .= " @ https://allpasal.com/ ";
                /* -------------------------------creating message for sms ends here ----------------------------------*/
                SendSmsJob::dispatch(
                    $store->store_contact_mobile,
                    $message,
                    $data
                );
            }
        } catch (Exception $exception) {
            throw $exception;
        }

    }
}

