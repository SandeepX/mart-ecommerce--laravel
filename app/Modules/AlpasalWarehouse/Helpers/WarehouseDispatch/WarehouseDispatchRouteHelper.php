<?php


namespace App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch;


use App\Modules\AlpasalWarehouse\Models\WhStoreDispatch\WarehouseDispatchRoute;
use Illuminate\Support\Facades\DB;

class WarehouseDispatchRouteHelper
{
    public static function filterDispatchRoutes(array $filterParameters,$paginateBy = 20, $with = [])
    {
        $dispatchRoutes = WarehouseDispatchRoute::with($with)
            ->when(isset($filterParameters['warehouse_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_dispatch_routes.warehouse_code', $filterParameters['warehouse_code']);
            })->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('warehouse_dispatch_routes.status', $filterParameters['status']);
            })->leftJoin('wh_dispatch_route_stores', function ($join) {
                $join->on('wh_dispatch_route_stores.wh_dispatch_route_code', '=', 'warehouse_dispatch_routes.wh_dispatch_route_code');
            })->leftJoin('stores_detail', function ($join) {
                $join->on('wh_dispatch_route_stores.store_code', '=', 'stores_detail.store_code');
            })->select(
                'warehouse_dispatch_routes.wh_dispatch_route_code',
                'warehouse_dispatch_routes.route_name',
                'warehouse_dispatch_routes.status',
                'wh_dispatch_route_stores.wh_dispatch_route_store_code',
                'wh_dispatch_route_stores.sort_order',
                'stores_detail.store_code',
                'stores_detail.store_name',
                'stores_detail.latitude',
                'stores_detail.longitude',
                'stores_detail.store_logo',
                'stores_detail.store_landmark_name'
            );

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $dispatchRoutes = $dispatchRoutes
            ->orderBy('wh_dispatch_route_stores.sort_order', 'ASC')
            ->orderBy('warehouse_dispatch_routes.id', 'DESC')
            ->paginate($paginateBy)
            ->withQueryString();
        return $dispatchRoutes;
    }
}
