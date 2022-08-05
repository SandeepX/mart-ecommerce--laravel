<?php


namespace App\Modules\Location\Helpers;

use App\Modules\Location\Models\LocationBlacklisted;
use App\Modules\Location\Models\LocationHierarchy;


class BlacklistedLocationFilter
{
    public static function filter($filterParameters, $paginateBy, $with = [])
    {
        $blacklistedLocation = LocationBlacklisted::when(isset($filterParameters['status']),function ($query) use($filterParameters){
            $query->where('status',$filterParameters['status']);
            })

            ->when(isset($filterParameters['from_date']),function ($query) use($filterParameters){
                $query->whereDate('created_at','>=',date('y-m-d',strtotime($filterParameters['from_date'])));
            })

            ->when(isset($filterParameters['to_date']),function ($query) use($filterParameters){
                $query->whereDate('created_at','<=',date('y-m-d',strtotime($filterParameters['to_date'])));
            })

            ->when($filterParameters['location_name'], function ($query) use ($filterParameters) {
                $query->whereHas('location', function ($query) use ($filterParameters) {
                    $query->where('location_name', $filterParameters['location_name']);
                });
            });
        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $blacklistedLocation = $blacklistedLocation->latest()->paginate($paginateBy);
        return $blacklistedLocation;
    }


}
