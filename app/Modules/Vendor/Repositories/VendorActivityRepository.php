<?php

namespace App\Modules\Vendor\Repositories;

use App\Modules\ActivityLog\Models\ActivityLog;
use Exception;

class VendorActivityRepository
{
public function getVendorActivity($userCode,$select,$limit,$startDate,$endDate){
//    dd($startDate);
    return ActivityLog::select($select)->where('user_code',$userCode)
//        ->whereBetween('created_at',[$startDate,$endDate])
            ->where('created_at', '>=',$endDate)
        ->latest()
        ->limit($limit)
        ->get();
}


}
