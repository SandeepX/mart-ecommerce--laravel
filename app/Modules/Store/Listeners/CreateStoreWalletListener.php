<?php

namespace App\Modules\Store\Listeners;;

use App\Modules\Store\Events\StoreRegisteredEvent;
use App\Modules\Wallet\Services\WalletService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateStoreWalletListener
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
     * @param  StoreRegisteredEvent  $event
     * @return void
     */
    public function handle(StoreRegisteredEvent $event)
    {
        $data = [];
        $data['wallet_holder_type'] = get_class($event->store);
        $data['wallet_type'] = strtolower(class_basename($event->store));
        $data['wallet_holder_code'] = $event->store->store_code;
        $this->walletService->createWallet($data);
    }
}
