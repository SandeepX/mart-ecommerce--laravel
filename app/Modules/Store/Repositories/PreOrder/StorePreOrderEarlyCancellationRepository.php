<?php


namespace App\Modules\Store\Repositories\PreOrder;

use App\Modules\Store\Models\PreOrder\StorePreorderEarlyCancellation;

class StorePreOrderEarlyCancellationRepository
{

    public function saveEarlyCancel($validatedData)
    {
        return StorePreorderEarlyCancellation::create($validatedData);
    }

}

