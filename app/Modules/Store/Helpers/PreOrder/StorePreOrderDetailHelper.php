<?php


namespace App\Modules\Store\Helpers\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use Illuminate\Support\Facades\DB;

class StorePreOrderDetailHelper
{

    // USage : store pre order - details in warehouse section
    public static function getStorePreOrderDetailForWarehouse($storePreOrderCode,$warehouseCode, $with = [])
    {
        $storePreOrderDetails = StorePreOrderDetail::with($with)
            ->join('warehouse_preorder_products', function ($join) {
                $join->on(
                    'store_preorder_details.warehouse_preorder_product_code',
                    '=',
                    'warehouse_preorder_products.warehouse_preorder_product_code'
                );
                   // ->where('warehouse_preorder_products.is_active',1);
            })->leftJoin('products_master', function ($join) {
                $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
            })->leftJoin('product_variants', function ($join) {
                $join->on('warehouse_preorder_products.product_variant_code', '=', 'product_variants.product_variant_code');
            })->leftJoin('vendors_detail', function ($join) {
                $join->on('products_master.vendor_code', '=', 'vendors_detail.vendor_code');
            })->join('store_pre_order_detail_view', function ($join) {
                $join->on(
                    'store_preorder_details.store_preorder_detail_code',
                    '=',
                    'store_pre_order_detail_view.store_preorder_detail_code'
                )->whereNull('store_pre_order_detail_view.deleted_at');
            }) ->leftJoin('product_package_details',
                'product_package_details.product_code','=','products_master.product_code')
            ->leftJoin('package_types','package_types.package_code','=','product_package_details.package_code')
            ->leftJoin('warehouse_product_master', function ($join) use ($warehouseCode){
                $join->on('warehouse_preorder_products.product_code', '=', 'warehouse_product_master.product_code')
                    ->on(function ($q) {
                        $q->on('warehouse_preorder_products.product_variant_code', '=', 'warehouse_product_master.product_variant_code')
                            ->orWhere(function ($q) {
                                $q->where('warehouse_product_master.product_variant_code', null)->where('warehouse_preorder_products.product_variant_code', null);
                            });
                    })->where('warehouse_product_master.warehouse_code',$warehouseCode);
            })
//            ->leftJoin('warehouse_product_stock_view', function ($join) {
//                $join->on('warehouse_product_stock_view.code', '=', 'warehouse_product_master.warehouse_product_master_code');
//            })
            ->leftJoin('package_types as ordered_product_package_type', function ($join) {
                $join->on('store_preorder_details.package_code', '=', 'ordered_product_package_type.package_code');
            })
            ->leftJoin('product_packaging_history', function ($join) {
                $join->on('store_preorder_details.product_packaging_history_code', '=', 'product_packaging_history.product_packaging_history_code');
            })
            ->select(
                'store_preorder_details.*',
                'warehouse_preorder_products.warehouse_preorder_product_code',
                'warehouse_preorder_products.warehouse_preorder_listing_code',
                'products_master.product_code',
                'product_variants.product_variant_code',
                'products_master.product_name',
                'products_master.vendor_code',
                'product_variants.product_variant_name',
                'vendors_detail.vendor_name',
                'store_pre_order_detail_view.unit_rate',
                'package_types.package_name',
                'warehouse_product_master.current_stock',
                'warehouse_product_master.warehouse_product_master_code',
                'ordered_product_package_type.package_name as ordered_package_name',
                'product_packaging_history.micro_unit_code',
                'product_packaging_history.unit_code',
                'product_packaging_history.macro_unit_code',
                'product_packaging_history.super_unit_code'

            )
            ->where('store_preorder_details.store_preorder_code', $storePreOrderCode)
            //->orderBy('store_preorder_details.id', 'DESC')
           # ->groupBy('store_preorder_details.store_preorder_detail_code')
            ->get();

        return $storePreOrderDetails;
    }

