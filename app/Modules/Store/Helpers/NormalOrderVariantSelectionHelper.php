<?php


namespace App\Modules\Store\Helpers;


use Illuminate\Support\Facades\DB;

class NormalOrderVariantSelectionHelper
{

    public static function getNormalOrderVariantLookUpQuery($warehouseCode,$productCode){
        $bindings = [
            'warehouse_code'=>$warehouseCode,
            'product_code' => $productCode
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
                        and `product_variants`.`product_variant_code` in (
                    select pv.product_variant_code from product_variants pv
                       inner join warehouse_product_master wpm
                       on wpm.product_variant_code = pv.product_variant_code
                       inner join warehouse_product_price_master wppm
                       on wppm.warehouse_product_master_code = wpm.warehouse_product_master_code
                       where wpm.product_code = "'.$bindings['product_code'].'"
                       and wpm.warehouse_code = "'.$bindings['warehouse_code'].'"
                       and wpm.is_active = 1
                       and wpm.current_stock > 0
                       )
                        and `product_variants`.`deleted_at`
                        is null
                        where product_variants.product_code = "'.$bindings['product_code'].'"
                        order by `product_variant_details`.`id` asc
            ),masterVariantsSorted AS(
               select variant_code,
               variant_name
               from variantsLookup
               group by variant_code,variant_name
            ),masterVariantsLevel As (
                 select
                variant_code,
                #variant_name,
                ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) as variant_depth
                from masterVariantsSorted
            ),
              results AS(select
                vLookup.*,
                mvl.variant_depth
                from variantsLookup vLookup inner join
                masterVariantsLevel mvl on
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

    public static function getNormalOrderVariantLookUpQueryForGuest($productCode){
        $bindings = [
            'product_code' => $productCode
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
                        and `product_variants`.`product_variant_code` in (
                    select pv.product_variant_code from product_variants pv
                       )
                        and `product_variants`.`deleted_at`
                        is null
                        where product_variants.product_code = "'.$bindings['product_code'].'"
                        order by `product_variant_details`.`id` asc
            ),masterVariantsSorted AS(
               select variant_code,
               variant_name
               from variantsLookup
               group by variant_code,variant_name
            ),masterVariantsLevel As (
                 select
                variant_code,
                #variant_name,
                ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) as variant_depth
                from masterVariantsSorted
            ),
              results AS(select
                vLookup.*,
                mvl.variant_depth
                from variantsLookup vLookup inner join
                masterVariantsLevel mvl on
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

    public static function getNormalOrderProductFirstVariantDetails($productCode,$warehouseCode=null){


        if($warehouseCode){
            $normalOrderVariantLookUpQuery = self::getNormalOrderVariantLookUpQuery(
                $warehouseCode,$productCode
            );
        }else{
            $normalOrderVariantLookUpQuery = self::getNormalOrderVariantLookUpQueryForGuest(
                $productCode
            );
        }


        $selectFirstVariantPartialQuery = "where finalResults.variant_code =
                    (select variant_code from finalResults order by id  limit 1)
                                   group by variant_code,variant_value_name,variant_name,variant_value_code";
        $query = $normalOrderVariantLookUpQuery.$selectFirstVariantPartialQuery;

        $results = DB::select($query);
        return $results;
    }

    public static function getAssociatedNormalOrderVariantDetails(
        $productCode,
        $variantValueCode,
        $variantDepth,
        $ancestorCode,
        $warehouseCode = null
    ){

        $bindings = [
            'variant_value_code' => $variantValueCode,
            'variant_depth' => $variantDepth,
            'ancestor_code' => $ancestorCode
        ];
        if($warehouseCode){
            $normalOrderVariantLookUpQuery = self::getNormalOrderVariantLookUpQuery($warehouseCode,$productCode);
        }else{
            $normalOrderVariantLookUpQuery = self::getNormalOrderVariantLookUpQueryForGuest($productCode);
        }


        $partialCondition = 'where';

        if($bindings['variant_depth']) {
            $variantDepthPartialQuery = 'where variant_depth ="' . $bindings['variant_depth'] . '"';
            $normalOrderVariantLookUpQuery = $normalOrderVariantLookUpQuery . $variantDepthPartialQuery;
            $partialCondition = 'and ';
        }

        $variantAssociationPartialQuery = $partialCondition . ' product_variant_code in (
                            select product_variant_code from finalResults where variant_value_code = "'.$bindings['variant_value_code'].'"
                                   )
                     and variant_value_code != "'.$bindings['variant_value_code'].'"   and ancestor_code = "'.$bindings['ancestor_code'].'"
                     group by variant_code,variant_value_name,variant_name,variant_value_code';

        $query = $normalOrderVariantLookUpQuery.$variantAssociationPartialQuery;

        $results = DB::select($query);
        return $results;

    }

}
