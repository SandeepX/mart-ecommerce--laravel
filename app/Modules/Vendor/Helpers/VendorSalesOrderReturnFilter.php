<?php


namespace App\Modules\Vendor\Helpers;


use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseReturn;
use Illuminate\Support\Facades\DB;

class VendorSalesOrderReturnFilter
{

    //usage:vendor api for sales-orders-returns listing
    public static function filterPaginatedGroupedVendorSalesOrderReturn($filterParameters, $paginateBy, $with = []){

        $salesReturns =WarehousePurchaseOrder::with($with)->join('warehouse_purchase_return', function ($join){
                    $join->on('warehouse_orders.warehouse_order_code', '=', 'warehouse_purchase_return.warehouse_order_code');
                })
            ->join('warehouses', function ($join){
                $join->on('warehouse_orders.warehouse_code', '=', 'warehouses.warehouse_code');
            })
           ->select('warehouse_purchase_return.*','warehouses.warehouse_name')
            ->addSelect(
                DB::raw('COUNT(warehouse_purchase_return.warehouse_purchase_return_code) as total_sales_return_items')
            )
            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_purchase_return.vendor_code', $filterParameters['vendor_code']);
            })->when(isset($filterParameters['warehouse_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_orders.warehouse_code', $filterParameters['warehouse_code']);
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $salesReturns = $salesReturns->groupBy('warehouse_purchase_return.warehouse_order_code')
            ->orderBy('warehouse_purchase_return.created_at', 'DESC')
            ->paginate($paginateBy);
        return $salesReturns;
    }

    //showing detail
    public static function filterPaginatedVendorSalesOrderReturn($filterParameters, $paginateBy, $with = []){

        $salesReturns = WarehousePurchaseReturn::with($with)
            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_purchase_return.vendor_code', $filterParameters['vendor_code']);
            })->when(isset($filterParameters['warehouse_order_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_purchase_return.warehouse_order_code', $filterParameters['warehouse_order_code']);
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $salesReturns = $salesReturns->orderBy('warehouse_purchase_return.created_at', 'DESC')
            ->paginate($paginateBy);
        return $salesReturns;
    }
}