    public static function getStorePreOrderAcceptedDetailForWarehouse($storePreOrderCode,$warehouseCode, $with = [])
    {
        $storePreOrderDetails = StorePreOrderDetail::with($with)
            ->join('warehouse_preorder_products', function ($join) {
                $join->on(
                    'store_preorder_details.warehouse_preorder_product_code',
                    '=',
                    'warehouse_preorder_products.warehouse_preorder_product_code'
                )->where('warehouse_preorder_products.is_active',1);
            })->leftJoin('products_master', function ($join) {
                $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
            })->leftJoin('product_variants', function ($join) {
                $join->on('warehouse_preorder_products.product_variant_code', '=', 'product_variants.product_variant_code');
            })->leftJoin('vendors_detail', function ($join) {
                $join->on('products_master.vendor_code', '=', 'vendors_detail.vendor_code');
            })->join('store_pre_order_detail_view', function ($join) {
                $join->on(
                    'store_preorder_details.store_preorder_detail_code',
                    '=',
                    'store_pre_order_detail_view.store_preorder_detail_code'
                )
                    ->whereNull('store_pre_order_detail_view.deleted_at')
                    ->where('store_pre_order_detail_view.delivery_status',1);
            }) ->leftJoin('product_package_details',
                'product_package_details.product_code','=','products_master.product_code')
            ->leftJoin('package_types','package_types.package_code','=','product_package_details.package_code')
            ->leftJoin('warehouse_product_master', function ($join) use ($warehouseCode){
                $join->on('warehouse_preorder_products.product_code', '=', 'warehouse_product_master.product_code')
                    ->on(function ($q) {
                        $q->on('warehouse_preorder_products.product_variant_code', '=', 'warehouse_product_master.product_variant_code')
                            ->orWhere(function ($q) {
                                $q->where('warehouse_product_master.product_variant_code', null)->where('warehouse_preorder_products.product_variant_code', null);
                            });
                    })->where('warehouse_product_master.warehouse_code',$warehouseCode);
            })
//            ->leftJoin('warehouse_product_stock_view', function ($join) {
//                $join->on('warehouse_product_stock_view.code', '=', 'warehouse_product_master.warehouse_product_master_code');
//            })
            ->leftJoin('package_types as ordered_product_package_type', function ($join) {
                $join->on('store_preorder_details.package_code', '=', 'ordered_product_package_type.package_code');
            })
            ->leftJoin('product_packaging_history', function ($join) {
                $join->on('store_preorder_details.product_packaging_history_code', '=', 'product_packaging_history.product_packaging_history_code');
            })
            ->select(
                'store_preorder_details.*',
                'warehouse_preorder_products.warehouse_preorder_product_code',
                'warehouse_preorder_products.warehouse_preorder_listing_code',
                'products_master.product_code',
                'products_master.product_name',
                'products_master.vendor_code',
                'product_variants.product_variant_name',
                'vendors_detail.vendor_name',
                'store_pre_order_detail_view.unit_rate',
                'package_types.package_name',
                'warehouse_product_master.current_stock',
                'warehouse_product_master.warehouse_product_master_code',
                'ordered_product_package_type.package_name as ordered_package_name',
                'product_packaging_history.micro_unit_code',
                'product_packaging_history.micro_to_unit_value',
                'product_packaging_history.unit_code',
                'product_packaging_history.unit_to_macro_value',
                'product_packaging_history.macro_unit_code',
                'product_packaging_history.macro_to_super_value',
                'product_packaging_history.super_unit_code'
            )
            ->where('store_preorder_details.store_preorder_code', $storePreOrderCode)
            ->orderBy('store_preorder_details.id', 'DESC')
            # ->groupBy('store_preorder_details.store_preorder_detail_code')
            ->get();

        return $storePreOrderDetails;
    }

    public static function isAnyStorePreOrderDetailDeliveryAccepted($storePreOrderCode)
    {

        $storePreOrderDetails = StorePreOrderDetail::where('store_preorder_code', $storePreOrderCode)
            ->where('delivery_status', 1)->count();

        //dd($storePreOrderDetails);

        if ($storePreOrderDetails > 0) {
            return true;
        }
        return false;
    }

