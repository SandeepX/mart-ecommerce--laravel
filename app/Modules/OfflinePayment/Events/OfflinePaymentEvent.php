<?php


namespace App\Modules\OfflinePayment\Events;

use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\OfflinePayment\Models\OfflinePaymentMeta;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPaymentMeta;
use App\Modules\Store\Models\Store;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OfflinePaymentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $offlinePaymentData;
    public $subscriptionData;
    public $validatedData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(OfflinePaymentMaster $offlinePaymentData,InvestmentPlanSubscription $subscriptionData,$validatedData)
    {
        $this->offlinePaymentData = $offlinePaymentData;
        $this->subscriptionData = $subscriptionData;
        $this->validatedData = $validatedData;
    }

}

