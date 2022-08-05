<?php


namespace App\Modules\AlpasalWarehouse\Helpers;

use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrderReceivedDetail;
use Illuminate\Support\Facades\DB;

class WarehousePurchaseOrderHelper
{

    public static function getWarehousePurchaseOrderDetail($warehouseOrderCode)
    {

        $purchaseReceivedDetails = WarehousePurchaseOrderReceivedDetail::select(
                                                                      'warehouse_purchase_order_received_details.warehouse_purchase_order_received_detail_code',
                                                                      'warehouse_purchase_order_received_details.warehouse_order_code',
                                                                      'warehouse_purchase_order_received_details.product_code',
                                                                      'warehouse_purchase_order_received_details.product_variant_code',
                                                                      'warehouse_purchase_order_received_details.received_quantity',
                                                                        DB::raw('GROUP_CONCAT(CONCAT(warehouse_purchase_order_received_details.package_quantity," ",package_types.package_name),"") as received_package')
                                                                   )
                                                                ->leftJoin('package_types','package_types.package_code','=','warehouse_purchase_order_received_details.package_code')
                                                                ->where('warehouse_order_code', $warehouseOrderCode)
                                                                ->groupBy(
                                                                     'warehouse_purchase_order_received_details.warehouse_order_code',
                                                                     'warehouse_purchase_order_received_details.product_code',
                                                                     'warehouse_purchase_order_received_details.product_variant_code'
                                                                );

        //dd($purchaseReceivedDetails);

        $purchaseOrderDetails = PurchaseOrderDetail::where('warehouse_order_details.warehouse_order_code', $warehouseOrderCode)
            ->join('products_master', function ($join) {
                $join->on('products_master.product_code', '=', 'warehouse_order_details.product_code');
            })
            ->leftJoin('product_variants', function ($join) {
                $join->on('product_variants.product_variant_code', '=', 'warehouse_order_details.product_variant_code');
            })
            ->leftJoin('package_types', function ($join) {
                $join->on('package_types.package_code', '=', 'warehouse_order_details.package_code');
            })
            ->leftJoin('product_packaging_history', function ($join) {
                $join->on('product_packaging_history.product_packaging_history_code',
                    '=', 'warehouse_order_details.product_packaging_history_code');
            })->leftJoin('package_types as unit_package_name', function ($join) {
                $join->on('unit_package_name.package_code',
                    '=',
                    'product_packaging_history.unit_code');
            })->leftJoin('package_types as micro_unit_package_name', function ($join) {
                $join->on('micro_unit_package_name.package_code',
                    '=',
                    'product_packaging_history.micro_unit_code');
            })->leftJoin('package_types as macro_unit_package_name', function ($join) {
                $join->on('macro_unit_package_name.package_code',
                    '=',
                    'product_packaging_history.macro_unit_code');
            })->leftJoin('package_types as super_unit_package_name', function ($join) {
                $join->on('super_unit_package_name.package_code',
                    '=',
                    'product_packaging_history.super_unit_code');
            })->leftJoinSub($purchaseReceivedDetails,'purchase_received',function ($join) {
                $join->on('warehouse_order_details.product_code','=','purchase_received.product_code');
                $join->on(function ($q) {
                    $q->on('warehouse_order_details.product_variant_code', '=', 'purchase_received.product_variant_code')
                        ->orWhere(function ($q) {
                            $q->where('warehouse_order_details.product_variant_code', null)->where('purchase_received.product_variant_code', null);
                        });
                });
            })
            ->select(
                'warehouse_order_details.warehouse_order_detail_code',
                'warehouse_order_details.warehouse_order_code',
                'warehouse_order_details.product_code',
                'warehouse_order_details.product_variant_code',
                'warehouse_order_details.package_code',
                'warehouse_order_details.product_packaging_history_code',
                'warehouse_order_details.is_taxable_product',
                'warehouse_order_details.quantity',
                DB::raw('GROUP_CONCAT(CONCAT(warehouse_order_details.package_quantity," ",package_types.package_name),"") as sending_package'),
                'purchase_received.received_quantity',
                'purchase_received.received_package',
                'warehouse_order_details.unit_rate',
                'warehouse_order_details.mrp',
                'warehouse_order_details.admin_margin_type',
                'warehouse_order_details.admin_margin_value',
                'warehouse_order_details.wholesale_margin_type',
                'warehouse_order_details.wholesale_margin_value',
                'warehouse_order_details.retail_margin_type',
                'warehouse_order_details.retail_margin_value',
                'warehouse_order_details.acceptance_status',
                'warehouse_order_details.has_received',
                'package_types.package_name as ordered_package_name',
                'products_master.product_name as product_name',
                'product_variants.product_variant_name as product_variant_name',

                'micro_unit_package_name.package_name as micro_unit_name',
                'unit_package_name.package_name as unit_name',
                'macro_unit_package_name.package_name as macro_unit_name',
                'super_unit_package_name.package_name as super_unit_name'

            )
            ->groupBy(
                'warehouse_order_details.warehouse_order_code',
                'warehouse_order_details.product_code',
                'warehouse_order_details.product_variant_code'
            )
            ->latest('warehouse_order_details.id')->get();


        return $purchaseOrderDetails;
    }

    public static function checkProductVariantExistsInWarehousePurchase($productCode,$productVariantCode = null){

        $warehousePurchaseOrderDetailCount = PurchaseOrderDetail::where('product_code',$productCode)
                                                          ->where('product_variant_code',$productVariantCode)
                                                          ->count();

        if($warehousePurchaseOrderDetailCount > 0){
            return true;
        }
         return false;
    }
}