    //used while creating warehouse pre order purchase to vendor
    public static function getStorePreOrderDetailsByVendorCode($vendorCode, $warehousePreOrderListingCode, $warehouseCode, $with = [])
    {
        $storePreOrderDetails = StorePreOrderDetail::with($with)
            ->join('warehouse_preorder_products', function ($join) {
                $join->on(
                    'store_preorder_details.warehouse_preorder_product_code',
                    '=',
                    'warehouse_preorder_products.warehouse_preorder_product_code'
                )->where('warehouse_preorder_products.is_active',1);
            })->join('warehouse_preorder_listings', function ($join) use ($warehousePreOrderListingCode) {
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code');
            })->join('products_master', function ($join) {
                $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
            })->leftJoin('product_variants', function ($join) {
                $join->on('warehouse_preorder_products.product_variant_code', '=', 'product_variants.product_variant_code');
            })->join('vendors_detail', function ($join) use ($vendorCode) {
                $join->on('products_master.vendor_code', '=', 'vendors_detail.vendor_code');
            })->leftJoin('warehouse_product_master', function ($join) use ($warehousePreOrderListingCode) {
                $join->on('warehouse_preorder_products.product_code', '=', 'warehouse_product_master.product_code')
                    ->on(function ($q) {
                        $q->on('warehouse_preorder_products.product_variant_code', '=', 'warehouse_product_master.product_variant_code')
                            ->orWhere(function ($q) {
                                $q->where('warehouse_product_master.product_variant_code', null)->where('warehouse_preorder_products.product_variant_code', null);
                            });
                    });
                /*->on('warehouse_preorder_products.product_variant_code', '=', 'warehouse_product_master.product_variant_code');*/
            })
//            ->leftJoin('warehouse_product_stock_view', function ($join) {
//                $join->on('warehouse_product_stock_view.code', '=', 'warehouse_product_master.warehouse_product_master_code');
//            })
            ->join('vendor_product_price_view', function ($join) {
                $join->on(function ($join) {
                    $join->on('vendor_product_price_view.product_code', '=', 'warehouse_preorder_products.product_code');
                    $join->on(function ($q) {
                        $q->on('vendor_product_price_view.product_variant_code', '=', 'warehouse_preorder_products.product_variant_code')
                            ->orWhere(function ($q) {
                                $q->where('vendor_product_price_view.product_variant_code', null)->where('warehouse_preorder_products.product_variant_code', null);
                            });
                    });
                });
            })
            ->where('warehouse_preorder_products.warehouse_preorder_listing_code', $warehousePreOrderListingCode)
            ->where('vendors_detail.vendor_code', $vendorCode)
            ->where('warehouse_preorder_listings.warehouse_code', $warehouseCode)
            ->where('store_preorder_details.delivery_status', 1)
            ->select(
                'store_preorder_details.store_preorder_detail_code',
                'store_preorder_details.quantity',
                'store_preorder_details.initial_order_quantity',
                'store_preorder_details.is_taxable',
                'store_preorder_details.delivery_status',
                'warehouse_preorder_products.warehouse_preorder_product_code',
                'warehouse_preorder_products.warehouse_preorder_listing_code',
                'products_master.product_code',
                'products_master.product_name',
                'products_master.vendor_code',
                'product_variants.product_variant_name',
                'product_variants.product_variant_code',
                'vendors_detail.vendor_name',
                'vendor_product_price_view.vendor_price',
                'warehouse_product_master.current_stock',
                'warehouse_preorder_listings.start_time',
                'warehouse_preorder_listings.end_time'

            )->selectRaw('SUM(quantity) as total_ordered_quantity')
            ->groupBy('warehouse_preorder_products.product_code', 'warehouse_preorder_products.product_variant_code')
            ->orderBy('store_preorder_details.id', 'DESC')->get();

        return $storePreOrderDetails;
    }

