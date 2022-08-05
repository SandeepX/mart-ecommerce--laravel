<?php

namespace App\Modules\Vendor\Events;;

use App\Modules\Vendor\Models\Vendor;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VendorRegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $vendor;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

}
