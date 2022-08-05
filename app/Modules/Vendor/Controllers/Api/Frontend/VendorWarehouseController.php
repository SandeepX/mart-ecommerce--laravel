<?php

namespace App\Modules\Vendor\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\Vendor\Requests\VendorWarehouseRequest;
use App\Modules\Vendor\Resources\VendorWarehouse\MinimalVendorWarehouseResource;
use App\Modules\Vendor\Resources\VendorWarehouse\SingleVendorWarehouseResource;
use App\Modules\Vendor\Resources\VendorWarehouse\VendorWarehouseListResource;
use App\Modules\Vendor\Services\VendorService;
use App\Modules\Vendor\Services\VendorWarehouseService;
use Carbon\Carbon;
use Exception;

class VendorWarehouseController extends Controller
{

    private $vendorWarehouseService;
    public function __construct(VendorWarehouseService $vendorWarehouseService)
    {
        $this->vendorWarehouseService = $vendorWarehouseService;
    }

    public function index($vendorCode){
        try{

            $vendorWarehouses = $this->vendorWarehouseService->getAllVendorWarehouses($vendorCode);
            $vendorWarehouses = VendorWarehouseListResource::collection($vendorWarehouses);
            return sendSuccessResponse('Data Found!', $vendorWarehouses);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function store($vendorCode, VendorWarehouseRequest $vendorWarehouseRequest){
        $validatedVendorWarehouse = $vendorWarehouseRequest->validated();
        try{
            $vendorWarehouse = $this->vendorWarehouseService->storeVendorWarehouse($vendorCode, $validatedVendorWarehouse);
            $vendorWarehouse = new MinimalVendorWarehouseResource($vendorWarehouse);
            return sendSuccessResponse('Vendor Warehouse Created Successfully', $vendorWarehouse);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function show($vendorCode, $vendorWarehouseCode){
        try{
            $vendorWarehouse = $this->vendorWarehouseService->findVendorWarehouseByCode($vendorCode, $vendorWarehouseCode);
            $vendorWarehouse = new SingleVendorWarehouseResource($vendorWarehouse);
            return sendSuccessResponse('Data Found', $vendorWarehouse);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());

        }
    }

    public function update($vendorCode, VendorWarehouseRequest $vendorWarehouseRequest, $vendorWarehouseCode){
        $validatedVendorWarehouse = $vendorWarehouseRequest->validated();
        try{
            $vendorWarehouse = $this->vendorWarehouseService->updateVendorWarehouse($vendorCode, $validatedVendorWarehouse, $vendorWarehouseCode);
            $vendorWarehouse = new VendorWarehouseListResource($vendorWarehouse);
            return sendSuccessResponse('Vendor Warehouse Updated Successfully', $vendorWarehouse);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function destroy($vendorCode, $vendorWarehouseCode){
        try{
            $vendorWarehouse = $this->vendorWarehouseService->deleteVendorWarehouse($vendorCode, $vendorWarehouseCode);
            $vendorWarehouse = new MinimalVendorWarehouseResource($vendorWarehouse);
            return sendSuccessResponse('Vendor Warehouse Deleted Successfully', $vendorWarehouse);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
