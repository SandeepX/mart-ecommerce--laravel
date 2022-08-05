<?php

namespace App\Modules\SalesManager\Listeners;

use App\Modules\SalesManager\Events\ManagerStatusApprovedEvent;
use App\Modules\Wallet\Services\WalletService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateManagerWalletListener
{

    public $walletService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Handle the event.
     *
     * @param  ManagerStatusApprovedEvent  $event
     * @return void
     */
    public function handle(ManagerStatusApprovedEvent $event)
    {
        $data = [];
        $data['wallet_holder_type'] = get_class($event->manager);
        $data['wallet_type'] = 'manager';
        $data['wallet_holder_code'] = $event->manager->manager_code;

        $this->walletService->createWallet($data);
    }
}
