<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseDispatchRoute;


use App\Http\Controllers\Controller;

use App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute\WhDispatchRouteMarkerCreateRequest;
use App\Modules\AlpasalWarehouse\Services\WarehouseDispatchRoute\WarehouseDispatchRouteMarkerService;
use Exception;
class WarehouseDispatchRouteMarkerController extends Controller
{
    private $dispatchRouteMarkerService;
    public function __construct(WarehouseDispatchRouteMarkerService $dispatchRouteMarkerService)
    {
        $this->dispatchRouteMarkerService = $dispatchRouteMarkerService;
    }

    public function createDispatchRouteMarkers(WhDispatchRouteMarkerCreateRequest $request,$whDispatchRouteCode){
        try{
            $validatedData = $request->validated();
            $this->dispatchRouteMarkerService->saveManyWarehouseDispatchRouteMarkers($whDispatchRouteCode,$validatedData);
            return sendSuccessResponse('Markers added successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteMassRouteMarkers($whDispatchRouteCode){
        try{
            $this->dispatchRouteMarkerService->deleteMassRouteMarkers($whDispatchRouteCode);
            return sendSuccessResponse('Markers deleted successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
