<?php

namespace App\Modules\Store\Helpers\PreOrder;

use App\Modules\Product\Resources\PreOrder\SinglePreOrderProductListingCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class PreOrderProductHelper
{
    public static function getPreOrderProductsListViewQuery($warehousePreOrderListingCode,$storeCode,$productCode=null)
    {

        $query = "
        select
           dt.*,
           CASE WHEN (dt.micro_to_unit_value > 0) THEN
              packageWisePriceFromMicroCalculator(dt.micro_to_unit_value,
                NULL,
                 NULL,
                'unit',
                dt.micro_price)
                ELSE
                0
                END
                AS unit_price,
                CASE WHEN (dt.micro_to_unit_value > 0 and dt.unit_to_macro_value > 0) THEN
         packageWisePriceFromMicroCalculator(dt.micro_to_unit_value,
                dt.unit_to_macro_value,
                 NULL,
                'macro',
                dt.micro_price)
                ELSE
                0
                END
                AS macro_price,
             CASE WHEN (dt.micro_to_unit_value > 0 and dt.unit_to_macro_value > 0 and dt.macro_to_super_value >0) THEN
             packageWisePriceFromMicroCalculator(
                 dt.micro_to_unit_value,
                 dt.unit_to_macro_value,
                 dt.unit_to_macro_value,
                 'super',
                 dt.micro_price)
                 ELSE
                 0
                 END
                 AS super_price
        FROM
        (SELECT
          wpl.warehouse_preorder_listing_code,
          wpl.warehouse_code,
          wpp.warehouse_preorder_product_code,
          wpp.product_code,
          spo.store_preorder_code,
          pm.product_name,
          pm.slug,
          productImages.image as product_image,
          wpp.product_variant_code,
          pv.product_variant_name,
          productVariantImages.image as product_variant_image,
          wpp.mrp,
          ppd.micro_unit_code,
          ppd.micro_to_unit_value,
          micro_package.package_name AS micro_package_name,
          ppd.unit_code,
          ppd.unit_to_macro_value,
          unit_package.package_name AS unit_package_name,
          ppd.macro_unit_code,
          ppd.macro_to_super_value,
          macro_package.package_name AS macro_package_name,
          ppd.super_unit_code,
          super_package.package_name AS super_package_name,
          micro_spod.quantity AS ordered_micro_quantity,
          unit_spod.quantity AS ordered_unit_quantity,
          macro_spod.quantity AS ordered_macro_quantity,
          super_spod.quantity AS  ordered_super_quantity,
        microPriceFromWarehousePriceSetting(wpp.mrp,wpp.wholesale_margin_type,wpp.wholesale_margin_value,wpp.retail_margin_type,wpp.retail_margin_value) as micro_price
        FROM warehouse_preorder_products wpp
        JOIN warehouse_preorder_listings wpl
        ON wpl.warehouse_preorder_listing_code = wpp.warehouse_preorder_listing_code
        AND (wpl.deleted_at is NULL)
        JOIN products_master pm ON pm.product_code = wpp.product_code AND (pm.deleted_at is NULL)
        LEFT JOIN product_variants pv ON pv.product_variant_code = wpp.product_variant_code AND (pm.deleted_at is NULL)
        JOIN(
             SELECT product_code,image from product_images where id in (SELECT min(id) from product_images group by product_code)
            ) as  productImages on wpp.product_code=productImages.product_code
        LEFT JOIN(
                 SELECT product_variant_code,image from product_variant_images where id in (SELECT min(id) from product_variant_images group by product_variant_code)
             )as productVariantImages on wpp.product_variant_code=productVariantImages.product_variant_code
               JOIN product_packaging_details ppd ON ppd.product_code = wpp.product_code
                AND (ppd.product_variant_code = wpp.product_variant_code
                OR (ppd.product_variant_code IS NULL
                AND wpp.product_variant_code IS NULL))
        JOIN package_types micro_package ON micro_package.package_code = ppd.micro_unit_code
            AND micro_package.deleted_at IS NULL
        JOIN package_types unit_package ON unit_package.package_code = ppd.unit_code
            AND unit_package.deleted_at IS NULL
        LEFT JOIN package_types macro_package ON macro_package.package_code = ppd.macro_unit_code
            AND macro_package.deleted_at IS NULL
        LEFT JOIN package_types super_package ON super_package.package_code = ppd.super_unit_code
            AND super_package.deleted_at IS NULL
        LEFT JOIN store_preorder spo ON spo.warehouse_preorder_listing_code = wpl.warehouse_preorder_listing_code AND (spo.store_code = '".$storeCode."' and spo.deleted_at is NULL)
        LEFT JOIN store_preorder_details  micro_spod ON micro_spod.warehouse_preorder_product_code = wpp.warehouse_preorder_product_code
              AND (micro_spod.store_preorder_code = spo.store_preorder_code)
              AND (micro_spod.package_code = micro_package.package_code)
              AND micro_spod.deleted_at is NULL
        LEFT JOIN store_preorder_details  unit_spod ON unit_spod.warehouse_preorder_product_code = wpp.warehouse_preorder_product_code
              AND (unit_spod.store_preorder_code = spo.store_preorder_code)
              AND (unit_spod.package_code = unit_package.package_code)
              AND unit_spod.deleted_at is NULL
        LEFT JOIN store_preorder_details  macro_spod ON macro_spod.warehouse_preorder_product_code = wpp.warehouse_preorder_product_code
              AND (macro_spod.store_preorder_code = spo.store_preorder_code)
              AND (macro_spod.package_code = macro_package.package_code)
              AND macro_spod.deleted_at is NULL
        LEFT JOIN store_preorder_details  super_spod ON macro_spod.warehouse_preorder_product_code = wpp.warehouse_preorder_product_code
              AND (super_spod.store_preorder_code = spo.store_preorder_code)
              AND (super_spod.package_code = super_package.package_code)
              AND super_spod.deleted_at is NULL
        where wpp.warehouse_preorder_listing_code = '".$warehousePreOrderListingCode."' ";

            if($productCode) {
                $query .= "and wpp.product_code = '" . $productCode . "' ";
            }
           $query .= "and wpp.is_active = 1  and wpp.deleted_at is NULL  ) dt ";

            return  $query;
    }

    public static function getSinglePreOrderProductListViewDetailsOfPreOrder($warehousePreOrderListingCode,$storeCode,$productCode){
        $query =  self::getPreOrderProductsListViewQuery($warehousePreOrderListingCode,$storeCode,$productCode);
        $results = DB::select($query);
        return $results;
    }

    public static function getAllPreOrderProductListViewDetailsOfPreOrder($warehousePreOrderListingCode,$storeCode){
        $query =  self::getPreOrderProductsListViewQuery($warehousePreOrderListingCode,$storeCode);

        $results = DB::select($query);
        $results = collect($results);
        $groupedByProductCode = $results->mapToGroups(function ($product, $key) {
            return
                [
                    $product->product_code => $product,
                ];
        })->values();

        return $groupedByProductCode;

    }



}














