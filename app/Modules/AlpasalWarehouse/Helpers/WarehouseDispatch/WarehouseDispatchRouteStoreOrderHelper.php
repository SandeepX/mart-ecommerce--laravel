<?php


namespace App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch;


use App\Modules\AlpasalWarehouse\Models\BillMerge\BillMergeMaster;
use App\Modules\AlpasalWarehouse\Models\WhStoreDispatch\WarehouseDispatchRouteStoreOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrderView;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreOrder;
use Illuminate\Support\Facades\DB;

class WarehouseDispatchRouteStoreOrderHelper
{

    public static function getDispatchableStoreOrdersWithoutPrice($warehouseCode, $storeCode)
    {
        $storeOrders = StoreOrder::where('store_orders.store_code', $storeCode)
            /*  ->join('stores_detail', function ($join) use ($warehouseCode) {
                  $join->on('stores_detail.store_code', '=', 'store_orders.store_code');
              })->join('store_warehouse', function ($join) use ($warehouseCode) {
                  $join->on('store_warehouse.store_code', '=', 'stores_detail.store_code')
                      ->where('store_warehouse.warehouse_code', $warehouseCode)
                      ->where('store_warehouse.connection_status', 1);
              })*/
            ->whereIn('store_orders.delivery_status', ['processing', 'accepted', 'ready_to_dispatch'])
            ->where('store_orders.has_merged', 0)
            ->where('store_orders.wh_code', $warehouseCode)
            ->select(
                'store_orders.store_order_code as order_code',
                'store_orders.delivery_status as status',
                // 'store_orders.total_price as total_amount',
               // DB::raw("ROUND(store_orders.total_price,2) as total_amount"),
                DB::raw("'normal_order' as order_type")
            )->groupBy('store_orders.store_order_code');

        $billMerges = BillMergeMaster::where('bill_merge_master.store_code', $storeCode)
            ->where('bill_merge_master.warehouse_code', $warehouseCode)
            ->whereIn('bill_merge_master.status', ['pending', 'ready_to_dispatch'])
         /*   ->join('bill_merge_product', function ($join) use ($warehouseCode) {
                $join->on('bill_merge_product.bill_merge_master_code',
                    '=',
                    'bill_merge_master.bill_merge_master_code');
            })*/
            ->select(
                'bill_merge_master.bill_merge_master_code as order_code',
                'bill_merge_master.status as status',
               // DB::raw("ROUND(SUM(bill_merge_product.subtotal),2) as total_amount"),
                DB::raw("'bill_merge' as order_type")
            )->groupBy('bill_merge_master.bill_merge_master_code');


        $storePreOrders = StorePreOrder::where('store_preorder.store_code', $storeCode)
            ->join('warehouse_preorder_listings', function ($join) use ($warehouseCode) {
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'store_preorder.warehouse_preorder_listing_code')
                    ->where('warehouse_preorder_listings.warehouse_code', $warehouseCode);
            })->whereIn('store_preorder.status', ['processing', 'finalized', 'ready_to_dispatch'])
            ->where('store_preorder.has_merged', 0)
            ->select(
                'store_preorder_code as order_code',
                'store_preorder.status as status',
                //'total_price as total_amount',
                //DB::raw("ROUND(store_preorder.total_price,2) as total_amount"),
                DB::raw("'pre_order' as order_type")
            )
            ->groupBy('store_preorder.store_preorder_code')
            ->union($storeOrders)
            ->union($billMerges)
            ->get();