    public static function getAcceptedStorePreOrderDetailsForDispatchAndStockDeduction($storePreOrderCode)
    {
        $storePreOrderDetails = DB::select("
            SELECT
            dt.warehouse_preorder_listing_code,
             dt.warehouse_preorder_product_code AS wppc,
            dt.warehouse_code,
            dt.store_preorder_detail_code,
            dt.store_preorder_code,
            dt.warehouse_product_master_code AS wpm,
            dt.quantity,
            dt.micro_quantity,
            dt.package_code,
            dt.product_packaging_history_code,
            dt.delivery_status,

            (CASE
                WHEN
                    dt.current_stock >= dt.micro_quantity
                THEN
                    0
                ELSE 1
            END) AS cannot_be_disptached,
            dt.current_stock,
            dt.product_name,
            dt.product_variant_name
        FROM
            (SELECT
                spod.store_preorder_detail_code,
                spod.store_preorder_code,
                    spod.warehouse_preorder_product_code,
                    spod.quantity,
                    spod.delivery_status,
                    coalesce(wpm.current_stock,0) as current_stock,
                    pm.product_name,
                    pv.product_variant_name,
                    wpl.warehouse_preorder_listing_code,
                    wpm.warehouse_product_master_code,
                    wpl.warehouse_code,
                    spod.package_code,
                    spod.product_packaging_history_code,
                    (select product_packaging_to_micro_quantity_function( spod.package_code,spod.product_packaging_history_code,spod.quantity)) as micro_quantity

            FROM
                store_preorder_details spod
            INNER JOIN store_preorder spo ON spo.store_preorder_code = spod.store_preorder_code
            INNER JOIN store_pre_order_detail_view spodv ON spodv.store_preorder_detail_code = spod.store_preorder_detail_code
            INNER JOIN warehouse_preorder_products wpp ON spod.warehouse_preorder_product_code = wpp.warehouse_preorder_product_code
                    and wpp.is_active = 1
            INNER JOIN products_master pm ON pm.product_code = wpp.product_code
            LEFT JOIN product_variants pv ON pv.product_variant_code = wpp.product_variant_code
                OR pv.product_variant_code IS NULL
                AND wpp.product_variant_code IS NULL
            INNER JOIN warehouse_preorder_listings wpl ON wpl.warehouse_preorder_listing_code = wpp.warehouse_preorder_listing_code
            LEFT JOIN warehouse_product_master wpm ON wpm.warehouse_code = wpl.warehouse_code
                AND wpm.product_code = wpp.product_code
                AND (wpm.product_variant_code = wpp.product_variant_code
                OR wpm.product_variant_code IS NULL
                AND wpp.product_variant_code IS NULL)
            WHERE
                spo.store_preorder_code = '".$storePreOrderCode."'
                    AND spod.deleted_at IS NULL
                    AND spod.delivery_status = 1) dt
        ");

        return $storePreOrderDetails;
    }


    public static function getStorePreOrderDetailsByVendorCodeWithFilter($vendorCode,$warehousePreOrderListingCode,$filterParameters,$warehouseCode,$with=[]){

        $storePreOrderDetails = StorePreOrderDetail::with($with)
            ->join('warehouse_preorder_products', function ($join) {
                $join->on('store_preorder_details.warehouse_preorder_product_code', '=', 'warehouse_preorder_products.warehouse_preorder_product_code');
            })->join('warehouse_preorder_listings', function ($join) use ($warehousePreOrderListingCode) {
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code');
            })->join('products_master', function ($join) {
                $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
            })->leftJoin('product_variants', function ($join) {
                $join->on('warehouse_preorder_products.product_variant_code', '=', 'product_variants.product_variant_code');
            })->join('vendors_detail', function ($join) use($vendorCode){
                $join->on('products_master.vendor_code', '=', 'vendors_detail.vendor_code');
            })->leftJoin('warehouse_product_master', function ($join) use ($warehousePreOrderListingCode){
                $join->on('warehouse_preorder_products.product_code', '=', 'warehouse_product_master.product_code')
                    ->on(function ($q){
                        $q->on('warehouse_preorder_products.product_variant_code','=', 'warehouse_product_master.product_variant_code')
                            ->orWhere(function($q){
                                $q->where('warehouse_product_master.product_variant_code',null)->where('warehouse_preorder_products.product_variant_code',null);
                            });
                    });
                /*->on('warehouse_preorder_products.product_variant_code', '=', 'warehouse_product_master.product_variant_code');*/
            })
//            ->leftJoin('warehouse_product_stock_view', function ($join) {
//                $join->on('warehouse_product_stock_view.code', '=', 'warehouse_product_master.warehouse_product_master_code');
//            })
            ->join('vendor_product_price_view', function ($join) {
                $join->on(function($join){
                    $join->on('vendor_product_price_view.product_code','=', 'warehouse_preorder_products.product_code');
                    $join->on(function ($q){
                        $q->on('vendor_product_price_view.product_variant_code','=', 'warehouse_preorder_products.product_variant_code')
                            ->orWhere(function($q){
                                $q->where('vendor_product_price_view.product_variant_code',null)->where('warehouse_preorder_products.product_variant_code',null);
                            });
                    });
                });
            })
            ->where('warehouse_preorder_products.warehouse_preorder_listing_code', $warehousePreOrderListingCode)
            ->where('vendors_detail.vendor_code',$vendorCode)
            ->when(isset($filterParameters['product_code']),function ($query) use ($filterParameters){
               $query->where('products_master.product_code',$filterParameters['product_code']);
            })
            ->when(isset($filterParameters['product_variant_code']),function ($query) use ($filterParameters){
                $query->where('product_variants.product_variant_code',$filterParameters['product_variant_code']);
            })
            ->where('warehouse_preorder_listings.warehouse_code',$warehouseCode)
            ->where('store_preorder_details.delivery_status',1)
            ->select(
                'store_preorder_details.store_preorder_detail_code',
                'store_preorder_details.quantity',
                'store_preorder_details.initial_order_quantity',
                'store_preorder_details.is_taxable',
                'store_preorder_details.delivery_status',
                'warehouse_preorder_products.warehouse_preorder_product_code',
                'warehouse_preorder_products.warehouse_preorder_listing_code',
                'products_master.product_code',
                'products_master.product_name',
                'products_master.vendor_code',
                'product_variants.product_variant_name',
                'product_variants.product_variant_code',
                'vendors_detail.vendor_name',
                'vendor_product_price_view.vendor_price',
                'warehouse_product_master.current_stock',
                'warehouse_preorder_listings.start_time',
                'warehouse_preorder_listings.end_time'


            )->selectRaw('SUM(quantity) as total_ordered_quantity')
            ->groupBy('warehouse_preorder_products.product_code','warehouse_preorder_products.product_variant_code')
            ->orderBy('store_preorder_details.id', 'DESC')->get();

        return $storePreOrderDetails;
    }

    // getting only finalized store pre order wise - details acheived
    public static function newgetStorePreOrderDetailsByVendorCodeWithFilter($vendorCode,$warehousePreOrderListingCode,$warehouseCode,$filterParameters =null,$with=[]){


        $storePreOrderDetails  = StorePreOrderDetail::with($with)
            ->select(
                'store_preorder_details.store_preorder_detail_code',
                'store_preorder_details.quantity',
                'store_preorder_details.delivery_status',
                'products_master.product_name',
                'products_master.product_code',
                'product_variants.product_variant_name',
                'product_variants.product_variant_code',
                'vendors_detail.vendor_name',
                'vendor_product_price_view.vendor_price',
                'package_types.package_name as ordered_package_name',
                'warehouse_preorder_listings.start_time',
                'warehouse_preorder_listings.end_time',
                 'warehouse_preorder_products.warehouse_preorder_product_code',
                 DB::raw('SUM(quantity) as total_ordered_quantity')
            )
            ->join('store_preorder',function ($join){
                $join->on(
                    'store_preorder.store_preorder_code',
                    '=',
                    'store_preorder_details.store_preorder_code'
                )->where('store_preorder.status','=','finalized');
            })
            ->join('warehouse_preorder_products', function ($join) {
                $join->on(
                    'store_preorder_details.warehouse_preorder_product_code',
                    '=',
                    'warehouse_preorder_products.warehouse_preorder_product_code'
                )->where('warehouse_preorder_products.is_active',1);
            })
            ->join('warehouse_preorder_listings', function ($join) use ($warehousePreOrderListingCode) {
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code')
                     ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$warehousePreOrderListingCode);
            })
            ->join('products_master', function ($join) {
                $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
            })
            ->leftJoin('product_variants', function ($join) {
                $join->on('warehouse_preorder_products.product_variant_code', '=', 'product_variants.product_variant_code');
            })->join('vendors_detail', function ($join) use($vendorCode){
                $join->on('products_master.vendor_code', '=', 'vendors_detail.vendor_code');
            })
            ->join('vendor_product_price_view', function ($join) {
                $join->on(function($join){
                    $join->on('vendor_product_price_view.product_code','=', 'products_master.product_code');
                    $join->on(function ($q){
                        $q->on('vendor_product_price_view.product_variant_code','=', 'product_variants.product_variant_code')
                            ->orWhere(function($q){
                                $q->where('vendor_product_price_view.product_variant_code',null)->where('product_variants.product_variant_code',null);
                            });
                    });
                });
            })  ->leftJoin('package_types', function ($join) {
                $join->on('package_types.package_code', '=', 'store_preorder_details.package_code');
            })
            ->when(isset($filterParameters['product_code']),function ($query) use ($filterParameters){
                $query->where('products_master.product_code',$filterParameters['product_code']);
            })
            ->when(isset($filterParameters['product_variant_code']),function ($query) use ($filterParameters){
                $query->where('product_variants.product_variant_code',$filterParameters['product_variant_code']);
            })
            ->addSelect(DB::raw('( warehouse_preorder_products.mrp -
                (
                 CASE warehouse_preorder_products.wholesale_margin_type when "p"
                                                  Then
                                                      (warehouse_preorder_products.wholesale_margin_value/100)*warehouse_preorder_products.mrp
                                                  Else
                                                      warehouse_preorder_products.wholesale_margin_value End
                )
            - (
                 CASE warehouse_preorder_products.retail_margin_type when "p"
                                           Then
                                                      (warehouse_preorder_products.retail_margin_value/100)*warehouse_preorder_products.mrp
                                                  Else
                                                      warehouse_preorder_products.retail_margin_value End
             )
            ) as unit_price'))
            ->where('vendors_detail.vendor_code',$vendorCode)
            ->where('warehouse_preorder_listings.warehouse_code',$warehouseCode)
            ->where('store_preorder_details.delivery_status',1)
            ->groupBy(  'products_master.product_code', 'product_variants.product_variant_code')
            ->get();
        return $storePreOrderDetails;

    }


    // getting all status - store pre order wise - details acheived
    public static function newgetStorePreOrderAllStatusDetailsByVendorCodeWithFilter($vendorCode,$warehousePreOrderListingCode,$warehouseCode,$filterParameters =null,$with=[]){



        $storePreOrderDetails  = StorePreOrderDetail::with($with)
            ->select(
                'store_preorder_details.store_preorder_detail_code',
                'store_preorder_details.quantity',
                'store_preorder_details.delivery_status',
                'products_master.product_name',
                'products_master.product_code',
                'product_variants.product_variant_name',
                'product_variants.product_variant_code',
                'vendors_detail.vendor_name',
                'vendor_product_price_view.vendor_price',
                'warehouse_preorder_listings.start_time',
                'warehouse_preorder_listings.end_time',
                'ordered_product_package_type.package_name as ordered_package_name',
                'store_preorder_details.product_packaging_history_code',

                DB::raw('SUM(quantity) as total_ordered_quantity'),
                DB::raw('SUM(
                      product_packaging_to_micro_quantity_function(
                      store_preorder_details.package_code,
                      store_preorder_details.product_packaging_history_code,
                      store_preorder_details.quantity)
                     ) as total_micro_ordered_quantity')
            )
            ->join('warehouse_preorder_products', function ($join) {
                $join->on(
                    'store_preorder_details.warehouse_preorder_product_code',
                    '=',
                    'warehouse_preorder_products.warehouse_preorder_product_code'
                )->where('warehouse_preorder_products.is_active',1);
            })
            ->join('warehouse_preorder_listings', function ($join) use ($warehousePreOrderListingCode) {
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code')
                    ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$warehousePreOrderListingCode);
            })
            ->join('products_master', function ($join) {
                $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
            })
            ->leftJoin('product_variants', function ($join) {
                $join->on('warehouse_preorder_products.product_variant_code', '=', 'product_variants.product_variant_code');
            })->join('vendors_detail', function ($join) use($vendorCode){
                $join->on('products_master.vendor_code', '=', 'vendors_detail.vendor_code');
            })
            ->join('vendor_product_price_view', function ($join) {
                $join->on(function($join){
                    $join->on('vendor_product_price_view.product_code','=', 'products_master.product_code');
                    $join->on(function ($q){
                        $q->on('vendor_product_price_view.product_variant_code','=', 'product_variants.product_variant_code')
                            ->orWhere(function($q){
                                $q->where('vendor_product_price_view.product_variant_code',null)->where('product_variants.product_variant_code',null);
                            });
                    });
                });
            }) ->leftJoin('package_types as ordered_product_package_type', function ($join) {
                $join->on('store_preorder_details.package_code', '=', 'ordered_product_package_type.package_code');
            })
            ->addSelect(DB::raw('( warehouse_preorder_products.mrp -
                (
                 CASE warehouse_preorder_products.wholesale_margin_type when "p"
                                                  Then
                                                      (warehouse_preorder_products.wholesale_margin_value/100)*warehouse_preorder_products.mrp
                                                  Else
                                                      warehouse_preorder_products.wholesale_margin_value End
                )
            - (
                 CASE warehouse_preorder_products.retail_margin_type when "p"
                                           Then
                                                      (warehouse_preorder_products.retail_margin_value/100)*warehouse_preorder_products.mrp
                                                  Else
                                                      warehouse_preorder_products.retail_margin_value End
             )
            ) as unit_price'))
            ->when(isset($filterParameters['product_code']),function ($query) use ($filterParameters){
                $query->where('products_master.product_code',$filterParameters['product_code']);
            })
            ->when(isset($filterParameters['product_variant_code']),function ($query) use ($filterParameters){
                $query->where('product_variants.product_variant_code',$filterParameters['product_variant_code']);
            })
            ->where('vendors_detail.vendor_code',$vendorCode)
            ->where('warehouse_preorder_listings.warehouse_code',$warehouseCode)
            ->where('store_preorder_details.delivery_status',1)
            ->groupBy(  'products_master.product_code', 'product_variants.product_variant_code')
            ->get();

        return $storePreOrderDetails;

    }


 public static function getStoreOrderQty(
     $vendorCode,$warehousePreorderListingCode,$warehouseCode,$productCode,$productVariantCode,$with=[])
 {
     $storePreOrderDetails  = StorePreOrderDetail::with($with)
         ->select(
               'stores_detail.store_name',
             'store_preorder_details.quantity',
             'products_master.product_name',
             'stores_detail.store_code',
             'store_preorder_details.quantity',
             'store_preorder_details.package_code',
             'store_preorder_details.product_packaging_history_code',
             DB::raw('SUM(
                      product_packaging_to_micro_quantity_function(
                      store_preorder_details.package_code,
                      store_preorder_details.product_packaging_history_code,
                      store_preorder_details.quantity)) as total_micro_ordered_quantity')
         )
         ->join('warehouse_preorder_products', function ($join) use($productCode,$productVariantCode) {
             $join->on(
                 'store_preorder_details.warehouse_preorder_product_code',
                 '=',
                 'warehouse_preorder_products.warehouse_preorder_product_code'
             )->where('warehouse_preorder_products.is_active',1)
             ->where('warehouse_preorder_products.product_code',$productCode)
             ->where('warehouse_preorder_products.product_variant_code',$productVariantCode);
         })
         ->join('store_preorder',
         'store_preorder.store_preorder_code','store_preorder_details.store_preorder_code')
         ->join('products_master', function ($join) {
             $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
         })
         ->join('warehouse_preorder_listings', function ($join) use ($warehousePreorderListingCode) {
             $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code')
                 ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$warehousePreorderListingCode);
         })
         ->join('vendors_detail', function ($join) use($vendorCode){
             $join->on('products_master.vendor_code', '=', 'vendors_detail.vendor_code');
         })
         ->join('stores_detail',
         'stores_detail.store_code','store_preorder.store_code')
         ->where('store_preorder_details.delivery_status',1)
         ->where('vendors_detail.vendor_code',$vendorCode)
         ->where('warehouse_preorder_listings.warehouse_code',$warehouseCode)
         ->groupBy('stores_detail.store_code')
         ->get();

     //dd($storePreOrderDetails);

     return $storePreOrderDetails;
 }

