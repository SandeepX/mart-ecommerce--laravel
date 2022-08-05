<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseStoreGroup;


use App\Http\Controllers\Controller;

use App\Modules\AlpasalWarehouse\Requests\WarehouseStoreGroup\WhStoreGroupDetailMassCreateRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseStoreGroup\WhStoreGroupDetailMassDeleteRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseStoreGroup\WhStoreGroupDetailSortRequest;
use App\Modules\AlpasalWarehouse\Services\WarehouseStoreGroup\WarehouseStoreGroupDetailService;
use Exception;
class WarehouseStoreGroupDetailController extends Controller
{
    private $warehouseStoreGroupDetailService;
    public function __construct(WarehouseStoreGroupDetailService $warehouseStoreGroupDetailService)
    {
        $this->warehouseStoreGroupDetailService = $warehouseStoreGroupDetailService;
    }

    public function addStoresToGroup(WhStoreGroupDetailMassCreateRequest $request,$warehouseStoreGroupCode){
        try{
            $validatedData = $request->validated();
            $this->warehouseStoreGroupDetailService->massAddWarehouseStoreGroupDetail($warehouseStoreGroupCode,$validatedData);
            return sendSuccessResponse('Stores added to the group successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function sortStoresOfGroup(WhStoreGroupDetailSortRequest $request,$warehouseStoreGroupCode){
        try{
            $validatedData = $request->validated();
            $this->warehouseStoreGroupDetailService->sortWarehouseStoreGroupDetail($warehouseStoreGroupCode,$validatedData);
            return sendSuccessResponse('Stores sorted successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function massDeleteGroupDetail(WhStoreGroupDetailMassDeleteRequest $request,$warehouseStoreGroupCode){
        try{
            $validatedData = $request->validated();
            $this->warehouseStoreGroupDetailService->massDeleteStoreGroupDetail($warehouseStoreGroupCode,$validatedData);
            return sendSuccessResponse('Stores removed from group successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
