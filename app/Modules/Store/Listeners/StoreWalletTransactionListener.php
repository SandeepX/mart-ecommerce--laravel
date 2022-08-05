<?php


namespace App\Modules\Store\Listeners;


use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Events\StoreWalletTransactionEvent;
use App\Modules\Wallet\Services\WalletTransactionService;

class StoreWalletTransactionListener
{
    public $walletTransactionService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(WalletTransactionService $walletTransactionService)
    {
        $this->walletTransactionService = $walletTransactionService;
    }


    public function handle(StoreWalletTransactionEvent  $storeWalletTransactionEvent){
        $storeWalletTransactionDetails = $storeWalletTransactionEvent->storeWalletTransactionDetails;
        $walletTransaction = $this->walletTransactionService->createWalletTransaction($storeWalletTransactionDetails);
        $smsSendStatus = isset($storeWalletTransactionDetails['transaction_notification_details']['sms'])
            ? $storeWalletTransactionDetails['transaction_notification_details']['sms']['status']
            : false;

        $data['purpose'] = $storeWalletTransactionDetails['wallet_transaction_purpose'];
        $data['purpose_code'] = $walletTransaction->wallet_transaction_code;

        if($smsSendStatus){
            SendSmsJob::dispatch(
                $storeWalletTransactionDetails['transaction_notification_details']['sms']['contact_no'],
                $storeWalletTransactionDetails['transaction_notification_details']['sms']['message'],
                $data
            );
        }
    }

}
