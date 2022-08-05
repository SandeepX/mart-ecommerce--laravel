<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/22/2020
 * Time: 11:50 AM
 */

namespace App\Modules\Location\Traits;


use App\Modules\Location\Models\LocationHierarchy;
use Illuminate\Support\Facades\DB;

trait LocationHelper
{

    public function getOldFullLocationPath(){

        $separator='-';
        if ($this->location){
            $ward= $this->location->location_name;

            $municipality= $this->location->municipality->location_name;
            $district= $this->location->municipality->district->location_name;
            $province= $this->location->municipality->district->province->location_name;

            return $province.$separator.$district.$separator.$municipality.$separator.$ward;
        }

        return 'No location';
        //$registeredLocation = ;
       // return $registeredLocation;
    }


    public function getFullLocationPath($locationHierarchyPoint =4){
        if($locationHierarchyPoint > 5){
            throw  new \Exception('Location Hierarchy Point Not Available
        : Cannot Get Location Above 5 (Country)');
        }
        $pointCount = 1;
        $separator='-';
        $finalLocationPath='';
        $locationNames=[];
        $currentLocation =$this->location;//ward model object
        a: if ($currentLocation){
            // $finalLocationPath = $finalLocationPath.$currentLocation->location_name.$separator;
            array_push($locationNames,$currentLocation->location_name);
            if ($currentLocation->upperLocation){
                $currentLocation= $currentLocation->upperLocation;// Dist model location object
                if($pointCount != $locationHierarchyPoint){
                    ++$pointCount;
                    goto a;
                }
                //dd(1);
            }
            //for removing country name
            if (count($locationNames) > $locationHierarchyPoint){
                array_pop($locationNames);
            }
            $locationNames=array_reverse($locationNames);
            $totalLocationNames= count($locationNames);
            foreach ($locationNames as $i=>$locationName){
                $finalLocationPath = $finalLocationPath.$locationName;
                if ($totalLocationNames-1 > $i){
                    $finalLocationPath= $finalLocationPath.$separator;
                }
            }
            return $finalLocationPath;
        }
        return 'No location';
        //$registeredLocation = ;
        // return $registeredLocation;
    }

    public function getFullLocationPathByLocation(LocationHierarchy $locationHierarchy,$locationHierarchyPoint =4){
        if($locationHierarchyPoint > 5){
            throw  new \Exception('Location Hierarchy Point Not Available
        : Cannot Get Location Above 5 (Country)');
        }
        $pointCount = 1;
        $separator='-';
        $finalLocationPath='';
        $locationNames=[];
        $currentLocation =$locationHierarchy;//ward model object
        a: if ($currentLocation){
            // $finalLocationPath = $finalLocationPath.$currentLocation->location_name.$separator;
            array_push($locationNames,$currentLocation->location_name);
            if ($currentLocation->upperLocation){
                $currentLocation= $currentLocation->upperLocation;// Dist model location object
                if($pointCount != $locationHierarchyPoint){
                    ++$pointCount;
                    goto a;
                }
                //dd(1);
            }
            //for removing country name
            if (count($locationNames) > $locationHierarchyPoint){
                array_pop($locationNames);
            }
            $locationNames=array_reverse($locationNames);
            $totalLocationNames= count($locationNames);
            foreach ($locationNames as $i=>$locationName){
                $finalLocationPath = $finalLocationPath.$locationName;
                if ($totalLocationNames-1 > $i){
                    $finalLocationPath= $finalLocationPath.$separator;
                }
            }
            return $finalLocationPath;
        }
        return 'No location';
        //$registeredLocation = ;
        // return $registeredLocation;
    }

    public function test(){

        $separator='-';
        $i=0;
        $currentLocation =$this->location;
        a: if ($currentLocation){
            $i++;
            if ($currentLocation->upperLocation){
                $currentLocation= $currentLocation->upperLocation;
                goto a;
                //dd(1);
            }
        }

        return $i;
        return 'No location';
        //$registeredLocation = ;
        // return $registeredLocation;
    }
}
