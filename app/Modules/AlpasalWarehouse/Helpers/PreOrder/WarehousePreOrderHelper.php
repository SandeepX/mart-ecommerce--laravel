<?php


namespace App\Modules\AlpasalWarehouse\Helpers\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Support\Facades\DB;


class WarehousePreOrderHelper
{

    public static function doesPreOrderConsistProduct($warehousePreOrderCode, $productCode)
    {
        $products = WarehousePreOrderProduct::where('warehouse_preorder_listing_code', $warehousePreOrderCode)
            ->where('product_code', $productCode)->count();

        if ($products > 0) {
            return true;
        }
        return false;
    }

    public static function getPreOrderProductsWithPrice($warehousePreOrderCode, $productCode)
    {

        $query = "select table1.product_code,table1.product_variant_code,table1.product_name,table1.product_variant_name,
       table2.warehouse_preorder_product_code,table2.warehouse_preorder_listing_code,table2.mrp,table2.admin_margin_type,table2.admin_margin_value,
       table2.wholesale_margin_type,table2.wholesale_margin_value,table2.retail_margin_type,
       table2.retail_margin_value,table2.is_active,table2.min_order_quantity,table2.max_order_quantity from (
           select pm.product_code,pm.product_name,pv.product_variant_code,pv.product_variant_name
           from products_master as pm left join product_variants as pv on pm.product_code=pv.product_code
           where pm.product_code = '$productCode') as table1 left JOIN (
               select warehouse_preorder_product_code,warehouse_preorder_listing_code,product_code,product_variant_code,
                      mrp,admin_margin_type,admin_margin_value,wholesale_margin_type,wholesale_margin_value,retail_margin_type,retail_margin_value,is_active,min_order_quantity,max_order_quantity from warehouse_preorder_products
               where product_code ='$productCode' and warehouse_preorder_listing_code='$warehousePreOrderCode' and deleted_at is null )
               as table2 on table1.product_code=table2.product_code and table1.product_variant_code=table2.product_variant_code
                                or table1.product_variant_code is null and table2.product_variant_code is null";

        $warehousePreOrderProducts = DB::select(DB::raw($query));

        //dd($warehousePreOrderProducts);
        return $warehousePreOrderProducts;
    }

    public static function getPreOrderProductsWithPackaging($warehousePreOrderCode, $productCode)
    {

        $query = "select
               table1.product_code,
               table1.product_variant_code,
               table1.product_name,
               table1.product_variant_name,
               table2.warehouse_preorder_product_code,
               table2.warehouse_preorder_listing_code,
                product_packaging_details.micro_unit_code,
                product_packaging_details.unit_code,
                product_packaging_details.macro_unit_code,
                product_packaging_details.super_unit_code,
                product_packaging_details.micro_to_unit_value,
                product_packaging_details.unit_to_macro_value,
                product_packaging_details.macro_to_super_value,
                micro_package_name.package_name as micro_package_name,
                unit_package_name.package_name as unit_package_name,
                macro_package_name.package_name as macro_package_name,
                super_package_name.package_name as super_package_name


              from (
                      select
                             pm.product_code,
                             pm.product_name,
                             pv.product_variant_code,
                             pv.product_variant_name
                      from products_master as pm
                      left join product_variants as pv on pm.product_code=pv.product_code
                      where pm.product_code = '$productCode'
                  ) as table1
              left JOIN
                  (
                      select warehouse_preorder_product_code,
                             warehouse_preorder_listing_code,
                             product_code,
                             product_variant_code,
                             mrp,
                             admin_margin_type,
                             admin_margin_value,
                             wholesale_margin_type,
                             wholesale_margin_value,
                             retail_margin_type,
                             retail_margin_value,
                             is_active,
                             min_order_quantity,
                             max_order_quantity
                      from warehouse_preorder_products
                      where product_code ='$productCode' and warehouse_preorder_listing_code='$warehousePreOrderCode' and deleted_at is null
                    )as table2
                on table1.product_code=table2.product_code and table1.product_variant_code=table2.product_variant_code
                                       or table1.product_variant_code is null and table2.product_variant_code is null
            left JOIN product_packaging_details
            on table2.product_code=product_packaging_details.product_code  and product_packaging_details.product_variant_code=table2.product_variant_code
            or product_packaging_details.product_variant_code is null and table2.product_variant_code is null


            left JOIN package_types as micro_package_name on  micro_package_name.package_code= product_packaging_details.micro_unit_code
            left JOIN package_types as unit_package_name on  unit_package_name.package_code= product_packaging_details.unit_code
            left JOIN package_types as macro_package_name on  macro_package_name.package_code= product_packaging_details.macro_unit_code
            left JOIN package_types as super_package_name on  super_package_name.package_code= product_packaging_details.super_unit_code
 where product_packaging_details.product_code ='$productCode' and product_packaging_details.deleted_at is null
            ";

        $warehousePreOrderProducts = DB::select(DB::raw($query));

        return $warehousePreOrderProducts;
    }

