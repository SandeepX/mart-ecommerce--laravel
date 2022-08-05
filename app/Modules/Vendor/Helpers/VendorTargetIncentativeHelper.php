<?php


namespace App\Modules\Vendor\Helpers;


use App\Modules\Vendor\Models\VendorTargetIncentive;

class VendorTargetIncentativeHelper
{
    public static function filterPaginatedVendorTargetIncentiveDetails($filterParameters, $paginateBy)
    {
        //dd($filterParameters['has_meet_target']);
        $vendorTargetIncentative = VendorTargetIncentive::when(isset($filterParameters['VTMCode']), function ($query) use ($filterParameters) {
                $query->where('vendor_target_master_code', $filterParameters['VTMCode']);
            })
            ->when(isset($filterParameters['productCode']), function ($query) use ($filterParameters) {
                $query->where('product_code', $filterParameters['productCode']);
            })
            ->when(isset($filterParameters['ProductVariantCode']), function ($query) use ($filterParameters) {
                $query->where('product_variant_code', $filterParameters['ProductVariantCode']);
            })
            ->when(isset($filterParameters['incentive_type']), function ($query) use ($filterParameters) {
                $query->where('incentive_type', $filterParameters['incentive_type']);
            })
            ->when(isset($filterParameters['incentive_value_from']), function ($query) use ($filterParameters) {
                $query->where('incentive_value','>=', $filterParameters['incentive_value_from']);
            })
            ->when(isset($filterParameters['incentive_value_to']), function ($query) use ($filterParameters) {
                $query->where('incentive_value','<=', $filterParameters['incentive_value_to']);
            })
            ->when(isset($filterParameters['has_meet_target']) && $filterParameters['has_meet_target']=='1', function ($query) use ($filterParameters) {
                $query->where('has_meet_target', $filterParameters['has_meet_target']);
            })
            ->when(isset($filterParameters['has_meet_target']) && $filterParameters['has_meet_target']=='0', function ($query) use ($filterParameters) {
                $query->where('has_meet_target', $filterParameters['has_meet_target']);
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $vendorTargetIncentative = $vendorTargetIncentative->latest()->paginate($paginateBy);
        return $vendorTargetIncentative;
    }
}



