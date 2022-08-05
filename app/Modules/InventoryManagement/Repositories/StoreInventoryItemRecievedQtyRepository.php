<?php


namespace App\Modules\InventoryManagement\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\InventoryManagement\Models\StoreInventoryItemReceivingQty;

class StoreInventoryItemRecievedQtyRepository extends RepositoryAbstract
{

    public function store($validatedData)
    {
        return StoreInventoryItemReceivingQty::create($validatedData)->fresh();
    }

    public function findOrfailCurrentQtyStockDetailBySIIRQDCode($siirqd_code)
    {
        return StoreInventoryItemReceivingQty::where('siirqd_code',$siirqd_code)->firstOrfail();
    }

    public function getStockDetailOfStoreInStoreInventory($siid_code,$pph_code)
    {
        return StoreInventoryItemReceivingQty::with($this->with)
            ->select($this->select)
            ->where('siid_code',$siid_code)
            ->where('pph_code',$pph_code)
            ->get();
    }


    public function getItemReceivedQtyDetailBySiidCode($SIIDCode)
    {
        return StoreInventoryItemReceivingQty::select($this->select)
            ->where('siid_code',$SIIDCode)
            ->latest()
            ->get();
    }

    public function update($currentStockQtyDetail,$validatedData)
    {
        return $currentStockQtyDetail->update($validatedData);
    }

}
