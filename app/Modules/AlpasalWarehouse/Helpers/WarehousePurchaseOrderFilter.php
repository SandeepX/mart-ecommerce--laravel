<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/10/2020
 * Time: 1:42 PM
 */

namespace App\Modules\AlpasalWarehouse\Helpers;


use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;

class WarehousePurchaseOrderFilter
{

    public static function filterPaginatedWarehousePurchaseOrders($filterParameters, $paginateBy, $with = [])
    {

        $status = isset($filterParameters['status']) && in_array($filterParameters['status'], WarehousePurchaseOrder::STATUSES) ? true : false;

        $purchaseOrders = WarehousePurchaseOrder::with($with)
            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->where('vendor_code', $filterParameters['vendor_code']);
            })->when(isset($filterParameters['warehouse_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_code', $filterParameters['warehouse_code']);
            })->when($status, function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            })->when(isset($filterParameters['order_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('order_date', '>=', date('y-m-d', strtotime($filterParameters['order_date_from'])));
            })->when(isset($filterParameters['order_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('order_date', '<=', date('y-m-d', strtotime($filterParameters['order_date_to'])));
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $purchaseOrders = $purchaseOrders->latest()->paginate($paginateBy)->withQueryString();
        return $purchaseOrders;
    }
}