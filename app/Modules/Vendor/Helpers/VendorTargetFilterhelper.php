<?php


namespace App\Modules\Vendor\Helpers;

use App\Modules\Vendor\Models\VendorTargetMaster;


class VendorTargetFilterhelper
{
    public static function filterPaginatedVendorTarget($filterParameters,$paginateBy,$with=[])
    {
        $vendorTarget = VendorTargetMaster::with($with)
            ->when(isset($filterParameters['name']), function ($query) use ($filterParameters) {
                $query->where('name', 'like', '%' . $filterParameters['name'] . '%');
            })
            ->when(isset($filterParameters['vendor_name']), function ($query) use ($filterParameters) {
                $query->whereHas('vendor',function ($query) use ($filterParameters){
                    $query->where('vendor_name', 'like', '%' . $filterParameters['vendor_name'] . '%');
                });
            })

            ->when(isset($filterParameters['location_name']), function ($query) use ($filterParameters) {
                $query->whereHas('municipality',function ($query) use ($filterParameters){
                    $query->where('location_name', 'like', '%' . $filterParameters['location_name'] . '%');
                    })
                    ->orWhereHas('district', function ($query) use ($filterParameters) {
                        $query->where('location_name', 'like', '%' . $filterParameters['location_name'] . '%');
                    })
                    ->orWhereHas('province', function ($query) use ($filterParameters) {
                        $query->where('location_name', 'like', '%' . $filterParameters['location_name'] . '%');
                    });
                })


            ->when(isset($filterParameters['start_date']), function ($query) use ($filterParameters) {
                $query->whereDate('start_date','>=',date('y-m-d',strtotime($filterParameters['start_date'])));
            })

            ->when(isset($filterParameters['end_date']), function ($query) use ($filterParameters) {
                $query->whereDate('end_date','<=',date('y-m-d',strtotime($filterParameters['end_date'])));
            })
            ->when(isset($filterParameters['is_active']), function ($query) use ($filterParameters) {
                $query->where('is_active', $filterParameters['is_active']);
            })

            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $vendorTarget = $vendorTarget->latest()->paginate($paginateBy);
        return $vendorTarget;
    }
}
