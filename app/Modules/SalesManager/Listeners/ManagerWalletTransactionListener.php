<?php


namespace App\Modules\SalesManager\Listeners;


use App\Modules\SalesManager\Events\ManagerWalletTransactionEvent;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Wallet\Services\WalletTransactionService;

class ManagerWalletTransactionListener
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


    public function handle(ManagerWalletTransactionEvent  $managerWalletTransactionEvent){


        $managerWalletTransactionDetails = $managerWalletTransactionEvent->managerWalletTransactionDetails;

        $walletTransaction = $this->walletTransactionService->createWalletTransaction($managerWalletTransactionDetails);

        $smsSendStatus = isset($managerWalletTransactionDetails['transaction_notification_details']['sms'])
            ? $managerWalletTransactionDetails['transaction_notification_details']['sms']['status']
            : false;

        $data['purpose'] = $managerWalletTransactionDetails['wallet_transaction_purpose'];
        $data['purpose_code'] = $walletTransaction->wallet_transaction_code;

        if($smsSendStatus){
            SendSmsJob::dispatch(
                $managerWalletTransactionDetails['transaction_notification_details']['sms']['contact_no'],
                $managerWalletTransactionDetails['transaction_notification_details']['sms']['message'],
                $data
            );
        }
    }

}
