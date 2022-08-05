<?php

namespace App\Modules\Vendor\Listeners;

use App\Modules\Vendor\Events\VendorRegisteredEvent;
use App\Modules\Wallet\Services\WalletService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateVendorWalletListener
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
     * @param  VendorRegisteredEvent  $event
     * @return void
     */
    public function handle(VendorRegisteredEvent $event)
    {
            $data = [];
            $data['wallet_holder_type'] = get_class($event->vendor);
            $data['wallet_type'] = strtolower(class_basename($event->vendor));
            $data['wallet_holder_code'] = $event->vendor->vendor_code;
            $this->walletService->createWallet($data);
    }
}
