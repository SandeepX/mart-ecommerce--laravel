<?php

namespace App\Modules\InvestmentPlan\Events;

use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateInvestmentPlanSubscriptionPaymentStatusEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
   public $onlinePaymentMasterData;
   public $validatedData;

    public function __construct($onlinePayment,$validatedData = [])
    {
        $this->onlinePaymentMasterData = $onlinePayment;
        $this->validatedData = $validatedData;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