    public static function getFinalizedStoreOrderQty($vendorCode,$warehousePreorderListingCode,$productCode,$warehouseCode,$with=[])
    {
        $storePreOrderDetails  = StorePreOrderDetail::with($with)
            ->select(
                'stores_detail.store_name',
                'store_preorder_details.quantity',
                'products_master.product_name'
            )
            ->join('warehouse_preorder_products', function ($join) use($productCode) {
                $join->on(
                    'store_preorder_details.warehouse_preorder_product_code',
                    '=',
                    'warehouse_preorder_products.warehouse_preorder_product_code'
                )->where('warehouse_preorder_products.is_active',1)
                    ->where('warehouse_preorder_products.product_code',$productCode);
            })
            ->join('store_preorder',function($join){
                $join->on('store_preorder.store_preorder_code','store_preorder_details.store_preorder_code')
                    ->where('store_preorder.status','finalized');
            })
            ->join('products_master', function ($join) {
                $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
            })
            ->join('warehouse_preorder_listings', function ($join) use ($warehousePreorderListingCode) {
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code')
                    ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$warehousePreorderListingCode);
            })
            ->join('vendors_detail', function ($join) use($vendorCode){
                $join->on('products_master.vendor_code', '=', 'vendors_detail.vendor_code');
            })
            ->join('stores_detail',
                'stores_detail.store_code','store_preorder.store_code')
            ->where('store_preorder_details.delivery_status',1)
            ->where('vendors_detail.vendor_code',$vendorCode)
            ->where('warehouse_preorder_listings.warehouse_code',$warehouseCode)
            ->get();

