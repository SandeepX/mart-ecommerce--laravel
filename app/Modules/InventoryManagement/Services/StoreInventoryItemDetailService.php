<?php


namespace App\Modules\InventoryManagement\Services;


use App\Modules\InventoryManagement\Repositories\StoreInventoryItemRepository;

class StoreInventoryItemDetailService
{
    public $storeInventoryItemRepo;

    public function __construct(StoreInventoryItemRepository $storeInventoryItemRepo)
    {
        $this->storeInventoryItemRepo = $storeInventoryItemRepo;
    }

}
