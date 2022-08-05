<?php
namespace App\Modules\Vendor\Helpers;

use App\Modules\ActivityLog\Models\ActivityLog;

class VendorActivityFilterHelper{

    public static function getVendorActivity($filterParameters){
        return ActivityLog::select($filterParameters['select'])
            ->where('user_code',$filterParameters['userCode'])
            ->where('created_at','<=',$filterParameters['endDate'])
            ->where('created_at','>=',$filterParameters['startDate'])
            ->latest()
            ->paginate($filterParameters['paginateBy']);

    }
}