        return $storePreOrderDetails;
    }

    public static function getVendorWisePreOrderableProductsForPurchaseOrder($vendorCode,$warehousePreOrderListingCode,$warehouseCode,$filterParameters =null,$with=[]){

        $storePreOrderDetails  = StorePreOrderDetail::with($with)
            ->select(
                'store_preorder_details.store_preorder_detail_code',
                'store_preorder_details.quantity',
                'store_preorder_details.delivery_status',
                'products_master.product_name',
                'products_master.product_code',
                'product_variants.product_variant_name',
                'product_variants.product_variant_code',
                'vendors_detail.vendor_name',
                'vendor_product_price_view.vendor_price',
                'warehouse_preorder_listings.start_time',
                'warehouse_preorder_listings.end_time',
                'warehouse_preorder_products.warehouse_preorder_product_code',
                DB::raw('CASE WHEN warehouse_product_master.current_stock THEN warehouse_product_master.current_stock ELSE 0 END as current_stock'),
                DB::raw('SUM(quantity) as total_ordered_quantity')
            )
            ->join('store_preorder',function ($join){
                $join->on(
                    'store_preorder.store_preorder_code',
                    '=',
                    'store_preorder_details.store_preorder_code'
                )->where('store_preorder.status','=','finalized');
            })
            ->join('warehouse_preorder_products', function ($join) {
                $join->on(
                    'store_preorder_details.warehouse_preorder_product_code',
                    '=',
                    'warehouse_preorder_products.warehouse_preorder_product_code'
                )->where('warehouse_preorder_products.is_active',1);
            })
            ->join('warehouse_preorder_listings', function ($join) use ($warehousePreOrderListingCode) {
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code', '=', 'warehouse_preorder_products.warehouse_preorder_listing_code')
                    ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$warehousePreOrderListingCode);
            })
            ->join('products_master', function ($join) {
                $join->on('warehouse_preorder_products.product_code', '=', 'products_master.product_code');
            })
            ->leftJoin('product_variants', function ($join) {
                $join->on('warehouse_preorder_products.product_variant_code', '=', 'product_variants.product_variant_code');
            })->join('vendors_detail', function ($join) use($vendorCode){
                $join->on('products_master.vendor_code', '=', 'vendors_detail.vendor_code');
            })
            ->leftJoin('warehouse_product_master', function ($join) use ($warehousePreOrderListingCode){
                $join->on('warehouse_preorder_products.product_code', '=', 'warehouse_product_master.product_code')
                    ->on(function ($q){
                        $q->on('warehouse_preorder_products.product_variant_code','=', 'warehouse_product_master.product_variant_code')
                            ->orWhere(function($q){
                                $q->where('warehouse_product_master.product_variant_code',null)->where('warehouse_preorder_products.product_variant_code',null);
                            });
                    });
                /*->on('warehouse_preorder_products.product_variant_code', '=', 'warehouse_product_master.product_variant_code');*/
            })
