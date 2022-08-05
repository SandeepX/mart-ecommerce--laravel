<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductHelper;
use App\Modules\AlpasalWarehouse\Resources\WarehouseWithConnectionStoreCollection;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Vendor\Resources\VendorCollection;
use Illuminate\Http\Request;
use Exception;

class WarehouseFilterControllerApi extends Controller
{
    private $warehouseService;
    public function __construct(WarehouseService $warehouseService)
    {
         $this->warehouseService = $warehouseService;
    }

    public function warhouseListsWithConnectedStores(){
        try{
            $with = ['stores:store_code,store_name'];
            $warehouses = WarehouseHelper::getAllWarehousesWithConnectedStores($with);
            return new WarehouseWithConnectionStoreCollection($warehouses);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getAllRelatedVendorsOfWarehouseByWarehouseCode(Request $request,$warehouseCode){
        try{
            $filterParameters = [
                'paginate_by' => $request->get('paginate_by') ?? 10,
                'vendor_name' => $request->get('vendor_name')
            ];

            $this->warehouseService->findOrFailWarehouseByCode($warehouseCode);
            $vendors =  WarehouseProductHelper::getWarehouseVendorListByWarehouseCode($warehouseCode,$filterParameters);
            return sendSuccessResponse('Vendors Found !!',$vendors);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function getAllRelatedProductOfWarehouseByWarehouseCode(Request $request,$warehouseCode){
        try{
            $filterParameters = [
                'vendor_code' => $request->get('vendor_code'),
                'paginate_by' => $request->get('paginate_by') ?? 10,
                'product_name'=> $request->get('product_name')
            ];

            $this->warehouseService->findOrFailWarehouseByCode($warehouseCode);
            $products = WarehouseProductHelper::getProductsofWarehouseWithVariantByWarehouseCode($warehouseCode,$filterParameters);
           return sendSuccessResponse('Products Found !!',$products);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }
}
