<?php


namespace App\Modules\Location\Repositories;


use App\Modules\Location\Models\LocationBlacklisted;

class LocationBlacklistedRepository
{
    public function getAllBlacklistedLocation()
    {
        return LocationBlacklisted::latest()->paginate(20);
    }

    public function getBlacklistedLocationByBLHCode($BLHCode)
    {
        return LocationBlacklisted::where('blacklisted_location_hierarchy_code',$BLHCode)->first();
    }

    public function getBlackListedLocationByLocationCode($locationCode)
    {
        return LocationBlacklisted::where('location_code',$locationCode)->first();
    }

    public function store($validatedData)
    {
        return LocationBlacklisted::create($validatedData)->fresh();
    }

    public function update($blackListedLocationdetail, $validateddata)
    {
        return $blackListedLocationdetail->update($validateddata);
    }

    public function toggleBlacklistedLocationStatus(LocationBlacklisted $blackListedLocation)
    {
        return $blackListedLocation->update([
            'status' => !$blackListedLocation['status']
        ]);
    }

    public function delete(LocationBlacklisted $blackListedLocation)
    {
        return $blackListedLocation->delete();
    }

}
