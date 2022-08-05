<?php

namespace App\Modules\Store\Events;

use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\Store\Models\Store;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoadBalanceCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $offlinePaymentData;
    public $validatedData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(OfflinePaymentMaster $offlinePaymentData,$validatedData)
    {
        $this->offlinePaymentData = $offlinePaymentData;
        $this->validatedData = $validatedData;
    }

}
