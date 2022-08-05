<?php


namespace App\Modules\Vendor\Helpers;

use App\Modules\Vendor\Models\VendorTargetMaster;


class VendorSetTargetHelper
{
    public static function filterPaginatedVendorTargetDetails($filterParameters,$paginateBy)
    {

        $vendorTarget = VendorTargetMaster::where('vendor_code',$filterParameters['vendor_code'])

            ->when(isset($filterParameters['vendor_name']), function ($query) use ($filterParameters) {
                $query->where('name', $filterParameters['vendor_name']);
            })

            ->when(isset($filterParameters['is_active']), function ($query) use ($filterParameters) {
                $query->where('is_active', $filterParameters['is_active']);
            })

            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            })

            ->when(isset($filterParameters['startDate_from']),function ($query) use($filterParameters){
                $query->whereDate('start_date','>=',date('y-m-d',strtotime($filterParameters['startDate_from'])));

            })

            ->when(isset($filterParameters['endDate_to']),function ($query) use($filterParameters) {
                $query->whereDate('end_date', '<=', date('y-m-d', strtotime($filterParameters['endDate_to'])));

            });


        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $vendorTarget = $vendorTarget->latest()->paginate($paginateBy);
        return $vendorTarget;
    }
}


