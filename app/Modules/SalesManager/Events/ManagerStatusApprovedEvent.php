<?php

namespace App\Modules\SalesManager\Events;

use App\Modules\SalesManager\Models\Manager;
use App\Modules\User\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManagerStatusApprovedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $manager;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

}
