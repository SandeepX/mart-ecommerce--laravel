<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/30/2020
 * Time: 3:12 PM
 */

namespace App\Modules\Location\Helpers;


use App\Modules\Location\Models\LocationHierarchy;

use DB;
use Illuminate\Support\Arr;

class LocationHierarchyFilter
{

    public static function test($filterParameters,$paginateBy,$with=[]){

        $provinceFilterEnable = isset($filterParameters['province'])?true :false;
        if (isset($filterParameters['district']) || isset($filterParameters['municipality']) || isset($filterParameters['district'])){

            $provinceFilterEnable=false;
        }
        $locations = LocationHierarchy::with($with)
            ->when($filterParameters['location_name'], function ($query) use ($filterParameters) {
                $query->where('location_name', 'like', '%' . $filterParameters['location_name'] . '%');
            })-> when($filterParameters['location_type'], function ($query) use ($filterParameters) {
                $query->where('location_type', 'like', '%' . $filterParameters['location_type'] . '%');
            })->when($provinceFilterEnable, function ($query) use ($filterParameters) {
               //dd(1);
                $query->where('upper_location_code', $filterParameters['province']);
            })->when($filterParameters['district'], function ($query) use ($filterParameters) {
               //dd($filterParameters['district']);
               // $query->whereHas('nestedLowerLocations');
                $query->where('upper_location_code', $filterParameters['district']);
            })->when($filterParameters['municipality'], function ($query) use ($filterParameters) {
               // dd( $filterParameters['municipality']);
                $query->orWhere('upper_location_code', $filterParameters['municipality']);
            });

        //dd($locations->toSql());
        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $locations = $locations->latest()->paginate($paginateBy);
        return $locations;
    }

    public static function filterPaginatedLocations($filterParameters,$paginateBy,$with=[]){

       $locationCodeFilterEnable=false;
       $locationsCodes=[];
       $locationCode='';
        if (isset($filterParameters['province'])){
            $locationCodeFilterEnable=true;
            $locationCode=$filterParameters['province'];
        }
        if (isset($filterParameters['district'])){
            $locationCodeFilterEnable=true;
            $locationCode=$filterParameters['district'];
        }

        if (isset($filterParameters['municipality'])){
            $locationCodeFilterEnable=true;
            $locationCode=$filterParameters['municipality'];
        }


        if ($locationCodeFilterEnable){
           // dd($locationCode);
             $locations= LocationHierarchy::where('upper_location_code',$locationCode)->pluck('location_code')->toArray();

            // dd($locations);
            if ($locations){
                array_push($locationsCodes,$locations);
                a: $newLocations = LocationHierarchy::whereIn('upper_location_code',$locations)->pluck('location_code')->toArray();

                if ($newLocations){
                    array_push($locationsCodes,$newLocations);
                    $locations= $newLocations;
                    goto a;
                }
            }
            $locationsCodes=Arr::flatten($locationsCodes);

        }
        //dd($locationsCodes);
        $locations = LocationHierarchy::with($with)
            ->when($filterParameters['location_name'], function ($query) use ($filterParameters) {
                $query->where('location_name', 'like', '%' . $filterParameters['location_name'] . '%');
            })-> when($filterParameters['location_type'], function ($query) use ($filterParameters) {
                $query->where('location_type', 'like', '%' . $filterParameters['location_type'] . '%');
            })->when($locationCodeFilterEnable, function ($query) use ($locationsCodes) {
                //dd(1);
                $query->whereIn('location_code', $locationsCodes);
            });

        //dd($locations->toSql());
        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $locations = $locations->latest()->paginate($paginateBy);
        return $locations;
    }
}