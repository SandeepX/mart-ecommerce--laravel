<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/23/2020
 * Time: 10:54 AM
 */

namespace App\Modules\Vendor\Helpers;


use App\Modules\Vendor\Models\Vendor;

class VendorFilter
{

    public static function filterPaginatedVendors($filterParameters,$paginateBy,$with=[])
    {

        $vendors = Vendor::with($with)
            ->when(isset($filterParameters['vendor_name']), function ($query) use ($filterParameters) {
                $query->where('vendor_name', 'like', '%' . $filterParameters['vendor_name'] . '%');
            })->when(isset($filterParameters['vendor_owner']), function ($query) use ($filterParameters) {
                $query->where('vendor_owner', 'like', '%' . $filterParameters['vendor_owner'] . '%');
            })->when(isset($filterParameters['company_type']), function ($query) use ($filterParameters) {
                $query->where('company_type_code', $filterParameters['company_type']);
            })->when(isset($filterParameters['joined_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['joined_date_from'])));
            })->when(isset($filterParameters['joined_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['joined_date_to'])));
            })->when(isset($filterParameters['province']), function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality.district.province', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['province']);
                });
            })->when(isset($filterParameters['district']), function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality.district', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['district']);
                });
            })->when(isset($filterParameters['municipality']), function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['municipality']);
                });
            })->when(isset($filterParameters['ward']), function ($query) use ($filterParameters) {

                $query->whereHas('location', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['ward']);
                });
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $vendors = $vendors->latest()->paginate($paginateBy);
        return $vendors;
    }
}