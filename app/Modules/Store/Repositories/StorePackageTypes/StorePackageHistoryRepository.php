<?php


namespace App\Modules\Store\Repositories\StorePackageTypes;


use App\Modules\Store\Models\StorePackageTypes\StorePackageHistory;

class StorePackageHistoryRepository
{

    public function createStorePackageHistory($validatedData){
      return StorePackageHistory::create($validatedData);
    }

    public function getLatestHistoryByStoreCode($storeCode){
        return StorePackageHistory::where('store_code',$storeCode)
                                   ->latest()
                                   ->first();
    }

}
