<?php


namespace App\Modules\SalesManager\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManagerWalletTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $managerWalletTransactionDetails;


    public function __construct(array $managerWalletTransactionDetails){
        $this->managerWalletTransactionDetails = $managerWalletTransactionDetails;
    }

}
