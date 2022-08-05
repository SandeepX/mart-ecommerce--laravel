<?php


namespace App\Modules\Store\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoreWalletTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $storeWalletTransactionDetails;


    public function __construct(array $storeWalletTransactionDetails){
        $this->storeWalletTransactionDetails = $storeWalletTransactionDetails;
    }


}
