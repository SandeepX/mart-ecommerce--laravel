<?php

namespace App\Modules\Store\Services;

use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;
use App\Modules\Store\Repositories\StoreOrderNotificationRepository;
use App\Modules\User\Repositories\UserRepository;

class StoreOrderNotificationService
{
    private $storeOrderNotificationRepository;
    private $userRepository;
    private $warehouseRepository;

    public function __construct(
        StoreOrderNotificationRepository $storeOrderNotificationRepository,
        UserRepository $userRepository,
        WarehouseRepository $warehouseRepository
    )
    {
        $this->storeOrderNotificationRepository = $storeOrderNotificationRepository;
        $this->userRepository = $userRepository;
        $this->warehouseRepository = $warehouseRepository;
    }

    public function storeOrderPlacementNotification($storeOrder)
    {
        $admins = $this->userRepository->getAdminTypeUsers();
       // $warehouse = $this->warehouseRepository->findOrFailByCode($storeOrder->wh_code);
        $this->storeOrderNotificationRepository->storeOrderPlacementNotification($admins, $storeOrder);
       //$this->storeOrderNotificationRepository->storeOrderPlacementNotificationToWarehouse($warehouse, $storeOrder);
    }

    public function storeOrderStatusChangeNotification($storeOrder)
    {
        $this->storeOrderNotificationRepository->StoreOrderStatusChangeNotification($storeOrder);
    }
}
