<?php


namespace App\Modules\InventoryManagement\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\InventoryManagement\Models\StoreInventoryItem;
use Carbon\Carbon;

class StoreInventoryItemRepository  extends RepositoryAbstract
{


    public function findStoreInventoryItemDetailBySignature($signature)
    {
        return StoreInventoryItem::where('signature',$signature)->first();
    }

    public function store($validatedData)
    {
        return StoreInventoryItem::create($validatedData)->fresh();
    }

    public function update($storeInventoryItemDetail,$storeInventoryItemData)
    {
        return $storeInventoryItemDetail->update($storeInventoryItemData);
    }

    public function findOrFailStoreInventoryItemBatchDetailWhichMatches($matchThese)
    {
        return StoreInventoryItem::where($matchThese)
            ->first();
    }
}
