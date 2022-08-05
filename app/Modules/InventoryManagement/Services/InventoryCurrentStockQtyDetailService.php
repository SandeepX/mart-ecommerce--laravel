<?php


namespace App\Modules\InventoryManagement\Services;


use App\Modules\InventoryManagement\Repositories\StoreInventoryItemRecievedQtyRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryCurrentStockQtyDetailService
{
    public $inventoryItemRecievedQtyRepo;

    public function __construct(StoreInventoryItemRecievedQtyRepository $inventoryItemRecievedQtyRepo)
    {
        $this->inventoryItemRecievedQtyRepo = $inventoryItemRecievedQtyRepo;
    }

    public function getItemReceivedQtyDetailBySiidCode($SIIDCode,$select=['*'])
    {
        return $this->inventoryItemRecievedQtyRepo
            ->select($select)
            ->getItemReceivedQtyDetailBySiidCode($SIIDCode);
    }

    public function findOrfailCurrentQtyStockDetailBySIIRQDCode($siirqd_code)
    {
        return $this->inventoryItemRecievedQtyRepo->findOrfailCurrentQtyStockDetailBySIIRQDCode($siirqd_code);
    }

    public function getCurrentStockQtyRecievedDetailBySIIDAndPPHCode($siid_code,$pph_code,$with=[],$select=['*'])
    {
        return $this->inventoryItemRecievedQtyRepo->select($select)
            ->with($with)
            ->getStockDetailOfStoreInStoreInventory($siid_code,$pph_code);
    }

    public function updateCurrentStockQtyDetail($currentStockQtyDetail,$validatedData)
    {
        try{
            DB::beginTransaction();
                $updateCurrentProductStockQtyDetail = $this->inventoryItemRecievedQtyRepo
                    ->update($currentStockQtyDetail,$validatedData);
            DB::commit();
            return $updateCurrentProductStockQtyDetail;
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


}
