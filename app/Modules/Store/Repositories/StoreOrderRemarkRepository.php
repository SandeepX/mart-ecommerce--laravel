<?php

namespace App\Modules\Store\Repositories;

use App\Modules\Store\Models\StoreOrderRemark;

class StoreOrderRemarkRepository
{

    public function saveRemarks($validatedData){
        return StoreOrderRemark::create($validatedData);
    }

}
