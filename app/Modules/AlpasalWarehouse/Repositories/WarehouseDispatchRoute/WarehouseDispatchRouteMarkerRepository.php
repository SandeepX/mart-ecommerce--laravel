<?php


namespace App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute;


use App\Modules\AlpasalWarehouse\Models\WhStoreDispatch\WarehouseDispatchRouteMarker;
use Carbon\Carbon;
use Exception;

class WarehouseDispatchRouteMarkerRepository
{
    public function createMany($validatedArrayData){
        try {
            $routeMarker = new WarehouseDispatchRouteMarker();
            $latestPrimaryCode = $routeMarker->generatePrimaryCode();
            // dd($validatedArrayData);
            $validatedArrayData= array_map(function ($validatedData) use ($routeMarker,&$latestPrimaryCode,&$toBeReturnedData){
                $currentDateTime =Carbon::now();
                $validatedData['wh_dispatch_route_marker_code'] = $latestPrimaryCode;
                $validatedData['created_by'] = getAuthUserCode();
                $validatedData['created_at'] = $currentDateTime;
                $validatedData['updated_at'] = $currentDateTime;
                $latestPrimaryCode = $routeMarker->incrementPrimaryCodeWithOutZeroPadding(
                    $latestPrimaryCode,WarehouseDispatchRouteMarker::MODEL_PREFIX);

                //only fillables
                $routeMarkerArray = array_filter($validatedData, function ($k) use ($routeMarker) {
                    return in_array($k, $routeMarker->getFillables());
                }, ARRAY_FILTER_USE_KEY); //gettin only fillables array

                return $routeMarkerArray;

            },$validatedArrayData);
            //dd($validatedArrayData);
            WarehouseDispatchRouteMarker::insert($validatedArrayData);
            return $validatedArrayData;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function deleteByDispatchRouteCode($dispatchRouteCode){
        WarehouseDispatchRouteMarker::where('wh_dispatch_route_code',$dispatchRouteCode)->delete();
    }
}
