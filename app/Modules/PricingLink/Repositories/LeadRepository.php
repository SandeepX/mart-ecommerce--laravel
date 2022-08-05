<?php


namespace App\Modules\PricingLink\Repositories;

use App\Modules\PricingLink\Models\UserPricingView;


class LeadRepository
{
    public function getAllPricingLinkLeads($filterParameters,$paginatedBy=10)
    {
        return UserPricingView::when(isset($filterParameters['is_verified']), function ($query) use ($filterParameters) {
            $query->where('is_verified', $filterParameters['is_verified']);
        })
            ->when(isset($filterParameters['joined_date_from']), function ($query) use ($filterParameters) {
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
            })
            ->orderBy('id','desc')->paginate($paginatedBy);
    }

}
