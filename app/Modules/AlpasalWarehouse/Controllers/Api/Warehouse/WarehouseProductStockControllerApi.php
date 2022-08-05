<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse;


use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductStockService;
use Exception;
use Illuminate\Http\Request;

class WarehouseProductStockControllerApi extends Controller
{

    private $warehouseProductStockService;

    public function __construct(WarehouseProductStockService $warehouseProductStockService){
        $this->warehouseProductStockService = $warehouseProductStockService;
    }

    public function getWarehouseProductStockHistories(Request $request,$warehouseProductMasterCode){
        try{

            $warehouseCode = getAuthWarehouseCode();
            $warehouseProductStockDetail =$this->warehouseProductStockService->getWarehouseProductStockHistories($warehouseProductMasterCode,$warehouseCode);

            //return  $productPriceHistories;
            $warehouseProductStockHistories = $warehouseProductStockDetail['stock_histories'];
            $productDetail = $warehouseProductStockDetail['product_detail'];
            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-products.show-partials.stock-history-table',
                    compact('warehouseProductStockHistories','productDetail'))->render();
            }
            return $warehouseProductStockHistories;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
