<?php


namespace App\Modules\InventoryManagement\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InventoryPurchaseStockHelper
{
    public static function getStoreInventoryCurrentProductStockDetail($filterParameters)
    {
        try {
            $perPage = $filterParameters['perPage'];
            $query = self::getQueryForStoreInventoryCurrentProductStockDetail($filterParameters);
            $totalCount = count(DB::select($query));
            $offset = (($filterParameters['page'] - 1) * $perPage);
            if ($perPage) {
                $query .= ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
            }
            $results = DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
            return $paginator;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public static function getQueryForStoreInventoryCurrentProductStockDetail($filterParameters)
    {
        $query = "
                   select
                    dt.store_name,
                    dt.store_code,
                    dt.siid_code,
                    dt.product_name,
                    dt.product_variant_name,
                    dt.mrp,
                    dt.cost_price,
                    dt.expiry_date,
                    dt.manufacture_date,
                    dt.pph_code,
                    dt.created_at,

                     GROUP_CONCAT(
                       CONCAT(qty,' ',package_name)
                       ORDER BY super_unit_code,macro_unit_code,unit_code , micro_unit_code
                    ) as total_stock
                 from  (
                     select
                            sd.store_name,
                            sd.store_code,
                            pm.product_name,
                            # pm.product_code,
                            pv.product_variant_name,
                            #pv.product_variant_code,
                            siirqd.package_code,
                            siirqd.pph_code,
                            pt.package_name,
                            siid.siid_code,
                            siid.mrp,
                            siid.cost_price,
                            siid.expiry_date,
                            siid.manufacture_date,
                            siirqd.quantity,
                            max(siirqd.created_at) as created_at,
                            ppd.micro_unit_code,
                            ppd.unit_code,
                            ppd.macro_unit_code,
                            ppd.super_unit_code,
                            sum(siirqd.quantity) as qty
                        FROM
                            store_inventory_item_receiving_qty_detail siirqd
                                INNER JOIN
                            package_types pt ON siirqd.package_code = pt.package_code
                                INNER JOIN
                            store_inventory_item_detail siid ON siirqd.siid_code = siid.siid_code
                                INNER JOIN
                            store_inventories si ON si.store_inventory_code = siid.store_inventory_code
                                INNER JOIN
                            stores_detail sd ON sd.store_code = si.store_code
                                INNER JOIN
                            products_master pm ON pm.product_code = si.product_code
                                INNER JOIN
                            product_variants pv ON pv.product_variant_code = si.product_variant_code
                                INNER JOIN
                                product_packaging_details ppd
                               on  ppd.product_code = si.product_code and (ppd.product_variant_code = si.product_variant_code
                                        or (ppd.product_variant_code is null and si.product_variant_code is null))
                        where siirqd.deleted_at is null
                             and pm.deleted_at is null
                             and pt.deleted_at is null
                             and pv.deleted_at is null
                     ";

        if ($filterParameters['store_code']) {
            $query .= " and si.store_code = '" . $filterParameters['store_code'] . "' ";
        }

        if ($filterParameters['product_code']) {
            $query .= " and si.product_code = '" . $filterParameters['product_code'] . "' ";
        }

        if ($filterParameters['expiry_date_from']) {
            $query .= " and siid.expiry_date >= '" . $filterParameters['expiry_date_from'] . "' ";
        }

        if ($filterParameters['expiry_date_to']) {
            $query .= " AND siid.expiry_date <= '" . $filterParameters['expiry_date_to'] . "' ";
        }


        $query .=" group by siid_code,package_code,pph_code
                        ) dt
               group by siid_code,pph_code
               order by created_at desc

             ";
        return $query;
    }

    public static function getAllStorePurchasedProductsForCurrentStock($storeCode)
    {
        $query = self::getQueryForStorePurchasedProductDetail($storeCode);
        return DB::select($query);
    }

    public static function getQueryForStorePurchasedProductDetail($storeCode)
    {
        $query = "
      			WITH normalOrderSales AS (
                    SELECT
                     	SOD.product_code
                   FROM
                    	`store_orders`
                    	INNER JOIN store_order_details SOD
                        	ON store_orders.store_order_code = SOD.store_order_code
                            AND SOD.deleted_at IS NULL
                            AND SOD.acceptance_status = 'accepted'
                	WHERE
                    	store_orders.delivery_status = 'dispatched'
                        AND store_orders.deleted_at IS NULL
                        AND store_orders.store_code = '".$storeCode."'
                	GROUP BY
                	SOD.product_code
                ),

                preOrderSales AS (
                    SELECT
                    	WPP.product_code
                	FROM
                    	`store_preorder`
                    	INNER JOIN store_preorder_details SPOD
                        	ON store_preorder.store_preorder_code = SPOD.store_preorder_code
                            AND	SPOD.deleted_at is NULL
                    	INNER JOIN warehouse_preorder_products WPP
                       	ON WPP.warehouse_preorder_product_code = SPOD.warehouse_preorder_product_code
                        AND WPP.deleted_at IS NULL AND WPP.is_active = 1
                	WHERE
                    (store_preorder.status = 'dispatched')
                    AND (store_preorder.store_code = '".$storeCode."')
                    AND SPOD.delivery_status = 1
                    AND store_preorder.deleted_at IS NULL

				GROUP BY
                	WPP.product_code
            	),

                combinedDataOfNormalSalesAndProductDetail as (
                select * from normalOrderSales
					 union
					 select * from preOrderSales
                 ),

                productDetail as (
                select
						combinedDataOfNormalSalesAndProductDetail.product_code,
                        products_master.product_name
                    from combinedDataOfNormalSalesAndProductDetail
                    join products_master on products_master.product_code = combinedDataOfNormalSalesAndProductDetail.product_code
                    Group By product_code
                 )

                select * from productDetail
                ";

        $query .=' ORDER BY productDetail.product_code ASC';

        return $query;

    }

    public static function getProductPackageDetailByPPHCode($pphCode)
    {
        $query = self::getQueryForProductPackageDetailByPPHCode($pphCode);
        return  DB::select($query);
    }

    public static function getQueryForProductPackageDetailByPPHCode($pphCode)
    {
        $query = "
            With ProductPackagingdetail as (
                SELECT
                    micro_unit_code, unit_code, macro_unit_code, super_unit_code
                FROM
                    product_packaging_history
                WHERE
                    product_packaging_history_code = '".$pphCode."'
             ),

             packageMicroUnitDetail as (
                select
                    package_code, package_name
                from package_types
                inner join ProductPackagingdetail
                on ProductPackagingdetail.micro_unit_code = package_types.package_code
                where package_types.deleted_by is null
             ),
             packageUnitCodeDetail as (
                select
                package_code, package_name
                from package_types
                inner join ProductPackagingdetail
                on ProductPackagingdetail.unit_code =package_types.package_code
                where package_types.deleted_by is null
             ),
             packageMacroUnitDetail as (
                select
                package_code, package_name
                from package_types
                inner join ProductPackagingdetail
                on ProductPackagingdetail.macro_unit_code =package_types.package_code
                where package_types.deleted_by is null
             ),
             packageSuperUnitDetail as (
                select
                package_code, package_name
                from package_types
                inner join ProductPackagingdetail
                on ProductPackagingdetail.super_unit_code = package_types.package_code
                where package_types.deleted_by is null
             ),

             completePackageDetail as (
                select * from packageMicroUnitDetail
                union
                select * from packageUnitCodeDetail
                union
                 select * from packageMacroUnitDetail
                 union
                 select * from packageSuperUnitDetail
             )
             select * from completePackageDetail
        ";

        return $query;
    }


    public static function getDispatchedProductVariantDetailToStoreByProductCode($productCode,$storeCode,$variantCode=null)
    {
        $query = self::getQueryForDispatchedProductVariantDetailToStoreByProductCode($productCode,$storeCode,$variantCode);
        return  DB::select($query);
    }

    public static function getQueryForDispatchedProductVariantDetailToStoreByProductCode($productCode,$storeCode,$variantCode)
    {
       $query = "
                with productVariantDetailFromStoreNormalOrder As (
                    SELECT
                        sod.product_variant_code,
                        pv.product_variant_name
                    FROM
                        store_orders so
                            INNER JOIN
                                store_order_details sod ON so.store_order_code = sod.store_order_code
                                AND sod.deleted_at IS NULL
                            INNER JOIN
                                product_variants pv ON sod.product_variant_code = pv.product_variant_code
                                AND pv.deleted_at IS NULL

                    WHERE
                        so.delivery_status = 'dispatched'
                            AND sod.acceptance_status = 'accepted'
                            AND so.store_code = '" .$storeCode."'
                            AND sod.product_code = '" .$productCode."'
                            AND so.deleted_at IS NULL
                    GROUP BY product_variant_code
                    ),

                    productVariantDetailFromStorePreorder as (
                        SELECT
                            wpp.product_variant_code,
                            pv.product_variant_name
                        FROM
                            store_preorder spo
                            INNER JOIN
                                store_preorder_details spod on spo.store_preorder_code = spod.store_preorder_code
                                AND spod.deleted_at is NULL
                            INNER JOIN
                                warehouse_preorder_products wpp on spod.warehouse_preorder_product_code = wpp.warehouse_preorder_product_code
                                AND wpp.deleted_at is NULL AND wpp.is_active = 1
                            INNER JOIN
                                product_variants pv ON wpp.product_variant_code = pv.product_variant_code
                                AND pv.deleted_at IS NULL
                        WHERE
                            spo.status = 'dispatched'
                                AND spod.delivery_status = 1
                                AND spo.store_code = '" .$storeCode."'
                                AND wpp.product_code = '" .$productCode."'
                                AND spo.deleted_at IS NULL
                       GROUP BY product_variant_code
                    ),

                    storePurchasedProductVariantsDetail As (
                        select * from productVariantDetailFromStoreNormalOrder
                            union
                        select * from productVariantDetailFromStorePreorder
                    )

                SELECT * FROM storePurchasedProductVariantsDetail ";

                if(!is_null($variantCode)){
                    $query .= " WHERE product_variant_code  = '" . $variantCode . "' ";
                }

       return $query;
    }

}