        return $storePreOrders;
    }

    public static function getDispatchableStoreOrders($warehouseCode, $storeCode)
    {
        $storeOrders = StoreOrder::where('store_orders.store_code', $storeCode)
            /*  ->join('stores_detail', function ($join) use ($warehouseCode) {
                  $join->on('stores_detail.store_code', '=', 'store_orders.store_code');
              })->join('store_warehouse', function ($join) use ($warehouseCode) {
                  $join->on('store_warehouse.store_code', '=', 'stores_detail.store_code')
                      ->where('store_warehouse.warehouse_code', $warehouseCode)
                      ->where('store_warehouse.connection_status', 1);
              })*/
            ->whereIn('store_orders.delivery_status', ['processing', 'accepted', 'ready_to_dispatch'])
            ->where('store_orders.has_merged', 0)
            ->where('store_orders.wh_code', $warehouseCode)
            ->select(
                'store_orders.store_order_code as order_code',
                'store_orders.delivery_status as status',
                // 'store_orders.total_price as total_amount',
                DB::raw("ROUND(SUM(store_orders.total_price),2) as total_amount"),
                DB::raw("'normal_order' as order_type")
            )->groupBy('store_orders.store_order_code');

        $billMerges = BillMergeMaster::where('bill_merge_master.store_code', $storeCode)
            ->where('bill_merge_master.warehouse_code', $warehouseCode)
            ->whereIn('bill_merge_master.status', ['pending', 'ready_to_dispatch'])
            ->join('bill_merge_product', function ($join) use ($warehouseCode) {
                $join->on('bill_merge_product.bill_merge_master_code',
                    '=',
                    'bill_merge_master.bill_merge_master_code');
            })->select(
                'bill_merge_master.bill_merge_master_code as order_code',
                'bill_merge_master.status as status',
                DB::raw("ROUND(SUM(bill_merge_product.subtotal),2) as total_amount"),
                DB::raw("'bill_merge' as order_type")
            )->groupBy('bill_merge_master.bill_merge_master_code');


        $storePreOrders = StorePreOrderView::where('store_pre_orders_view.store_code', $storeCode)
            ->join('warehouse_preorder_listings', function ($join) use ($warehouseCode) {
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'store_pre_orders_view.warehouse_preorder_listing_code')
                    ->where('warehouse_preorder_listings.warehouse_code', $warehouseCode);
            })->whereIn('store_pre_orders_view.status', ['processing', 'finalized', 'ready_to_dispatch'])
            ->where('store_pre_orders_view.has_merged', 0)
            ->select(
                'store_preorder_code as order_code',
                'store_pre_orders_view.status as status',
                //'total_price as total_amount',
                DB::raw("ROUND(SUM(store_pre_orders_view.total_price),2) as total_amount"),
                DB::raw("'pre_order' as order_type")
            )
            ->groupBy('store_pre_orders_view.store_preorder_code')
            ->union($storeOrders)
            ->union($billMerges)
            ->get();

        return $storePreOrders;
    }

    public static function getDispatchableStoreOrdersWithExisting($warehouseCode, $storeCode)
    {
        $storeOrders = StoreOrder::where('store_orders.store_code', $storeCode)
           ->leftJoin('wh_dispatch_route_store_orders', function ($join) use ($warehouseCode) {
                $join->on('wh_dispatch_route_store_orders.order_code', '=', 'store_orders.store_order_code');
            })
            ->whereIn('store_orders.delivery_status', ['processing', 'accepted', 'ready_to_dispatch'])
            ->where('store_orders.has_merged', 0)
            ->where('store_orders.wh_code', $warehouseCode)
            ->select(
                'store_orders.store_order_code as order_code',
                'store_orders.delivery_status as status',
                //'store_orders.total_price as total_amount',
                DB::raw("ROUND(SUM(store_orders.total_price),2) as total_amount"),
                DB::raw("'normal_order' as order_type"),
                'wh_dispatch_route_store_orders.wh_dispatch_route_store_order_code',
                DB::raw("
                    (CASE
                        WHEN wh_dispatch_route_store_orders.order_code = store_orders.store_order_code
                        THEN 1
                        ELSE 0
                        END) as has_been_added
                ")
            )->groupBy('store_orders.store_order_code');

        $billMerges = BillMergeMaster::where('bill_merge_master.store_code', $storeCode)
            ->where('bill_merge_master.warehouse_code', $warehouseCode)
            ->whereIn('bill_merge_master.status', ['pending', 'ready_to_dispatch'])
            ->join('bill_merge_product', function ($join) use ($warehouseCode) {
                $join->on('bill_merge_product.bill_merge_master_code',
                    '=',
                    'bill_merge_master.bill_merge_master_code');
            })->leftJoin('wh_dispatch_route_store_orders', function ($join) use ($warehouseCode) {
                $join->on('wh_dispatch_route_store_orders.order_code', '=', 'bill_merge_master.bill_merge_master_code');
            })->select(
                'bill_merge_master.bill_merge_master_code as order_code',
                'bill_merge_master.status as status',
                DB::raw("ROUND(SUM(bill_merge_product.subtotal),2) as total_amount"),
                DB::raw("'bill_merge' as order_type"),
                'wh_dispatch_route_store_orders.wh_dispatch_route_store_order_code',
                DB::raw("
                    (CASE
                        WHEN wh_dispatch_route_store_orders.order_code = bill_merge_master.bill_merge_master_code
                        THEN 1
                        ELSE 0
                        END) as has_been_added
                ")
            )->groupBy('bill_merge_master.bill_merge_master_code');


        $storePreOrders = StorePreOrderView::where('store_pre_orders_view.store_code', $storeCode)
            ->join('warehouse_preorder_listings', function ($join) use ($warehouseCode) {
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'store_pre_orders_view.warehouse_preorder_listing_code')
                    ->where('warehouse_preorder_listings.warehouse_code', $warehouseCode);
            })->leftJoin('wh_dispatch_route_store_orders', function ($join) use ($warehouseCode) {
                $join->on('wh_dispatch_route_store_orders.order_code', '=', 'store_pre_orders_view.store_preorder_code');
            })
            ->whereIn('store_pre_orders_view.status', ['processing', 'finalized', 'ready_to_dispatch'])
            ->where('store_pre_orders_view.has_merged', 0)
            ->select(
                'store_pre_orders_view.store_preorder_code as order_code',
                'store_pre_orders_view.status as status',
                //'total_price as total_amount',
                DB::raw("ROUND(SUM(store_pre_orders_view.total_price),2) as total_amount"),
                DB::raw("'pre_order' as order_type"),
                'wh_dispatch_route_store_orders.wh_dispatch_route_store_order_code',
                DB::raw("
                    (CASE
                        WHEN wh_dispatch_route_store_orders.order_code = store_pre_orders_view.store_preorder_code
                        THEN 1
                        ELSE 0
                        END) as has_been_added
                ")
            )
            ->groupBy('store_pre_orders_view.store_preorder_code')
            ->union($storeOrders)
            ->union($billMerges)
            ->get();

        return $storePreOrders;
    }

    public static function getDispatchRouteStoreOrders($dispatchRouteCode)
    {
        $routeStoreOrders = WarehouseDispatchRouteStoreOrder::join('wh_dispatch_route_stores', function ($join) {
            $join->on('wh_dispatch_route_stores.wh_dispatch_route_store_code', '=', 'wh_dispatch_route_store_orders.wh_dispatch_route_store_code');
        })->join('warehouse_dispatch_routes', function ($join) use ($dispatchRouteCode) {
            $join->on('wh_dispatch_route_stores.wh_dispatch_route_code', '=', 'warehouse_dispatch_routes.wh_dispatch_route_code')
                ->where('warehouse_dispatch_routes.wh_dispatch_route_code', $dispatchRouteCode);
        })->leftJoin('store_orders', function ($join) use ($dispatchRouteCode) {
            $join->on('wh_dispatch_route_store_orders.order_code', '=', 'store_orders.store_order_code');
        })->leftJoin('bill_merge_master', function ($join) use ($dispatchRouteCode) {
            $join->on('wh_dispatch_route_store_orders.order_code', '=', 'bill_merge_master.bill_merge_master_code');
        })->leftJoin('store_pre_orders_view', function ($join) use ($dispatchRouteCode) {
            $join->on('wh_dispatch_route_store_orders.order_code', '=', 'store_pre_orders_view.store_preorder_code');
        })->select(
            'wh_dispatch_route_store_orders.wh_dispatch_route_store_order_code',
            'wh_dispatch_route_store_orders.order_code',
            'wh_dispatch_route_store_orders.order_type',
            'wh_dispatch_route_store_orders.total_amount',
            DB::raw("
                    (CASE
                        WHEN wh_dispatch_route_store_orders.order_code = store_orders.store_order_code
                        THEN store_orders.delivery_status
                        WHEN wh_dispatch_route_store_orders.order_code = store_pre_orders_view.store_preorder_code
                        THEN store_pre_orders_view.status
                        WHEN wh_dispatch_route_store_orders.order_code = bill_merge_master.bill_merge_master_code
                        THEN bill_merge_master.status
                        ELSE 'invalid'
                        END) as status
                "),
            DB::raw("
                    (CASE
                        WHEN wh_dispatch_route_store_orders.order_code = store_orders.store_order_code
                        THEN 'normal_order'
                        WHEN wh_dispatch_route_store_orders.order_code = store_pre_orders_view.store_preorder_code
                        THEN 'pre_order'
                        WHEN wh_dispatch_route_store_orders.order_code = bill_merge_master.bill_merge_master_code
                        THEN 'bill_merge'
                        ELSE 'invalid'
                        END) as order_type
                ")
        )->groupBy('wh_dispatch_route_store_orders.wh_dispatch_route_store_order_code')
            ->get();

        return $routeStoreOrders;
    }

    public static function test($warehouseCode, $storeCode)
    {
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
        })->leftJoin('bill_merge_master', function ($join) use ($warehouseCode) {
            $join->on('bill_merge_master.store_code', '=', 'stores_detail.store_code')
                ->where('bill_merge_master.warehouse_code', $warehouseCode)
                ->whereIn('bill_merge_master.status', ['pending', 'ready_to_dispatch']);
        })->where('stores_detail.store_code', $storeCode)
            ->select(
                'stores_detail.store_code',
                'store_orders.store_order_code',
                'store_orders.delivery_status as normal_order_status',
                'store_orders.total_price as normal_order_total_amount',
                'store_pre_orders_view.store_preorder_code',
                'store_pre_orders_view.total_price as preorder_total_amount',
                'store_pre_orders_view.status as preorder_status'

            )->groupBY([
                'store_orders.store_order_code',
                'store_pre_orders_view.store_preorder_code',
            ])->get();
        return $stores;
    }
}
