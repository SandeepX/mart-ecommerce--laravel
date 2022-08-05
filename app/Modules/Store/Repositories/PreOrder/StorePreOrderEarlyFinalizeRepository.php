<?php

namespace App\Modules\Store\Repositories\PreOrder;

use App\Modules\Store\Models\PreOrder\StorePreOrderEarlyFinalization;

class StorePreOrderEarlyFinalizeRepository
{

    public function saveEarlyFinalize($validatedData){
        return StorePreOrderEarlyFinalization::create($validatedData);
    }

}
