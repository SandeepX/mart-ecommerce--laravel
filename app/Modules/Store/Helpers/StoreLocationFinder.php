<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/22/2020
 * Time: 4:14 PM
 */

namespace App\Modules\Store\Helpers;


use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Store\Models\Store;

class StoreLocationFinder
{
    public static function findStoreLocation($filterParameters,$paginateBy,$with=[])
    {
        $stores=Store::with($with)
            ->when($filterParameters['province'], function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality.district.province', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['province']);
                });
            })->when($filterParameters['district'], function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality.district', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['district']);
                });
            })->when($filterParameters['municipality'], function ($query) use ($filterParameters) {

                $query->whereHas('location.municipality', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['municipality']);
                });
            })->when($filterParameters['ward'], function ($query) use ($filterParameters) {

                $query->whereHas('location', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['ward']);
                });
            });
        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;
        $stores = $stores->latest()->get();
        return $stores;
    }
    //    usage StoreFinderApiController
    public static function getLocationPathInStoreFinder($filterParameters)
    {
        $separator='-';
        $finalLocationPath='';
        $path=[];
        if($filterParameters['province'])
        {
            $locationName =LocationHierarchy::where('location_type','province')->where('location_code',$filterParameters['province'])->first();
            if(!$locationName){
            throw new \Exception('No Such '.ucwords('province').' Found !',404);
            }

            array_push($path,$locationName->location_name);
        }
        if($filterParameters['district'])
        {
            $locationName =LocationHierarchy::where('location_type','district')->where('location_code',$filterParameters['district'])->first();
            if(!$locationName){
            throw new \Exception('No Such '.ucwords('district').' Found !',404);
            }

            array_push($path,$locationName->location_name);
        }
        if($filterParameters['municipality'])
        {
            $locationName =LocationHierarchy::where('location_type','municipality')->where('location_code',$filterParameters['municipality'])->first();
            if(!$locationName){
            throw new \Exception('No Such '.ucwords('municipality').' Found !',404);
            }

            array_push($path,$locationName->location_name);
        }
        if($filterParameters['ward'])
        {
            $locationName =LocationHierarchy::where('location_type','ward')->where('location_code',$filterParameters['ward'])->first();
            if(!$locationName){
            throw new \Exception('No Such '.ucwords('ward').' Found !',404);
            }

            array_push($path,$locationName->location_name);
        }
        $totalLocations=count($path);
        foreach($path as $i=>$locationName)
        {
            $finalLocationPath=$finalLocationPath.$locationName;
            if($totalLocations-1>$i)
            {
                $finalLocationPath=$finalLocationPath.$separator;
            }
        }
        return $finalLocationPath;
    }
    public static function storesNotInWards($stores)
    {
        $storeLocationCodeOfFoundStores=[];
        $storeLocationWardsOfFoundStores=[];
        foreach($stores as $i=>$store)
        {
            $storeWard=LocationHierarchy::where('location_code',$store->store_location_code)->first();
            array_push($storeLocationWardsOfFoundStores,$storeWard->location_name);
            array_push($storeLocationCodeOfFoundStores,$store->store_location_code);
        }
        $storesInWard=LocationHierarchy::where('location_code',$storeLocationCodeOfFoundStores['0'])->first();
        $municipality=$storesInWard->municipality;
        $wardsInMunicipality=$municipality->lowerLocations;
        $wardsInMunicipality=$wardsInMunicipality->pluck('location_name')->toArray();
        $storeNotInWards=array_diff($wardsInMunicipality,$storeLocationWardsOfFoundStores);
        $storeNotInWards="Store not found in following wards ".implode(',',$storeNotInWards);
        return $storeNotInWards;
    }

    public static function getAllStoreLocations(){
        return Store::whereNotNull('latitude')->whereNotNull('longitude')->select('store_name','latitude','longitude')->get();
    }
}
