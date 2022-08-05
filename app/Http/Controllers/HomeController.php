<?php

namespace App\Http\Controllers;

use App\Modules\Product\Resources\PreOrder\SinglePreOrderProductListingCollection;
use App\Modules\Product\Resources\SingleProductListingCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function singleProductDetails(){

        $query = "
        SELECT
    dt.warehouse_product_master_code,
    dt.product_code,
    dt.product_name,
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
     packageWiseQtyFromMicroCalculator(dt.micro_to_unit_value,
            dt.unit_to_macro_value,
             NULL,
            'macro',
            dt.micro_price)
            ELSE
            0
            END
            AS macro_price,
         CASE WHEN (dt.micro_to_unit_value > 0 and dt.unit_to_macro_value > 0 and dt.macro_to_super_value >0) THEN
         packageWiseQtyFromMicroCalculator(
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
            productImages.image as product_image,
            wpm.product_variant_code,
            pv.product_variant_name,
            productVariantImages.image as product_variant_image,
            wpsv.current_stock,
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
            (wpsv.current_stock
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
    JOIN warehouse_product_stock_view wpsv ON wpsv.code = wpm.warehouse_product_master_code
        AND (wpsv.current_stock > 0)
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
        AND micro_carts.user_code = 'U00000003'
        AND micro_carts.deleted_at IS NULL
    LEFT JOIN carts AS unit_carts ON wpm.product_code = unit_carts.product_code
        AND (wpm.product_variant_code = unit_carts.product_variant_code
        OR (wpm.product_variant_code IS NULL
        AND unit_carts.product_variant_code IS NULL))
        AND (unit_package.package_code = unit_carts.package_code)
        AND unit_carts.user_code = 'U00000003'
        AND unit_carts.deleted_at IS NULL
    LEFT JOIN carts AS macro_carts ON wpm.product_code = macro_carts.product_code
        AND (wpm.product_variant_code = macro_carts.product_variant_code
        OR (wpm.product_variant_code IS NULL
        AND macro_carts.product_variant_code IS NULL))
        AND (macro_package.package_code = macro_carts.package_code)
        AND macro_carts.user_code = 'U00000003'
        AND unit_carts.deleted_at IS NULL
    LEFT JOIN carts AS super_carts ON wpm.product_code = super_carts.product_code
        AND (wpm.product_variant_code = super_carts.product_variant_code
        OR (wpm.product_variant_code IS NULL
        AND super_carts.product_variant_code IS NULL))
        AND (super_package.package_code = super_carts.package_code)
        AND super_carts.user_code = 'U00000003'
        AND super_carts.deleted_at IS NULL
    WHERE
        wpm.warehouse_code = 'AW1001' AND wpm.is_active = 1
            AND wpm.product_code = 'P1021'
    ) dt
GROUP BY dt.warehouse_product_master_code,dt.product_variant_code
        ";

        $results = DB::select($query);

        return  new SingleProductListingCollection(collect($results));
    }

    public function singlePreOrderProductDetails(){

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
     packageWiseQtyFromMicroCalculator(dt.micro_to_unit_value,
            dt.unit_to_macro_value,
             NULL,
            'macro',
            dt.micro_price)
            ELSE
            0
            END
            AS macro_price,
         CASE WHEN (dt.micro_to_unit_value > 0 and dt.unit_to_macro_value > 0 and dt.macro_to_super_value >0) THEN
         packageWiseQtyFromMicroCalculator(
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
    LEFT JOIN store_preorder spo ON spo.warehouse_preorder_listing_code = wpl.warehouse_preorder_listing_code AND (spo.store_code = 'S1000' and spo.deleted_at is NULL)
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
    where wpp.warehouse_preorder_listing_code = 'WPLC1073' and wpp.product_code = 'P1732' and wpp.is_active =1  and wpp.deleted_at is NULL  ) dt
        ";

        $results = DB::select($query);

        return new SinglePreOrderProductListingCollection($results);


    }

}
