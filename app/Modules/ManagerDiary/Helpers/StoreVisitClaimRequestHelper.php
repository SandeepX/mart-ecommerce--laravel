<?php

namespace App\Modules\ManagerDiary\Helpers;

use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimRequestByManager;

class StoreVisitClaimRequestHelper
{
    public static function getMapsLocationOfVisitClaim(StoreVisitClaimRequestByManager $storeVisitClaim){

       $managerDiary = $storeVisitClaim->managerDiary ?? NULL;
       $mapsLocations = [];
        if($managerDiary->latitude && $managerDiary->longitude){
                 $mapsLocations[] = [
                     'lat' => $managerDiary->latitude,
                     'long' => $managerDiary->longitude,
                     'content' => 'Store Physical location in manager Diary'
                 ];
        }
        $referredStore =  $managerDiary->referredStore ?? NULL;

        if($referredStore->latitude && $referredStore->longitude){
                $mapsLocations[] = [
                    'lat' => $referredStore->latitude,
                    'long' => $referredStore->longitude,
                    'content' => 'Store Physical location'
                ];
        }

        if($storeVisitClaim->manager_latitude && $storeVisitClaim->manager_longitude){
                $mapsLocations[] = [
                    'lat' => $storeVisitClaim->manager_latitude,
                    'long' => $storeVisitClaim->manager_longitude,
                    'content' => 'Manager Qr generated Location'
                ];
        }

        if($storeVisitClaim->store_latitude && $storeVisitClaim->store_longitude){
            $mapsLocations[] = [
                'lat' => $storeVisitClaim->store_latitude,
                'long' => $storeVisitClaim->store_longitude,
                'content' => 'Store Qr scanned Location'
            ];
        }


        return $mapsLocations;

    }

}
