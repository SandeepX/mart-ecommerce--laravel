<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/12/2020
 * Time: 3:31 PM
 */

namespace App\Modules\Product\Helpers;


use App\Modules\Product\Models\ProductMaster;
use Illuminate\Support\Facades\DB;

class ProductHelper
{

    public static function getActiveVerifiedProducts()
    {
        return ProductMaster::verified()->active()->latest()->get();
    }

    public static function getActiveVerifiedProductsCode()
    {
        return ProductMaster::verified()->active()->latest()->pluck('product_code')->toArray();
    }

    public static function getQualifiedProductsCode($with=[]){
        return ProductMaster::with($with)->qualifiedToDisplay()->latest()->pluck('product_code')->toArray();
    }

    public static function productListViewDetailsQuery($warehouseCode,$userCode,$productCode=null){

        $query = "
        SELECT
        dt.warehouse_product_master_code,
        dt.product_code,
        dt.product_name,
        dt.slug as product_slug,
        dt.product_image,
        dt.product_variant_code,
        dt.product_variant_name,
        dt.product_variant_image,
        dt.current_stock,
        dt.micro_unit_code,
        dt.micro_package_name,
        dt.micro_to_unit_value,
        dt.unit_code,
        dt.unit_package_name,
        dt.unit_to_macro_value,
        dt.macro_unit_code,
        dt.macro_package_name,
        dt.macro_to_super_value,
        dt.super_unit_code,
        dt.super_package_name,
        dt.carts_micro_quantity,
        dt.carts_unit_quantity,
        dt.carts_macro_quantity,
        dt.carts_super_quantity,
        dt.cartable_micro_stock,
         CASE WHEN (dt.micro_to_unit_value > 0) THEN
         packageWiseQtyFromMicroCalculator(dt.micro_to_unit_value,
                dt.unit_to_macro_value,
                 dt.macro_to_super_value,
                'unit',
                dt.cartable_micro_stock)
                ELSE
                0
                END
                AS cartable_unit_stock,
            CASE WHEN (dt.micro_to_unit_value > 0 and dt.unit_to_macro_value > 0) THEN
     packageWiseQtyFromMicroCalculator(dt.micro_to_unit_value,
            dt.unit_to_macro_value,
             dt.macro_to_super_value,
            'macro',
            dt.cartable_micro_stock)
            ELSE
            0
            END
            AS cartable_macro_stock,
         CASE WHEN (dt.micro_to_unit_value > 0 and dt.unit_to_macro_value > 0 and dt.macro_to_super_value >0) THEN
         packageWiseQtyFromMicroCalculator(
             dt.micro_to_unit_value,
             dt.unit_to_macro_value,
             dt.unit_to_macro_value,
             'super',
             dt.cartable_micro_stock)
             ELSE
             0
             END
             AS cartable_super_stock,
             dt.micro_price,
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
            wpm.warehouse_product_master_code,
                wpm.product_code,
                pm.product_name,
                pm.slug,
                productImages.image as product_image,
                wpm.product_variant_code,
                pv.product_variant_name,
                productVariantImages.image as product_variant_image,
                wpm.current_stock,
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
                micro_carts.quantity AS carts_micro_quantity,
                unit_carts.quantity AS carts_unit_quantity,
                macro_carts.quantity AS carts_macro_quantity,
                super_carts.quantity AS carts_super_quantity,
                (wpm.current_stock
                 -
                     CASE WHEN micro_carts.quantity >0 THEN
                     micro_carts.quantity
                     ELSE
                     0
                     END
                 -
                     CASE WHEN (unit_carts.quantity > 0 and ppd.micro_to_unit_value >0 ) THEN
                     packageWiseQtyToMicroCalculator(
                         ppd.micro_to_unit_value,NULL,NULL,'unit',unit_carts.quantity
                     )
                     ELSE
                     0
                     END
                 -
                 CASE WHEN (macro_carts.quantity > 0 and ppd.micro_to_unit_value>0 and ppd.unit_to_macro_value>0) THEN
                  packageWiseQtyToMicroCalculator(ppd.micro_to_unit_value,ppd.unit_to_macro_value,NULL,'macro',macro_carts.quantity)
                  ELSE
                  0
                  END
                 -
                 CASE WHEN (super_carts.quantity > 0 and ppd.micro_to_unit_value>0 and ppd.unit_to_macro_value >0 and ppd.macro_to_super_value > 0) THEN
                 packageWiseQtyToMicroCalculator(ppd.micro_to_unit_value,ppd.unit_to_macro_value,ppd.macro_to_super_value,'super',super_carts.quantity)
                 ELSE
                 0
                 END
        )
         as cartable_micro_stock,
         microPriceFromWarehousePriceSetting(wppm.mrp,wppm.wholesale_margin_type,wppm.wholesale_margin_value,wppm.retail_margin_type,wppm.retail_margin_value) as micro_price
        FROM
            warehouse_product_master wpm
        JOIN products_master pm ON pm.product_code = wpm.product_code AND (pm.deleted_at is NULL)
        JOIN(
            SELECT product_code,image from product_images where id in (SELECT min(id) from product_images group by product_code)
        )
            as  productImages on wpm.product_code=productImages.product_code
        LEFT JOIN(
             SELECT product_variant_code,image from product_variant_images where id in (SELECT min(id) from product_variant_images group by product_variant_code)
         )
           as productVariantImages on wpm.product_variant_code=productVariantImages.product_variant_code
        JOIN warehouse_product_price_master wppm ON wppm.warehouse_product_master_code = wpm.warehouse_product_master_code
        LEFT JOIN product_variants pv ON (pv.product_variant_code = wpm.product_variant_code)
        JOIN product_packaging_details ppd ON ppd.product_code = wpm.product_code
            AND (ppd.product_variant_code = wpm.product_variant_code
            OR (ppd.product_variant_code IS NULL
            AND wpm.product_variant_code IS NULL))
        JOIN package_types micro_package ON micro_package.package_code = ppd.micro_unit_code
            AND micro_package.deleted_at IS NULL
        JOIN package_types unit_package ON unit_package.package_code = ppd.unit_code
            AND unit_package.deleted_at IS NULL
        LEFT JOIN package_types macro_package ON macro_package.package_code = ppd.macro_unit_code
            AND macro_package.deleted_at IS NULL
        LEFT JOIN package_types super_package ON super_package.package_code = ppd.super_unit_code
            AND super_package.deleted_at IS NULL
        LEFT JOIN carts micro_carts ON wpm.product_code = micro_carts.product_code
            AND (wpm.product_variant_code = micro_carts.product_variant_code
            OR (wpm.product_variant_code IS NULL
            AND micro_carts.product_variant_code IS NULL))
            AND (micro_package.package_code = micro_carts.package_code)
            AND micro_carts.user_code = '".$userCode."'
            AND micro_carts.deleted_at IS NULL
        LEFT JOIN carts AS unit_carts ON wpm.product_code = unit_carts.product_code
            AND (wpm.product_variant_code = unit_carts.product_variant_code
            OR (wpm.product_variant_code IS NULL
            AND unit_carts.product_variant_code IS NULL))
            AND (unit_package.package_code = unit_carts.package_code)
            AND unit_carts.user_code = '".$userCode."'
            AND unit_carts.deleted_at IS NULL
        LEFT JOIN carts AS macro_carts ON wpm.product_code = macro_carts.product_code
            AND (wpm.product_variant_code = macro_carts.product_variant_code
            OR (wpm.product_variant_code IS NULL
            AND macro_carts.product_variant_code IS NULL))
            AND (macro_package.package_code = macro_carts.package_code)
            AND macro_carts.user_code = '".$userCode."'
            AND macro_carts.deleted_at IS NULL
        LEFT JOIN carts AS super_carts ON wpm.product_code = super_carts.product_code
            AND (wpm.product_variant_code = super_carts.product_variant_code
            OR (wpm.product_variant_code IS NULL
            AND super_carts.product_variant_code IS NULL))
            AND (super_package.package_code = super_carts.package_code)
            AND super_carts.user_code = '".$userCode."'
            AND super_carts.deleted_at IS NULL
        WHERE
            wpm.warehouse_code = '".$warehouseCode."'
            AND wpm.current_stock > 0
            AND wpm.is_active = 1 ";

        if($productCode){
            $query .=  "AND wpm.product_code = '".$productCode."'";
        }
        $query .= " group by wpm.warehouse_product_master_code order by wpm.warehouse_product_master_code DESC ) dt ";

        return $query;
    }

    public static function singleProductListViewDetailsOfWarehouse($warehouseCode,$userCode,$productCode){
        $query = self::productListViewDetailsQuery($warehouseCode,$userCode,$productCode);
        $results = DB::select($query);

        return $results;
    }

    public static function allProductsListViewDetailsOfWarehouse($warehouseCode,$userCode){

        $query = self::productListViewDetailsQuery($warehouseCode,$userCode);

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
