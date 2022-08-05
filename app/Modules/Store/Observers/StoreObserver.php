<?php

namespace App\Modules\Store\Observers;

use App\Modules\Store\Models\Store;
use Illuminate\Support\Facades\Auth;

class StoreObserver
{
    public function creating(Store $store)
    {
        if(Auth::check()) {
            $authUserCode = getAuthUserCode();
            $store->created_by = $authUserCode;
            $store->updated_by = $authUserCode;
        }
        $store->store_code = $store->generateStoreCode();
    }

    public function updating(Store $store)
    {
        $store->updated_by = Auth::check()? getAuthUserCode():getSuperAdminUserCode();
    }

    public function deleting(Store $store){
        $store->deleted_by = getAuthUserCode();
        $store->save();
    }
}

