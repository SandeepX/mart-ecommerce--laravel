<?php


namespace App\Modules\InventoryManagement\Controllers\Api\Front;

use App\Modules\InventoryManagement\Helpers\StoreCurrentStockRecordHelper;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use Illuminate\Http\Request;

class StoreInventoryCurrentStockRecord
{
    public function getStoreInventoryCurrentStock(Request $request)
    {
        try {
            $storeCode = getAuthStoreCode();
            $filterParameters = [
                'store_code' => $storeCode,
                'product_code' => $request->get('product_code'),
                'expiry_date_from' => $request->get('expiry_date_from'),
                'expiry_date_to' => $request->get('expiry_date_to'),
                'perPage' => $request->get('per_page') ? $request->get('per_page') : 25,
            ];
            $storeCurrentStockDetail = StoreCurrentStockRecordHelper::getStoreInventoryProductCurrentStockDetail($filterParameters);
            $storeCurrentStockDetail->getCollection()->transform(function ($storeCurrentStockPackageContains, $key) {
                $storeCurrentStockPackageContains->package_contains = implode(' ', ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($storeCurrentStockPackageContains->pph_code));
                return $storeCurrentStockPackageContains;
            });
            return sendSuccessResponse('Data Found', $storeCurrentStockDetail);
        } catch (\Exception $e) {
            return sendErrorResponse($e->getMessage(), 400);
        }
    }





}

