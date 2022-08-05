<?php


namespace App\Modules\InventoryManagement\Helpers;


use Illuminate\Support\Facades\DB;

class StoreCurrentStockRecordHelper
{

    public static function getStoreInventoryProductCurrentStockDetail($filterParameters)
    {
        $subquery = DB::table('store_inventories As si')
            ->select(
                'si.product_code',
                'pm.product_name',
               'si.product_variant_code',
               'pv.product_variant_name',
                'siid.siid_code',
                'siid.cost_price',
                'siid.mrp',
                'siid.manufacture_date',
                'siid.expiry_date',
                'siirqd.pph_code',
                'siirqd.package_code',
                'pt.package_name',
                'product_packaging_details.micro_unit_code',
                'product_packaging_details.unit_code',
                'product_packaging_details.macro_unit_code',
                'product_packaging_details.super_unit_code',
                DB::raw('IFNULL(sum(siirqd.quantity),0) as stock_received_qty'),
                DB::raw('IFNULL(sum(siidqd.quantity),0) as stock_dispatched_qty'),
                DB::raw('(IFNULL(sum(siirqd.quantity),0) - IFNULL(sum(siidqd.quantity),0)) as remaining_stock_qty')
            )
                ->join('store_inventory_item_detail As siid','siid.store_inventory_code' , '=','si.store_inventory_code')
                ->join('products_master As pm','pm.product_code' , '=','si.product_code')
                ->join('product_variants As pv','pv.product_variant_code' , '=','si.product_variant_code')
                ->join('store_inventory_item_receiving_qty_detail As siirqd','siirqd.siid_code', '=','siid.siid_code')
                ->join('package_types As pt','siirqd.package_code' , '=','pt.package_code')
                ->leftJoin('store_inventory_item_dispatched_qty_detail As siidqd','siidqd.siid_code', '=','siid.siid_code')
                ->join('product_packaging_details',function($join){
                    $join->on("product_packaging_details.product_code","=","si.product_code");
                    $join->on(function($query)
                    {
                        $query->on("product_packaging_details.product_variant_code","=","si.product_variant_code");
                        $query->orOn(DB::raw('product_packaging_details.product_variant_code','=', null),
                            DB::raw('si.product_variant_code','=', null)
                        );
                    });
                })

            ->whereNull('siirqd.deleted_at')
            ->whereNull('siidqd.deleted_at')
            ->where('si.store_code',$filterParameters['store_code'])

            ->when(isset($filterParameters['product_code']),function ($query) use($filterParameters){
                $query->where('si.product_code',$filterParameters['product_code']);
            })

            ->when(isset($filterParameters['expiry_date_from']),function ($query) use($filterParameters){
                $query->where('siid.expiry_date','>=',$filterParameters['expiry_date_from']);
            })

            ->when(isset($filterParameters['expiry_date_to']),function ($query) use($filterParameters){
                $query->where('siid.expiry_date','<=',$filterParameters['expiry_date_to']);
            })

            ->groupBy(
                'siid.siid_code',
                'siirqd.pph_code',
                'siirqd.package_code'
            );


        $storeCurrentStockDetail = DB::table(DB::raw('('.$subquery->tosql().')  as table2 '))
            ->select(
                'product_code',
                'product_name',
                'product_variant_code',
                'product_variant_name',
                'siid_code',
                'cost_price',
                'mrp',
                'manufacture_date',
                'expiry_date',
                'pph_code',

                DB::raw('GROUP_CONCAT(
                    CONCAT( stock_received_qty," " ,package_name)
                           ORDER BY super_unit_code,macro_unit_code,unit_code , micro_unit_code) as
                           total_stock_received_qty'),
                DB::raw('GROUP_CONCAT(
                    CONCAT(stock_dispatched_qty," " ,package_name)
                           ORDER BY super_unit_code,macro_unit_code,unit_code , micro_unit_code) as
                           total_stock_dispatched_qty'),
                DB::raw('GROUP_CONCAT(
                    CONCAT( remaining_stock_qty," " ,package_name)
                           ORDER BY super_unit_code,macro_unit_code,unit_code , micro_unit_code) as
                           total_remaining_stock_qty')
            )

             ->groupBy('siid_code','pph_code')
            ->mergeBindings($subquery)
            ->paginate($filterParameters['perPage']);

            return $storeCurrentStockDetail;

    }


}
