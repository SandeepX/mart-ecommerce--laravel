<?php


namespace App\Modules\InventoryManagement\Controllers\Api\Front;

use App\Modules\InventoryManagement\Requests\InventoryCurrentStock\InventoryCurrentStockQtyDetailUpdateRequest;
use App\Modules\InventoryManagement\Resources\InventoryCurrentStockQtyDetail\InventoryCurrentStockQtyDetailCollection;
use App\Modules\InventoryManagement\Services\InventoryCurrentStockQtyDetailService;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use Exception;


class InventoryCurrentStockQtyDetailController
{
    public $inventoryCurrentStockQtyDetailService;

    public function __construct(InventoryCurrentStockQtyDetailService $inventoryCurrentStockQtyDetailService )
    {
        $this->inventoryCurrentStockQtyDetailService = $inventoryCurrentStockQtyDetailService;
    }

    public function getCurrentStockQtyRecievedDetail($siid_code, $pph_code)
    {
        try {
            $currentProductStockQtyRecievedDetail = $this->inventoryCurrentStockQtyDetailService
                ->getCurrentStockQtyRecievedDetailBySIIDAndPPHCode($siid_code, $pph_code);
            $currentProductStockQtyRecievedDetail = new InventoryCurrentStockQtyDetailCollection($currentProductStockQtyRecievedDetail);
            return sendSuccessResponse('Data Found', $currentProductStockQtyRecievedDetail);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updateCurrentStockQtyDetail(InventoryCurrentStockQtyDetailUpdateRequest $request,$siirqd_code)
    {
        try {
            $validatedData = $request->validated();
            $currentQtyStockDetail = $this->inventoryCurrentStockQtyDetailService->findOrfailCurrentQtyStockDetailBySIIRQDCode($siirqd_code);
            $productPackagingDetail = ProductPackagingContainsHelper::findProductPackagingDetailByPPHCode($validatedData['pph_code']);
            $validatedData['micro_unit_quantity'] =  ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                            $validatedData['package_code'],$productPackagingDetail,$validatedData['quantity']);

            $currentStockQtyUpdate = $this->inventoryCurrentStockQtyDetailService->updateCurrentStockQtyDetail($currentQtyStockDetail,$validatedData);

            return sendSuccessResponse('Current Product Stock Quantity Updated Successfully');
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

}

