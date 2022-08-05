<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseStoreGroup;


use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Requests\WarehouseStoreGroup\WarehouseStoreGroupCreateRequest;

use App\Modules\AlpasalWarehouse\Requests\WarehouseStoreGroup\WarehouseStoreGroupUpdateRequest;
use App\Modules\AlpasalWarehouse\Services\WarehouseStoreGroup\WarehouseStoreGroupService;
use Exception;
class WarehouseStoreGroupController extends Controller
{
    private $warehouseStoreGroupService;
    public function __construct(WarehouseStoreGroupService $warehouseStoreGroupService)
    {
        $this->warehouseStoreGroupService = $warehouseStoreGroupService;
    }

    public function saveWarehouseStoreGroup(WarehouseStoreGroupCreateRequest $request){
        try{
            $validatedData = $request->validated();
            //dd($validatedData);
            $this->warehouseStoreGroupService->saveWarehouseStoreGroup($validatedData);
            return sendSuccessResponse('New stores group created successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function showWarehouseStoreGroup(WarehouseStoreGroupUpdateRequest $request,$warehouseStoreGroupCode){
        try{
            $validatedData = $request->validated();
            $this->warehouseStoreGroupService->updateWarehouseStoreGroup($validatedData,$warehouseStoreGroupCode);
            return sendSuccessResponse('Stores group updated successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updateWarehouseStoreGroup(WarehouseStoreGroupUpdateRequest $request,$warehouseStoreGroupCode){
        try{
            $validatedData = $request->validated();
            $this->warehouseStoreGroupService->updateWarehouseStoreGroup($validatedData,$warehouseStoreGroupCode);
            return sendSuccessResponse('Stores group updated successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteWarehouseStoreGroup($warehouseStoreGroupCode){
        try{
            $this->warehouseStoreGroupService->deleteWarehouseStoreGroup($warehouseStoreGroupCode);
            return sendSuccessResponse('Stores group deleted successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
    public function toggleWarehouseStoreGroupStatus($warehouseStoreGroupCode){
        try{
            $this->warehouseStoreGroupService->toggleWarehouseStoreGroupStatus($warehouseStoreGroupCode);
            return sendSuccessResponse('Stores group status updated successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
