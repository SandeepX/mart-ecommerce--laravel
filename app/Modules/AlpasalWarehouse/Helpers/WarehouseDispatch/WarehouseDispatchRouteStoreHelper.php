<?php


namespace App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch;


use App\Modules\Store\Models\Store;
use Illuminate\Support\Facades\DB;

class WarehouseDispatchRouteStoreHelper
{
    //store that can be added to dispatch route
    public static function getDispatchableStores($warehouseCode, $dispatchRouteCode = null)
    {
        /*$alreadyAssignedStores = WarehouseDispatchRouteStore::join('warehouse_dispatch_routes', function ($join) use ($warehouseCode) {
            $join->on('warehouse_dispatch_routes.wh_dispatch_route_code', '=', 'wh_dispatch_route_stores.wh_dispatch_route_code')
                ->where('warehouse_dispatch_routes.status', 'pending')
                ->where('warehouse_dispatch_routes.warehouse_code', $warehouseCode);
        })->when($dispatchRouteCode,function ($q) use ($dispatchRouteCode){
            $q->where('warehouse_dispatch_routes.wh_dispatch_route_code','!=',$dispatchRouteCode);
        })->select(
            'wh_dispatch_route_stores.store_code'
        )->groupBy('wh_dispatch_route_stores.store_code')->get();
        dd($alreadyAssignedStores);*/


        $stores = Store::join('store_warehouse', function ($join) use ($warehouseCode) {
            $join->on('store_warehouse.store_code', '=', 'stores_detail.store_code')
                ->where('store_warehouse.warehouse_code', $warehouseCode)
                ->where('store_warehouse.connection_status', 1);
        })->leftJoin('store_orders', function ($join) {
            $join->on('store_orders.store_code', '=', 'stores_detail.store_code')
                ->where(function ($query) {
                    $query->whereIn('store_orders.delivery_status', ['processing', 'accepted', 'ready_to_dispatch']);
                })->where('store_orders.has_merged', 0);
        })->leftJoin('store_pre_orders_view', function ($join) {
            $join->on('store_pre_orders_view.store_code', '=', 'stores_detail.store_code')
                ->where(function ($query) {
                    $query->whereIn('store_pre_orders_view.status', ['processing', 'finalized', 'ready_to_dispatch']);
                })->where('store_pre_orders_view.has_merged', 0);
        }) ->leftJoin('warehouse_preorder_listings', function ($join) use ($warehouseCode) {
            $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'store_pre_orders_view.warehouse_preorder_listing_code')
                ->where('warehouse_preorder_listings.warehouse_code', $warehouseCode);
        })->leftJoin('bill_merge_master', function ($join) use ($warehouseCode) {
            $join->on('bill_merge_master.store_code', '=', 'stores_detail.store_code')
                ->where('bill_merge_master.warehouse_code', $warehouseCode)
                ->whereIn('bill_merge_master.status', ['pending', 'ready_to_dispatch']);
        }) ->leftJoin('bill_merge_product', function ($join) use ($warehouseCode) {
            $join->on('bill_merge_product.bill_merge_master_code',
                '=',
                'bill_merge_master.bill_merge_master_code');
        })->whereNotIn('stores_detail.store_code', function ($query) use ($warehouseCode, $dispatchRouteCode) {
            //below query for already assigned stores to pending dispatch routes
            $query->select('wh_dispatch_route_stores.store_code')
                ->from('wh_dispatch_route_stores')
                ->join('warehouse_dispatch_routes', function ($join) use ($warehouseCode) {
                    $join->on('warehouse_dispatch_routes.wh_dispatch_route_code', '=', 'wh_dispatch_route_stores.wh_dispatch_route_code')
                        ->where('warehouse_dispatch_routes.status', 'pending')
                        ->where('warehouse_dispatch_routes.warehouse_code', $warehouseCode);
                })->when($dispatchRouteCode, function ($q) use ($dispatchRouteCode) {
                    $q->where('wh_dispatch_route_stores.wh_dispatch_route_code', '!=', $dispatchRouteCode);
                })->groupBy('wh_dispatch_route_stores.store_code');
        })->select(
            'stores_detail.store_code',
            'stores_detail.store_name',
            'stores_detail.latitude',
            'stores_detail.longitude',
            'stores_detail.store_landmark_name',
            'stores_detail.store_logo',
            //'store_order_code',
            //'store_preorder_code',
            //'bill_merge_master_code',

            DB::raw('COALESCE(COUNT(DISTINCT(store_orders.store_order_code)),0) +
                           COALESCE(COUNT(DISTINCT(store_pre_orders_view.store_preorder_code)),0) +
                           COALESCE(COUNT(DISTINCT(bill_merge_master.bill_merge_master_code)),0)

                           as total_orders'),

            DB::raw("COALESCE(ROUND(SUM(store_orders.total_price),2),0) +
             COALESCE(ROUND(SUM(bill_merge_product.subtotal),2),0) +
             COALESCE(ROUND(SUM(store_pre_orders_view.total_price),2),0)
              as total_amount")
        )

            ->having('total_orders', '>', 0)
            ->groupBy(['stores_detail.store_code'])->get();
        return $stores;
    }
}
