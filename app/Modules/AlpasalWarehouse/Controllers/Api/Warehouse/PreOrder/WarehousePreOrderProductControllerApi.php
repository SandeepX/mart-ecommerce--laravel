<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse\PreOrder;


use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderProductFilter;
use Illuminate\Http\Request;
use Exception;

class WarehousePreOrderProductControllerApi extends Controller
{

    public function getWarehousePreOrderProducts(Request $request,$warehousePreOrderListingCode){
        try{

            $filterParameters = [
                'warehouse_code' =>getAuthWarehouseCode(),
                'warehouse_preorder_listing_code' => $warehousePreOrderListingCode,
            ];

            $with=['product','productVariant'];
            $warehousePreOrderProducts = WarehousePreOrderProductFilter::filterPaginatedWarehouseGroupedPreOrderProducts(
                $filterParameters,20,$with);


            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.pre-order-products-tbl',
                    compact('warehousePreOrderProducts','warehousePreOrderListingCode'))->render();
            }
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());

        }
    }
}
