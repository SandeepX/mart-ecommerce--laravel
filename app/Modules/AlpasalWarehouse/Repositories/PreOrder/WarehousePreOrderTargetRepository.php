<?php


namespace App\Modules\AlpasalWarehouse\Repositories\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderTarget;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Types\Models\StoreType;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderTargetRepository
{
    public function getStoreTypes()
    {
        return StoreType::where('is_active',1)->get();
    }

    public function storeWarehousePreOrderTarget($data)
    {
                WarehousePreOrderTarget::updateOrCreate(
                    [
                        'warehouse_preorder_listing_code'=>$data['warehouse_preorder_listing_code'],
                        'store_type_code'=>$data['store_type_code'],
                        'target_type'=>$data['target_type'],
                    ],
                    $data);
    }

    public function getPreOrderTargetsOfPreOrderListing($preOrderListingCode)
    {
        $storeOrdersTotal= DB::table('store_pre_orders_view')
            ->Join('warehouse_preorder_listings',function($join) use ($preOrderListingCode){
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code','=','store_pre_orders_view.warehouse_preorder_listing_code')
                    ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$preOrderListingCode);
            })
            ->select('store_code', DB::raw('SUM(total_price) as store_total_price'))
            ->groupBy('store_code');


       $preOrderTargets=WarehousePreOrderListing::Join('store_warehouse',
           'warehouse_preorder_listings.warehouse_code','=','store_warehouse.warehouse_code')
       ->Join('stores_detail',
           'stores_detail.store_code','=','store_warehouse.store_code')
       ->Join('preorder_target',
       'preorder_target.warehouse_preorder_listing_code','=','warehouse_preorder_listings.warehouse_preorder_listing_code')
       ->Join('store_types',
       'store_types.store_type_code','=','stores_detail.store_type_code')
       ->joinSub($storeOrdersTotal, 'store_orders_total', function ($join) {
               $join->on('stores_detail.store_code', '=', 'store_orders_total.store_code');
           })
       ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$preOrderListingCode)
       ->where('preorder_target.target_type','individual')
       ->groupBy('stores_detail.store_code')
       ->select(
           'stores_detail.store_name',
           'stores_detail.store_code',
           'store_types.store_type_name',
           'preorder_target.target_type',
           'preorder_target.target_value',
           'store_orders_total.store_total_price',
           'preorder_target.store_type_code'
       )
       ->get();
       return $preOrderTargets;
    }

    public function getStoreTypeTargets($preOrderListingCode)
    {
        $storeOrdersTotal= DB::table('store_pre_orders_view')
            ->Join('stores_detail','stores_detail.store_code','=','store_pre_orders_view.store_code')
            ->Join('warehouse_preorder_listings',function($join) use ($preOrderListingCode){
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code','=','store_pre_orders_view.warehouse_preorder_listing_code');
            })
            ->select('stores_detail.store_code', DB::raw('SUM(store_pre_orders_view.total_price) as store_type_total_price'))
            ->groupBy('stores_detail.store_type_code');

        $storeTypeTargets= DB::table('stores_detail')
            ->select('stores_detail.store_type_code',
                'preorder_target.target_type',
                'preorder_target.target_value',
                'store_types.store_type_name',
                'store_orders_total.store_type_total_price'
            )
            ->joinSub($storeOrdersTotal, 'store_orders_total', function ($join) {
                $join->on('stores_detail.store_code', '=', 'store_orders_total.store_code');
            })
            ->Join('store_types','store_types.store_type_code','=','stores_detail.store_type_code')
            ->groupBy('stores_detail.store_type_code')
            ->Join('preorder_target',function($join) use($preOrderListingCode){
                $join->on('preorder_target.store_type_code','=','stores_detail.store_type_code')
                    ->where('preorder_target.warehouse_preorder_listing_code',$preOrderListingCode)
                    ->where('preorder_target.target_type','group');
            })
           ->get();

        return $storeTypeTargets;
    }

}
