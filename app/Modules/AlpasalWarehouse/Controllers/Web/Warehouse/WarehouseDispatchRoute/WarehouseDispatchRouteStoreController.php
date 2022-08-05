<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseDispatchRoute;


use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute\WarehouseDispatchRouteStoreSortRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute\WhDispatchRouteStoreDeleteRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute\WhDispatchRouteStoreMassAddRequest;
use App\Modules\AlpasalWarehouse\Services\WarehouseDispatchRoute\WarehouseDispatchRouteStoreService;
use Exception;
class WarehouseDispatchRouteStoreController extends Controller
{
    private $warehouseDispatchRouteStoreService;
    public function __construct(
        WarehouseDispatchRouteStoreService $warehouseDispatchRouteStoreService)
    {
        $this->warehouseDispatchRouteStoreService = $warehouseDispatchRouteStoreService;
    }

    public function addStoresToDispatchRoute(WhDispatchRouteStoreMassAddRequest $request,$dispatchRouteCode){
        try{
            $validatedData = $request->validated();
            //dd($validatedData);
            $this->warehouseDispatchRouteStoreService->massAddStoreToDispatchRoute($dispatchRouteCode,$validatedData);
            $stores= $this->warehouseDispatchRouteStoreService->getDispatchRouteStores($dispatchRouteCode);
            $response=[
              'route_stores' => $stores
            ];
            return sendSuccessResponse('Store added to route successfully',$response);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function sortStoresOfDispatchRoute(
        WarehouseDispatchRouteStoreSortRequest $request,$dispatchRouteCode
    ){
        try{
            $validatedData = $request->validated();
            $this->warehouseDispatchRouteStoreService->sortDispatchRouteStores($dispatchRouteCode,$validatedData);
            return sendSuccessResponse('Stores sorted successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }


    public function deleteDispatchRouteStores(WhDispatchRouteStoreDeleteRequest $request,$whDispatchRouteCode){
        try{
            $validatedData = $request->validated();
            $this->warehouseDispatchRouteStoreService->deleteWarehouseDispatchRouteStores($whDispatchRouteCode,$validatedData);
            return sendSuccessResponse('Stores deleted successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteMassStoresByDispatchCode($whDispatchRouteCode){
        try{
            $this->warehouseDispatchRouteStoreService->deleteMassStoresByDispatchCode($whDispatchRouteCode);
            return sendSuccessResponse('Stores deleted successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
