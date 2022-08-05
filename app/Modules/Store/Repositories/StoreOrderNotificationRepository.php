<?php

namespace App\Modules\Store\Repositories;

use App\Modules\Store\Notifications\StoreOrderNotification;
use App\Modules\Store\Notifications\StoreOrderStatusNotification;
use Illuminate\Support\Facades\Auth;

class StoreOrderNotificationRepository
{
    public function storeOrderPlacementNotification($admins, $storeOrder)
    {
        foreach($admins as $admin){
            $admin->notify(new StoreOrderNotification($storeOrder));
        }
    }

    public function storeOrderPlacementNotificationToWarehouse($warehouse, $storeOrder)
    {
       $warehouse->notify(new StoreOrderNotification($storeOrder));
    }

    public function storeOrderStatusChangeNotification($storeOrder)
    {
        $user = $storeOrder->user;
        $user->notify(new StoreOrderStatusNotification($storeOrder));
    }
}
