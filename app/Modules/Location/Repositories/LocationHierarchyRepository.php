<?php

namespace App\Modules\Location\Repositories;

use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Location\Resources\LocationHierarchy\LocationHierarchyResource;

use Exception;
class LocationHierarchyRepository
{

    public function getAllLocations()
    {
//        $categories = 'municipality';
//
//        // return CategoryMaster::where('upper_location_code', null)->with('childCategories.childCategories')->get();
//        $query = "WITH RECURSIVE location_path (id, location_code,upper_location_code, location_name, location_type , slug, path) AS
//      (
//        SELECT id, location_code, location_name, upper_location_code,location_type , slug,  CAST(location_name AS CHAR(255)) as path
//          FROM location_hierarchy
//          where location_type = '".$categories."'
//          and location_code = 'BN'
//
//
//        UNION ALL
//        SELECT l.id, l.location_code, l.location_name,l.upper_location_code, l.location_type,l.slug, CONCAT(lp.path, ' > ', l.location_name)
//          FROM location_path AS lp  JOIN location_hierarchy AS l
//            ON lp.location_code = l.upper_location_code
//            where l.location_type = 'ward'
//
//      )
//      SELECT * FROM location_path
////      ORDER BY path";
////
////        $categories = DB::select($query);
//       return array_shift($categories);
        return LocationHierarchy::orderById()->get();
    }

    public function getAllLocationsByType($locationHierarchyType)
    {
        return LocationHierarchy::where('location_type', $locationHierarchyType)->get();
    }

    public function getLocationByCode($locationHierarchyCode,$with=[])
    {
        return LocationHierarchy::with($with)->findOrFail($locationHierarchyCode);
    }

    public function getLowerLocations($locationHierarchyCode)
    {
        $locationHierarchy = $this->getLocationByCode($locationHierarchyCode);
        return $locationHierarchy->lowerLocations;
    }

    public function getUpperLocation($locationHierarchyCode)
    {
        $locationHierarchy = $this->getLocationByCode($locationHierarchyCode);
        return $locationHierarchy->upperLocation;
    }

    public function getUpperLocationFromTole($locationHierarchyCode)
    {
        $tole = $this->getLocationByCode($locationHierarchyCode);
        $ward = $tole->upperLocation;
        $municipality = $tole->upperLocation->getUpperLocation;
        dd($municipality);
    }

    public function getLocationById($locationHierarchyId)
    {
        return LocationHierarchy::where('id', $locationHierarchyId)->first();
    }

    public function getLocationBySlug($locationHierarchySlug)
    {
        return LocationHierarchy::where('slug', $locationHierarchySlug)->first();
    }

    public function getLocationPath(LocationHierarchy $locationHierarchy)
    {
        //$locationHierarchy = $this->getLocationByCode($locationHierarchyCode);
        $data = [
          //  'tole' => new LocationHierarchyResource($locationHierarchy),
            'ward' => new LocationHierarchyResource($locationHierarchy),
            'municipality' => new LocationHierarchyResource($locationHierarchy->municipality),
            'district' => new LocationHierarchyResource($locationHierarchy->municipality->district),
            'province' => new LocationHierarchyResource($locationHierarchy->municipality->district->province),
            'country' => new LocationHierarchyResource($locationHierarchy->municipality->district->province->country),
        ];

        // $data['ward'] = new LocationHierarchyResource($locationHierarchy->ward);
        // $data['municipality'] = new LocationHierarchyResource($locationHierarchy->ward->municipality);
        // $data['district'] = new LocationHierarchyResource($locationHierarchy->ward->municipality->district);
        // $data['province'] = new LocationHierarchyResource($locationHierarchy->ward->municipality->district->province);
        // $data['country'] = new LocationHierarchyResource($locationHierarchy->ward->municipality->district->province->country);
        return $data;
    }

    public function create($validated)
    {
        $locationHierarchy = new LocationHierarchy();

        $slugAndLocationCode = $locationHierarchy->generateSlugAndLocationCode($validated['location_name'],
            $validated['location_type']);

        $authUserCode = getAuthUserCode();
       // $validated['slug'] = makeSlugWithHash($validated['location_name']);
        $validated['slug'] =$slugAndLocationCode['slug'];
       //$validated['location_code'] = uniqueHash(10);
        $validated['location_code'] = $slugAndLocationCode['location_code'];
        //$validated['location_type'] = 'tole/street';
        $validated['created_by'] = $authUserCode;
        $validated['updated_by'] = $authUserCode;
        $locationHierarchy = LocationHierarchy::create($validated);
        return $locationHierarchy->fresh();
    }

    public function update($validated, $locationHierarchyCode)
    {
        $locationHierarchy = $this->getLocationByCode($locationHierarchyCode);
        $validated['updated_by'] = getAuthUserCode();
        $locationHierarchy->update($validated);
        $locationHierarchy->slug = make_slug($validated['location_name']).'-'.$locationHierarchy->id;
        $locationHierarchy->save();
        return $locationHierarchy->fresh();
    }

    public function delete($locationHierarchyCode)
    {
        $locationHierarchy = $this->getLocationByCode($locationHierarchyCode);
        $locationHierarchy->delete();
        $locationHierarchy->deleted_by = getAuthUserCode();
        $locationHierarchy->save();
        return $locationHierarchy;
    }

    public function getAllProvince()
    {
        try{
            $allProvince = LocationHierarchy::where('location_type','province')->get();
            return $allProvince;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getAllDistrict($provinceCode)
    {
        try{
            $allDistricts = LocationHierarchy::where('upper_location_code',$provinceCode)
                ->where('location_type','district')
                ->get();
            return $allDistricts;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getAllMunicipality($districtCode)
    {
        try{
            $allMuniscipality = LocationHierarchy::where('upper_location_code',$districtCode)
                ->where('location_type','municipality')
                ->get();
            return $allMuniscipality;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getAllWard($municipalityCode)
    {
        try{
            $allWard = LocationHierarchy::where('upper_location_code',$municipalityCode)
                ->where('location_type','ward')
                ->get();
            return $allWard;
        }catch(Exception $exception){
            throw $exception;
        }
    }


}