//            ->leftJoin('warehouse_product_stock_view', function ($join) {
//                $join->on('warehouse_product_stock_view.code', '=', 'warehouse_product_master.warehouse_product_master_code');
//            })
            ->join('vendor_product_price_view', function ($join) {
                $join->on(function($join){
                    $join->on('vendor_product_price_view.product_code','=', 'products_master.product_code');
                    $join->on(function ($q){
                        $q->on('vendor_product_price_view.product_variant_code','=', 'product_variants.product_variant_code')
                            ->orWhere(function($q){
                                $q->where('vendor_product_price_view.product_variant_code',null)->where('product_variants.product_variant_code',null);
                            });
                    });
                });
            })
            ->when(isset($filterParameters['product_code']),function ($query) use ($filterParameters){
                $query->where('products_master.product_code',$filterParameters['product_code']);
            })
            ->when(isset($filterParameters['product_variant_code']),function ($query) use ($filterParameters){
                $query->where('product_variants.product_variant_code',$filterParameters['product_variant_code']);
            })
            ->addSelect(DB::raw('( warehouse_preorder_products.mrp -
                (
                 CASE warehouse_preorder_products.wholesale_margin_type when "p"
                                                  Then
                                                      (warehouse_preorder_products.wholesale_margin_value/100)*warehouse_preorder_products.mrp
                                                  Else
                                                      warehouse_preorder_products.wholesale_margin_value End
                )
            - (
                 CASE warehouse_preorder_products.retail_margin_type when "p"
                                           Then
                                                      (warehouse_preorder_products.retail_margin_value/100)*warehouse_preorder_products.mrp
                                                  Else
                                                      warehouse_preorder_products.retail_margin_value End
             )
            ) as unit_price'))
            ->where('vendors_detail.vendor_code',$vendorCode)
            ->where('warehouse_preorder_listings.warehouse_code',$warehouseCode)
            ->where('store_preorder_details.delivery_status',1)
            ->groupBy(  'products_master.product_code', 'product_variants.product_variant_code')
            ->get();
       // dd($storePreOrderDetails);
        return $storePreOrderDetails;
    }


}
