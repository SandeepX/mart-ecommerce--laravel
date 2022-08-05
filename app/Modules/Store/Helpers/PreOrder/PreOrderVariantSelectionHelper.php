<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 11:08 AM
 */

namespace App\Modules\Store\Helpers\PreOrder;

use Exception;
use Illuminate\Support\Facades\DB;

class PreOrderVariantSelectionHelper
{

    public static function getPreOrderVariantLookUpQuery($preOrderListingCode,$productCode){
        $bindings = [
            'product_code' => $productCode,
            'pre_order_listing_code'=>$preOrderListingCode
        ];
        $query = '
      WITH variantsLookup AS (
            select distinct
                     `variant_values`.`variant_code`,
                     `product_variant_details`.`id`,
                        `variant_values`.`variant_value_name`,
                        `variant_values`.`variant_value_code`,
                        `variant_values`.`slug` as `variant_value_slug`,
                         `product_variants`.`product_variant_code`,
                        `variants`.variant_name ,
                        `variants`.slug,
                        ROW_NUMBER() OVER (ORDER BY id asc) as row_num
                        from `product_variants`
                        inner join `product_variant_details`
                        on `product_variant_details`.`product_variant_code` = `product_variants`.`product_variant_code`
                        and `product_variant_details`.`deleted_at` is null
                        inner join `variant_values` on `variant_values`.`variant_value_code` = `product_variant_details`.`variant_value_code`
                        and `variant_values`.`deleted_at` is null
                        inner join `variants` on `variants`.`variant_code` = `variant_values`.`variant_code`
                        and `variants`.`deleted_at` is null
                        where `product_variants`.`product_variant_code` in (
                        select pv.product_variant_code from product_variants pv
                           inner join warehouse_preorder_products wpp
						on wpp.product_variant_code = pv.product_variant_code
                           where wpp.product_code = "'.$bindings['product_code'].'"
                           and wpp.warehouse_preorder_listing_code = "'.$bindings['pre_order_listing_code'].'"
                             and wpp.is_active = 1 and wpp.deleted_at is null
                           )
                        and `product_variants`.`deleted_at`
                        is null
                        order by `product_variant_details`.`id` asc
            ),masterVariantsSorted AS(
               select variant_code,
               variant_name
               from variantsLookup
               group by variant_code,variant_name
            ),masterVariantsLevel As (
                 select
                variant_code,
                ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) as variant_depth
                from masterVariantsSorted
            ),
			results As (
            select
            vLookup.*,
            mvl.variant_depth
            from variantsLookup vLookup
            inner join masterVariantsLevel mvl on
            mvl.variant_code = vLookup.variant_code
            ),
            finalResults As (
              select
			  *,
			  case when row_num = 1 then NULL
                else row_num - 1
                end as prev,
                case when variant_depth = 1 then variant_value_code
                else
                 (select variant_value_code from results where row_num < prev + 1 and variant_depth = 1 order
                 by row_num desc  limit 1)
              end as ancestor_code
              from results
            )

            select * from finalResults

        ';

        return $query;
    }

    public static function getPreOrderProductFirstVariantDetails($preOrderListingCode,$productCode){

        $preOrderVariantLookUpQuery = self::getPreOrderVariantLookUpQuery(
                                                $preOrderListingCode,$productCode
                                            );
        $selectFirstVariantPartialQuery = "where finalResults.variant_code =
                    (select variant_code from finalResults order by id  limit 1)
                                   group by variant_code,variant_value_name,variant_name,variant_value_code";
        $query = $preOrderVariantLookUpQuery.$selectFirstVariantPartialQuery;

        $results = DB::select($query);
        return $results;
    }

    public static function getAssociatedPreOrderVariantDetails(
        $preOrderListingCode,
        $productCode,
        $variantValueCode,
        $variantDepth,
        $ancestorCode){
        $bindings = [
            'variant_value_code' => $variantValueCode,
            'variant_depth' => $variantDepth,
            'ancestor_code' => $ancestorCode
        ];


        $preOrderVariantLookUpQuery = self::getPreOrderVariantLookUpQuery($preOrderListingCode,$productCode);

        $partialCondition = 'where';

        if($bindings['variant_depth']) {
            $variantDepthPartialQuery = 'where variant_depth ="' . $bindings['variant_depth'] . '"';
            $preOrderVariantLookUpQuery = $preOrderVariantLookUpQuery . $variantDepthPartialQuery;
            $partialCondition = 'and ';
        }

        $variantAssociationPartialQuery = $partialCondition . ' product_variant_code in (
                            select product_variant_code from finalResults where variant_value_code = "'.$bindings['variant_value_code'].'"
                                   )
                     and variant_value_code != "'.$bindings['variant_value_code'].'"   and ancestor_code = "'.$bindings['ancestor_code'].'"
                     group by variant_code,variant_value_name,variant_name,variant_value_code';

        $query = $preOrderVariantLookUpQuery.$variantAssociationPartialQuery;


        $results = DB::select($query);
        return $results;
    }

}