    private static function oldGetPreOrderProductsWithPrice($warehousePreOrderCode, $productCode)
    {

        $warehousePreOrderProducts = WarehousePreOrderProduct::join('warehouse_preorder_listings', function ($join) {
            $join->on('warehouse_preorder_products.warehouse_preorder_listing_code', '=', 'warehouse_preorder_listings.warehouse_preorder_listing_code');
        })->join('products_master', function ($join) {
            $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
        })->leftJoin('product_variants', function ($join) {
            $join->on('warehouse_preorder_products.product_variant_code', '=', 'product_variants.product_variant_code');
        })->where('warehouse_preorder_products.warehouse_preorder_listing_code', $warehousePreOrderCode)
            ->where('warehouse_preorder_products.product_code', $productCode)
            ->orderBy('warehouse_preorder_products.mrp', 'DESC', 'NULLS LAST')->get();

        return $warehousePreOrderProducts;
    }

    public static function isWarehousePreOrdersDateOverlapping($warehouseCode, $startTime
        ,$endTime,$exceptWarehousePreOrderListingCode=null)
    {

        $qs = "select * from warehouse_preorder_listings where
                   warehouse_code ='$warehouseCode'
                AND (
                    start_time
                        between '$startTime' and '$endTime' or
                    end_time
                        between '$startTime' and '$endTime' or
                    '$startTime'
                        between  start_time and end_time  or
                    '$endTime'
                        between  start_time and end_time
                    )
                    AND warehouse_preorder_listing_code !='$exceptWarehousePreOrderListingCode'
                    and deleted_at is null";
        $warehousePreOrders = DB::select($qs);
        if ($warehousePreOrders) {
            return true;
        }
        return false;
    }

    public static function getVendorsInvolvedInWarehousePreOrderListingStorePreOrders($warehousePreOrderListingCode,
                                                                                      $filterParameters,$paginateBy=20){

//        $warehousePreOrderListings = WarehousePreOrderListing::join('warehouse_preorder_products', function ($join) {
//            $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code')
//                ->whereNull('warehouse_preorder_products.deleted_at')
//                ->where('warehouse_preorder_products.is_active',1);
//        })->join('store_preorder', function ($join) {
//            $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'store_preorder.warehouse_preorder_listing_code')
//                ->whereNull('store_preorder.deleted_at');
//        })->join('store_preorder_details', function ($join) {
//            $join->on('store_preorder_details.store_preorder_code', '=', 'store_preorder.store_preorder_code')
//                ->on('store_preorder_details.warehouse_preorder_product_code', '=', 'warehouse_preorder_products.warehouse_preorder_product_code')
//                ->where('store_preorder_details.delivery_status',1)->whereNull('store_preorder_details.deleted_at');
//        }) ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$warehousePreOrderListingCode)
//            ->select(
//                'warehouse_preorder_products.product_code'
//            )
//            ->groupBy('warehouse_preorder_products.product_code');


        $orderedPreOrderItems = StorePreOrder::select('product_code')
        ->where('store_preorder.warehouse_preorder_listing_code',$warehousePreOrderListingCode)
                                             ->join('store_preorder_details', function ($join) {
                                             $join->on('store_preorder_details.store_preorder_code', '=', 'store_preorder.store_preorder_code')
                                                  //->on('store_preorder_details.warehouse_preorder_product_code', '=', 'warehouse_preorder_products.warehouse_preorder_product_code')
                                                  ->where('store_preorder_details.delivery_status',1)
                                                  ->whereNull('store_preorder_details.deleted_at');
                                      })
            ->join('warehouse_preorder_products', function ($join) {
                                $join->on('store_preorder_details.warehouse_preorder_product_code', '=', 'warehouse_preorder_products.warehouse_preorder_product_code')
                                    ->whereNull('warehouse_preorder_products.deleted_at')
                                    ->where('warehouse_preorder_products.is_active',1);
            })
            ->groupBy('store_preorder_details.warehouse_preorder_product_code');
       //dd($orderedPreOrderItems->get());



        $vendors = Vendor::join('products_master', function ($join) {
            $join->on('vendors_detail.vendor_code', '=', 'products_master.vendor_code')->whereNull('products_master.deleted_at');
        }) ->joinSub($orderedPreOrderItems, 'pre_ordered_items_sub', function ($join) {
            $join->on('products_master.product_code', '=', 'pre_ordered_items_sub.product_code');
        })->when(isset($filterParameters['vendor_name']), function ($query) use ($filterParameters) {
            $query->where('vendors_detail.vendor_name', 'like', '%' . $filterParameters['vendor_name'] . '%');
        })->select(
            'vendors_detail.vendor_code',
            'vendors_detail.vendor_name'
        ) ->selectRaw('COUNT(pre_ordered_items_sub.product_code) as total_ordered_products')
            ->groupBy('vendors_detail.vendor_code')->paginate($paginateBy)->withQueryString();


        return $vendors;
    }




