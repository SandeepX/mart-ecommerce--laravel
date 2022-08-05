<?php


namespace App\Modules\AlpasalWarehouse\Services\WarehouseDispatchRoute;

use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteMarkerRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseDispatchRoute\WarehouseDispatchRouteRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseDispatchRouteMarkerService
{
    private $warehouseDispatchRouteRepository,$warehouseDispatchRouteMarkerRepository;

    public function __construct(
        WarehouseDispatchRouteRepository $warehouseDispatchRouteRepository,
        WarehouseDispatchRouteMarkerRepository $warehouseDispatchRouteMarkerRepository
    )
    {
        $this->warehouseDispatchRouteRepository = $warehouseDispatchRouteRepository;
        $this->warehouseDispatchRouteMarkerRepository = $warehouseDispatchRouteMarkerRepository;
    }

    public function saveManyWarehouseDispatchRouteMarkers($dispatchRouteCode,$validatedData){
        try{
            //dd($validatedData);
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();

            $dispatchRoute = $this->warehouseDispatchRouteRepository->findByWarehouseCode($authWarehouseCode,$dispatchRouteCode);

            if (!$dispatchRoute){
                throw new Exception('Dispatch route not found');
            }
            if ($dispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }

            $this->warehouseDispatchRouteMarkerRepository->deleteByDispatchRouteCode($dispatchRouteCode);

            $validatedData['latitude'] = array_filter($validatedData['latitude']);
            $validatedData['longitude'] = array_filter($validatedData['longitude']);

            $toBeStoredMarkers =[];

            foreach ($validatedData['latitude'] as $key=>$inputLatitude){
                $inputLongitude = $validatedData['longitude'][$key];
                if (!$inputLongitude){
                    throw new Exception('Invalid data');
                }
                array_push($toBeStoredMarkers,[
                   'wh_dispatch_route_code' => $dispatchRouteCode,
                    'latitude'=> $inputLatitude,
                    'longitude'=> $inputLongitude,
                    'sort_order' =>$key+1,
                    'is_store' =>0
                ]);
            }

            $this->warehouseDispatchRouteMarkerRepository->createMany($toBeStoredMarkers);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteMassRouteMarkers($dispatchRouteCode){
        try{
            //dd($validatedData);
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();

            $dispatchRoute = $this->warehouseDispatchRouteRepository->findByWarehouseCode($authWarehouseCode,$dispatchRouteCode);

            if (!$dispatchRoute){
                throw new Exception('Dispatch route not found');
            }
            if ($dispatchRoute->isDispatched()) {
                throw new Exception('Route already dispatched');
            }

            $this->warehouseDispatchRouteMarkerRepository->deleteByDispatchRouteCode($dispatchRouteCode);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
