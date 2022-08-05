<?php


namespace App\Modules\AlpasalWarehouse\Helpers\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;

class WarehousePreOrderPurchaseHelper
{

    public static function isPreOrderPurchasePlacedToVendor(
        $vendorCode,$warehouseCode,$warehousePreOrderListingCode)
    {
        $warehousePurchaseOrders = WarehousePurchaseOrder::where('vendor_code',$vendorCode)
            ->where('warehouse_code',$warehouseCode)->where('order_source','preorder')
            ->join('warehouse_preorder_purchase_orders', function ($join) {
                $join->on(
                    'warehouse_preorder_purchase_orders.warehouse_order_code',
                    '=',
                    'warehouse_orders.warehouse_order_code');
            })->where(
                'warehouse_preorder_purchase_orders.warehouse_preorder_listing_code',
                $warehousePreOrderListingCode
            )->count();

        if ($warehousePurchaseOrders > 0){
            return true;
        }

        return false;
    }

    public static function getVendorWisePurchasedDetailsOfPreOrderofWarehouse($vendorCode,$warehouseCode,$warehousePreOrderListingCode){


        $warehousePurchaseOrdersDetails = PurchaseOrderDetail::
        select(
            'warehouse_orders.order_date',
            'warehouse_orders.status',
            'warehouse_orders.warehouse_order_code',
            'products_master.product_name',
            'product_variants.product_variant_name',
            'warehouse_order_details.product_code',
            'warehouse_order_details.product_variant_code',
            'warehouse_order_details.is_taxable_product',
            'warehouse_order_details.quantity',
            'warehouse_order_details.unit_rate'
        )
            ->join('warehouse_orders','warehouse_order_details.warehouse_order_code','=','warehouse_orders.warehouse_order_code')
            ->where('warehouse_orders.warehouse_code',$warehouseCode)->where('order_source','preorder')
            ->where('warehouse_orders.vendor_code',$vendorCode)
            ->join('warehouse_preorder_purchase_orders', function ($join) {
                $join->on(
                    'warehouse_preorder_purchase_orders.warehouse_order_code',
                    '=',
                    'warehouse_orders.warehouse_order_code');
            })
            ->join('products_master','warehouse_order_details.product_code','=','products_master.product_code')
            ->leftjoin('product_variants','warehouse_order_details.product_variant_code','=','product_variants.product_variant_code')
            ->where(
                'warehouse_preorder_purchase_orders.warehouse_preorder_listing_code',
                $warehousePreOrderListingCode
            )
            ->get();


        return $warehousePurchaseOrdersDetails;

    }
}