    public static function test3($warehousePreOrderListingCode){

        $warehousePreOrderListings = WarehousePreOrderListing::join('warehouse_preorder_products', function ($join) {
            $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code')
                ->whereNull('warehouse_preorder_products.deleted_at');
        })->join('store_preorder', function ($join) {
            $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'store_preorder.warehouse_preorder_listing_code')
                ->whereNull('store_preorder.deleted_at');
        })->join('store_preorder_details', function ($join) {
            $join->on('store_preorder_details.store_preorder_code', '=', 'store_preorder.store_preorder_code')
            ->on('store_preorder_details.warehouse_preorder_product_code', '=', 'warehouse_preorder_products.warehouse_preorder_product_code')
                ->where('store_preorder_details.delivery_status',1)->whereNull('store_preorder_details.deleted_at');
        }) ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->select(
                'warehouse_preorder_products.product_code'
            )
            ->groupBy('warehouse_preorder_products.product_code');


        $vendors = Vendor::join('products_master', function ($join) {
            $join->on('vendors_detail.vendor_code', '=', 'products_master.vendor_code')->whereNull('products_master.deleted_at');
        }) ->joinSub($warehousePreOrderListings, 'warehouse_preorder_listings_sub', function ($join) {
            $join->on('products_master.product_code', '=', 'warehouse_preorder_listings_sub.product_code');
        })->select(
            'vendors_detail.vendor_code',
            'vendors_detail.vendor_name'
        ) ->selectRaw('COUNT(DISTINCT warehouse_preorder_listings_sub.product_code) as total_ordered_products')
            ->groupBy('vendors_detail.vendor_code')->get();
        return $vendors;
    }

    public static function getVendorsInvolvedInWarehousePreOrdersForAdmin($warehousePreOrderListingCode,
                                                                          $filterParameters,$paginateBy=20){

        $warehousePreOrderListings = WarehousePreOrderListing::join('warehouse_preorder_products', function ($join) {
            $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code')
                ->whereNull('warehouse_preorder_products.deleted_at');
        })
         ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->select(
                'warehouse_preorder_products.product_code',
                'warehouse_preorder_products.is_active'
            )
            ->groupBy('warehouse_preorder_products.product_code');

        $vendors = Vendor::join('products_master', function ($join) {
            $join->on('vendors_detail.vendor_code', '=', 'products_master.vendor_code')->whereNull('products_master.deleted_at');
        }) ->joinSub($warehousePreOrderListings, 'warehouse_preorder_listings_sub', function ($join) {
            $join->on('products_master.product_code', '=', 'warehouse_preorder_listings_sub.product_code');
        })->select(
            'vendors_detail.vendor_code',
            'vendors_detail.vendor_name',
            DB::raw('SUM(warehouse_preorder_listings_sub.is_active) as total_active_products')
        )
            ->selectRaw('COUNT(DISTINCT warehouse_preorder_listings_sub.product_code) as total_products')
            ->groupBy('vendors_detail.vendor_code')
            ->get();

        return $vendors;
    }

    public static function getReportingData($filterParameters)
    {

//    $query =   "select
//                    wpl.pre_order_name as 'pre_order_name',
//                    wpl.warehouse_preorder_listing_code as 'WPLC',
//                    sd.store_name as 'store_name',
//                    sd.store_code as 'store_code',
//                    sd.store_owner as 'store_owner',
//                    CONCAT(province.location_name,', ',district.location_name,', ',municipality.location_name ,', ',ward.location_name) as 'store_location',
//                    sd.store_contact_phone as 'phone',
//                    sd.store_contact_mobile as 'mobile',
//                    spov.total_price as 'amount',
//                    wallets.current_balance as 'current_balance'
//                    from
//                    warehouse_preorder_listings wpl
//                    inner join store_pre_orders_view spov
//                    on spov.warehouse_preorder_listing_code = wpl.warehouse_preorder_listing_code
//                    inner join stores_detail sd
//                    on sd.store_code = spov.store_code
//
//                    where sd.store_name like '$storeName' and sd.store_owner like '$storeOwner'
//                    inner join location_hierarchy as ward on ward.location_code = sd.store_location_code
//                    where ward.location_code = '$ward'
//                    inner join location_hierarchy as municipality on ward.upper_location_code = municipality.location_code
//                    where municipality.location_code = '$municipality'
//                    inner join location_hierarchy as district on municipality.upper_location_code = district.location_code
//                    where district.location_code = '$district'
//                    inner join location_hierarchy as province on district.upper_location_code = province.location_code
//                    where province.location_code = '$province'
//                    inner join wallets
//                    on wallets.wallet_holder_code = sd.store_code and wallets.wallet_type = 'store'
//                    where wpl.is_finalized = 0
//                    and wpl.status_type = 'processing'
//                    where wpl.pre_order_name like '$preOrderName'
//                    #and wpl.warehouse_preorder_listing_code = 'WPLC1000' ";
        $query =   "select
                    wpl.pre_order_name as 'pre_order_name',
                    wpl.warehouse_preorder_listing_code as 'WPLC',
                    sd.store_name as 'store_name',
                    sd.store_code as 'store_code',
                    sd.store_owner as 'store_owner',
                    wh.warehouse_name as 'warehouse_name',
                    sd.store_full_location as 'store_full_location',
                    CONCAT(province.location_name,', ',district.location_name,', ',municipality.location_name ,', ',ward.location_name) as 'store_location',
                    sd.store_contact_phone as 'phone',
                    sd.store_contact_mobile as 'mobile',
                    spov.total_price as 'amount',
                    spov.store_preorder_code as 'store_preorder_code',
                    wallets.current_balance as 'current_balance'
                    from
                    warehouse_preorder_listings wpl
                    inner join store_pre_orders_view spov
                    on spov.warehouse_preorder_listing_code = wpl.warehouse_preorder_listing_code
                    inner join warehouses wh
                    on wh.warehouse_code = wpl.warehouse_code
                    inner join stores_detail sd
                    on sd.store_code = spov.store_code
                    inner join location_hierarchy as ward on ward.location_code = sd.store_location_code
                    inner join location_hierarchy as municipality on ward.upper_location_code = municipality.location_code
                    inner join location_hierarchy as district on municipality.upper_location_code = district.location_code
                    inner join location_hierarchy as province on district.upper_location_code = province.location_code
                    inner join wallets
                    on wallets.wallet_holder_code = sd.store_code and wallets.wallet_type = 'store'
                    where wpl.is_finalized = 0
                    and wpl.status_type = 'processing' ";

        if($filterParameters['store_name'])
        {
            $query .= ' and sd.store_name like "'.$filterParameters['store_name'].'" ';
        }
        if($filterParameters['store_owner'])
        {
            $query .= ' and sd.store_owner like "'.$filterParameters['store_owner'].'" ';
        }
        if($filterParameters['warehouse_code'])
        {
            $query .= ' and wh.warehouse_code = "'.$filterParameters['warehouse_code'].'" ';
        }
        if($filterParameters['province'])
        {
            $query .= ' and province.location_code = "'.$filterParameters['province'].'" ';
        }
        if($filterParameters['municipality'])
        {
            $query .= ' and municipality.location_code = "'.$filterParameters['municipality'].'" ';
        }
        if($filterParameters['district'])
        {
            $query .= ' and district.location_code = "'.$filterParameters['district'].'" ';
        }
        if($filterParameters['ward'])
        {
            $query .= ' and ward.location_code = "'.$filterParameters['ward'].'" ';
        }
        if($filterParameters['pre_order_name'])
        {
            $query .= ' and wpl.pre_order_name like "'.$filterParameters['pre_order_name'].'" ';
        }

        $warehousePreOrders = DB::select(DB::raw($query));
        return $warehousePreOrders;
    }

    public static function getReportingDataForExcel()
    {
        $query =   "select
                    wpl.pre_order_name as 'pre_order_name',
                    wpl.warehouse_preorder_listing_code as 'WPLC',
                    sd.store_name as 'store_name',
                    sd.store_code as 'store_code',
                    sd.store_owner as 'store_owner',
                    wh.warehouse_name as 'warehouse_name',
                    sd.store_full_location as 'store_full_location',
                    sd.store_contact_phone as 'phone',
                    sd.store_contact_mobile as 'mobile',
                    spov.total_price as 'amount',
                    spov.store_preorder_code as 'store_preorder_code',
                    wallets.current_balance as 'current_balance'
                    from
                    warehouse_preorder_listings wpl
                    inner join store_pre_orders_view spov
                    on spov.warehouse_preorder_listing_code = wpl.warehouse_preorder_listing_code
                    inner join warehouses wh
                    on wh.warehouse_code = wpl.warehouse_code
                    inner join stores_detail sd
                    on sd.store_code = spov.store_code
                    inner join wallets
                    on wallets.wallet_holder_code = sd.store_code and wallets.wallet_type = 'store'
                    where wpl.is_finalized = 0
                    and wpl.status_type = 'processing' ";

        $warehousePreOrders = DB::select(DB::raw($query));
        return $warehousePreOrders;
    }
}
