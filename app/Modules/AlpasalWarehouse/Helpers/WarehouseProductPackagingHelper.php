<?php


namespace App\Modules\AlpasalWarehouse\Helpers;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;

class WarehouseProductPackagingHelper
{
    public static function findWarehouseProductWithPackagingByProductCode($warehouseCode,$productCode){
        $warehouseProduct = WarehouseProductMaster::where('warehouse_product_master.warehouse_code',$warehouseCode)
            ->where('warehouse_product_master.product_code',$productCode)
            ->join('products_master', function ($join) {
                $join->on('products_master.product_code', '=', 'warehouse_product_master.product_code');
            })
            ->leftJoin('product_variants', function ($join) {
                $join->on('product_variants.product_variant_code', '=', 'warehouse_product_master.product_variant_code');
                   /* ->orWhere(function($q){
                        $q->where('warehouse_product_master.product_variant_code',null)->where('store_order_details.product_variant_code',null);
                    });*/
            })
            ->leftJoin('product_packaging_details', function ($join) {
                $join->on('product_packaging_details.product_code', '=', 'warehouse_product_master.product_code');
                $join->on(function ($q){
                    $q->on('product_packaging_details.product_variant_code','=', 'warehouse_product_master.product_variant_code')
                        ->orWhere(function($q){
                            $q->where('warehouse_product_master.product_variant_code',null)->where('product_packaging_details.product_variant_code',null);
                        });
                });
            })
            ->leftJoin('package_types as micro_package_name', function ($join) {
                $join->on('micro_package_name.package_code',
                    '=',
                    'product_packaging_details.micro_unit_code')
                    ->whereNull('product_packaging_details.deleted_at');
            })->leftJoin('package_types as unit_package_name', function ($join) {
                $join->on('unit_package_name.package_code',
                    '=',
                    'product_packaging_details.unit_code')->whereNull('product_packaging_details.deleted_at');
            })->leftJoin('package_types as macro_package_name', function ($join) {
                $join->on('macro_package_name.package_code',
                    '=',
                    'product_packaging_details.macro_unit_code')
                    ->whereNull('product_packaging_details.deleted_at');
            })->leftJoin('package_types as super_package_name', function ($join) {
                $join->on('super_package_name.package_code',
                    '=',
                    'product_packaging_details.super_unit_code')
                    ->whereNull('product_packaging_details.deleted_at');
            })->select(
                'warehouse_product_master.warehouse_product_master_code',
                'product_variants.product_variant_code',
                'products_master.product_code',
                'products_master.product_name',
                'product_variants.product_variant_name',
                'product_packaging_details.product_packaging_detail_code',

                'product_packaging_details.micro_unit_code',
                'micro_package_name.id as micro_unit_id',
                'micro_package_name.package_code as micro_unit_code',
                'micro_package_name.package_name as micro_unit_name',
                'micro_package_name.remarks as micro_unit_remarks',

                'product_packaging_details.unit_code',
                'unit_package_name.id as unit_id',
                'unit_package_name.package_code as unit_code',
                'unit_package_name.package_name as unit_name',
                'unit_package_name.remarks as unit_remarks',

                'product_packaging_details.macro_unit_code',
                'macro_package_name.id as macro_unit_id',
                'macro_package_name.package_code as macro_unit_code',
                'macro_package_name.package_name as macro_unit_name',
                'macro_package_name.remarks as macro_unit_remarks',

                'product_packaging_details.super_unit_code',
                'super_package_name.id as super_unit_id',
                'super_package_name.package_code as super_unit_code',
                'super_package_name.package_name as super_unit_name',
                'super_package_name.remarks as super_unit_remarks',

                'product_packaging_details.micro_to_unit_value',
                'product_packaging_details.unit_to_macro_value',
                'product_packaging_details.macro_to_super_value'
            )->orderBy('warehouse_product_master.created_by','DESC')->get();

        return $warehouseProduct;

    }
}
