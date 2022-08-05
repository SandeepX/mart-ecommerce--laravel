<?php


namespace App\Modules\AlpasalWarehouse\Helpers\BillMerge;


use App\Modules\AlpasalWarehouse\Models\BillMerge\BillMergeProduct;
use App\Modules\AlpasalWarehouse\Models\Warehouse;

class BillMergeHelper
{

    public static function getBillMergeAcceptedProductForWarehouse($billMergeMasterCode,$with=[]){
       $billMergeProducts = BillMergeProduct::with($with)
                          ->select(
                              'bill_merge_product.bill_merge_product_code',
                                'bill_merge_product.bill_merge_master_code',
                                  'bill_merge_product.bill_merge_details_code',
                                  'bill_merge_product.product_code',
                                  'products_master.product_name',
                                  'bill_merge_product.product_variant_code',
                                  'bill_merge_product.package_code',
                                  'product_variants.product_variant_name',
                                  'bill_merge_product.quantity',
                                  'bill_merge_product.is_taxable',
                                  'bill_merge_product.unit_rate',
                                  'bill_merge_product.subtotal',
                                  'bill_merge_product.status',
                                  'package_types.package_name',
                                  'ordered_package_types.package_name as ordered_package_name',
                                  'product_packaging_history.micro_unit_code',
                                  'product_packaging_history.micro_to_unit_value',
                                  'product_packaging_history.unit_code',
                                  'product_packaging_history.unit_to_macro_value',
                                  'product_packaging_history.macro_unit_code',
                                  'product_packaging_history.macro_to_super_value',
                                  'product_packaging_history.super_unit_code'
                          )
                          ->where('bill_merge_master_code',$billMergeMasterCode)
                          ->where('status','accepted')
                           ->leftJoin('products_master', function ($join) {
                               $join->on('bill_merge_product.product_code', '=', 'products_master.product_code');
                           })->leftJoin('product_variants', function ($join) {
                               $join->on('bill_merge_product.product_variant_code', '=', 'product_variants.product_variant_code');
                           })
                           ->leftjoin('product_package_details',
                               'product_package_details.product_code','=','products_master.product_code')
                           ->leftJoin('package_types','package_types.package_code','=','product_package_details.package_code')
                           ->leftJoin('package_types as ordered_package_types','ordered_package_types.package_code','=','bill_merge_product.package_code')
                           ->leftJoin('product_packaging_history', function ($join) {
                               $join->on('bill_merge_product.product_packaging_history_code', '=', 'product_packaging_history.product_packaging_history_code');
                           })
                          ->get();
            return $billMergeProducts;
    }


}
