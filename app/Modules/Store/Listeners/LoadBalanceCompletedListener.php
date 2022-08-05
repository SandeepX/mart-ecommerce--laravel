<?php

namespace App\Modules\Store\Listeners;
;

use App\Modules\OfflinePayment\Services\OfflinePaymentService;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Events\LoadBalanceCompletedEvent;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Store\Services\Payment\StorePaymentService;
use App\Modules\Wallet\Services\WalletTransactionService;

class LoadBalanceCompletedListener
{

    public $storeBalance;
    private $storePaymentService;
    private $offlinePaymentService;
    private $walletTransactionService;
    private $storeRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct(
        StorePaymentService $storePaymentService,
        StoreBalance $storeBalance,
        WalletTransactionService $walletTransactionService,
        OfflinePaymentService $offlinePaymentService,
        StoreRepository $storeRepository
    ){
        $this->storePaymentService = $storePaymentService;
        $this->storeBalance = $storeBalance;
        $this->walletTransactionService = $walletTransactionService;
        $this->offlinePaymentService = $offlinePaymentService;
        $this->storeRepository = $storeRepository;
    }


    /**
     * Handle the event.
     *
     * @param LoadBalanceCompletedEvent $event
     * @return void
     */
    public function handle(LoadBalanceCompletedEvent $event)
    {

        $offlinePaymentData = $event->offlinePaymentData;
        $validatedData = $event->validatedData;
        $store = $this->storeRepository->findOrFailStoreByCode($offlinePaymentData->offline_payment_holder_code);

        $transactedAmount = roundPrice($offlinePaymentData['amount']);

        $this->offlinePaymentService->handleBrCodeAfterBalanceLoad($validatedData['balance_reconciliation_code'], $offlinePaymentData);
        $walletBalance = $this->storeBalance->getStoreWalletCurrentBalance($store);
        /*------- Recording loaded balance in wallet Transaction ---------------*/
        $walletTransactionDetails = $this->offlinePaymentService->prepareWalletTransactionForLoadBalance($store, $offlinePaymentData, $validatedData);
        /* ---------------------------- ends here ------------------------------*/

        $smsSendStatus = (isset($validatedData['sms']))  ? $validatedData['sms'] : false;
        //dd($smsSendStatus);

        $payingAmount = $walletBalance + $transactedAmount;
        $transactionPurposesDetails = $this->offlinePaymentService->storeStautusUpdateOnLoadBalance($store, $payingAmount,$smsSendStatus);
        $data['purpose'] = $walletTransactionDetails['wallet_transaction_purpose'];
        $data['purpose_code'] = $walletTransactionDetails->wallet_transaction_code;

        /* -------------------------------creating message for sms ----------------------------------*/
        $message = "You current account has been credited with Rs. {$walletTransactionDetails->amount} due to  Load Balance ";
        if (count($transactionPurposesDetails) > 0) {
            $message .= ", Note : Cleared Charges (";
            foreach ($transactionPurposesDetails as $key => $transactionPurpose) {
                $message .= " " . ucwords(str_replace("_", " ", $key)) . " Rs " . $transactionPurpose . " ,";
            }
            $message = rtrim($message, ',');
            $message .= ")";
        }
        $message .= " @ https://allpasal.com/ ";
        /* -------------------------------creating message for sms ends here ----------------------------------*/
        if (isset($validatedData['sms']) && $validatedData['sms']) {
            SendSmsJob::dispatch(
                $walletTransactionDetails['transaction_notification_details']['sms']['contact_no'],
                $message,
                $data
            );
        }

    }







}
