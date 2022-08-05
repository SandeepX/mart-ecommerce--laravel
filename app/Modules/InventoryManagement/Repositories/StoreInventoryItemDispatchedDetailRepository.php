<?php


namespace App\Modules\InventoryManagement\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\InventoryManagement\Models\StoreInventoryItemDispatched;

class StoreInventoryItemDispatchedDetailRepository extends RepositoryAbstract
{
    public function getStoreStockSalesRecordDetailBySIIDCodeAndPPHCode($SIIDCode,$PPHCode)
    {
        return StoreInventoryItemDispatched::with($this->with)
            ->select($this->select)
            ->where('siid_code',$SIIDCode)
            ->where('pph_code',$PPHCode)
            ->get();
    }

    public function findOrFailInventoryStockSalesRecordBySIIDQDCode($siidqdCode)
    {
        return StoreInventoryItemDispatched::with($this->with)
            ->where('siidqd_code',$siidqdCode)
            ->first();
    }

    public function store($validatedData)
    {
        return StoreInventoryItemDispatched::create($validatedData)->fresh();
    }

    public function update($inventoryStockSalesRecordDetail,$validatedData)
    {
        return $inventoryStockSalesRecordDetail->update($validatedData);
    }

    public function delete($inventoryStockSalesRecordDetail)
    {
        return $inventoryStockSalesRecordDetail->delete();
    }

}
