<?php


namespace App\Modules\InventoryManagement\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StoreInventoryStockSalesHelper
{

    public static function getStoreInventoryProductSalesRecordDetail($filterParameters)
    {
        try {
            $perPage = $filterParameters['perPage'];
            $query = self::getQueryForStoreInventoryProductSalesRecordDetail($filterParameters);
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

    public static function getQueryForStoreInventoryProductSalesRecordDetail($filterParameters)
    {
        $query  = "
            with storeInventoryPurchaseSubTable as (
                SELECT
                    sd.store_name,
                    sd.store_code,
                    pm.product_name,
                    pv.product_variant_name,
                    siidqd.package_code,
                    siidqd.pph_code,
                    pt.package_name,
                    siid.siid_code,
                    siid.mrp,
                    siid.cost_price,
                    siid.expiry_date,
                    siid.manufacture_date,
                    siidqd.quantity,
                    MAX(siidqd.created_at) AS created_at,
                    ppd.micro_unit_code,
                    ppd.unit_code,
                    ppd.macro_unit_code,
                    ppd.super_unit_code,
                    SUM(siidqd.quantity) AS qty
                FROM
                    store_inventory_item_dispatched_qty_detail siidqd
                        INNER JOIN
                    package_types pt ON siidqd.package_code = pt.package_code
                        INNER JOIN
                    store_inventory_item_detail siid ON siidqd.siid_code = siid.siid_code
                        INNER JOIN
                    store_inventories si ON si.store_inventory_code = siid.store_inventory_code
                        INNER JOIN
                    stores_detail sd ON sd.store_code = si.store_code
                        INNER JOIN
                    products_master pm ON pm.product_code = si.product_code
                        INNER JOIN
                    product_variants pv ON pv.product_variant_code = si.product_variant_code
                        INNER JOIN
                    product_packaging_details ppd ON ppd.product_code = si.product_code
                        AND (ppd.product_variant_code = si.product_variant_code
                        OR (ppd.product_variant_code IS NULL
                        AND si.product_variant_code IS NULL))
                WHERE
                    siidqd.deleted_at IS NULL
                        AND pm.deleted_at IS NULL
                        AND pt.deleted_at IS NULL
                        AND pv.deleted_at IS NULL ";

            if ($filterParameters['store_code']) {
                $query .= " and si.store_code = '" . $filterParameters['store_code'] . "' ";
            }

            if ($filterParameters['product_code']) {
                $query .= " and si.product_code = '" . $filterParameters['product_code'] . "' ";
            }

        $query .=" GROUP BY siid_code,package_code,pph_code
            ),
            storeInventoryRecordTable as (
                select
                    store_name,
                    store_code,
                    siid_code,
                    product_name,
                    product_variant_name,
                    mrp,
                    cost_price,
                    expiry_date,
                    manufacture_date,
                    pph_code,
                    DATE_FORMAT(MAX(created_at),'%Y-%m-%d') as created_at,
                    GROUP_CONCAT(
                        CONCAT(qty,' ',package_name)
                        ORDER BY super_unit_code,macro_unit_code,unit_code,micro_unit_code
                    ) as total_dispatched_stock
                From storeInventoryPurchaseSubTable
                GROUP BY siid_code,pph_code
            )

            SELECT * From storeInventoryRecordTable WHERE created_at IS NOT NULL ";

            if ($filterParameters['sales_from']) {
                $query .= " AND created_at >= '" . $filterParameters['sales_from'] . "' ";
            }

            if ($filterParameters['sales_to']) {
                $query .= " AND created_at <= '" . $filterParameters['sales_to'] . "' ";
            }
            $query .=" ORDER BY created_at desc ";
//            dd($query);
        return $query;
    }

    public static function getStoreInventoryStockQuantityDetail($storeInventoryData)
    {
        $storeInventoryQuantity = DB::select("
                with recievedQtyDetail as (
                        SELECT
                            package_code,
                            sum(quantity) as quantity
                        FROM
                        store_inventory_item_receiving_qty_detail
                        WHERE
                            package_code = '" .$storeInventoryData['package_code']."'
                                AND siid_code = '" .$storeInventoryData['siid_code']."'
                                AND pph_code = '" .$storeInventoryData['pph_code']."'
                                AND deleted_at IS NULL
                                group by package_code
                    ),
                    dispatchedQtyDetail as (
                        SELECT
                            package_code,
                            sum(quantity) as quantity
                        FROM
                        store_inventory_item_dispatched_qty_detail
                        WHERE
                            package_code = '" .$storeInventoryData['package_code']."'
                                AND siid_code = '" .$storeInventoryData['siid_code']."'
                                AND pph_code = '" .$storeInventoryData['pph_code']."'
                                AND deleted_at IS NULL
                                group by package_code
                    ),

                    currentStockInStoreInventoryDetail as (
                    SELECT
                        rqd.package_code,
                        COALESCE(rqd.quantity,0) as received_qty,
                        COALESCE(dqd.quantity,0) as dispatched_qty,
                        COALESCE(rqd.quantity,0) -  COALESCE(dqd.quantity,0) as remaining_quantity
                    FROM
                        recievedQtyDetail rqd
                            left JOIN
                        dispatchedQtyDetail dqd ON rqd.package_code = dqd.package_code
                    )
                    select * from currentStockInStoreInventoryDetail
        ");


        return $storeInventoryQuantity;
    }

    public static function getStoreInventoryPackageDetailWithQuantityBySIIDCodeAndPPHCode($SIIDCode,$PPHCode)
    {
        $inventoryQtyWithPackageType = DB::select("
                with recievedQtyDetail as (
                        SELECT
                            package_code,
                            sum(quantity) as quantity
                        FROM
                        store_inventory_item_receiving_qty_detail
                        WHERE
                              siid_code = '" .$SIIDCode."'
                                AND pph_code = '" .$PPHCode."'
                                AND deleted_at IS NULL
                                group by package_code
                    ),
                    dispatchedQtyDetail as (
                        SELECT
                            package_code,
                            sum(quantity) as quantity
                        FROM
                        store_inventory_item_dispatched_qty_detail
                        WHERE
                             siid_code = '" .$SIIDCode."'
                                AND pph_code = '" .$PPHCode."'
                                AND deleted_at IS NULL
                                group by package_code
                    ),

                    currentStockInStoreInventoryDetail as (
                    SELECT
                        rqd.package_code,
                        COALESCE(rqd.quantity,0) as store_in_qty,
                        COALESCE(dqd.quantity,0) as store_out_qty,
                        COALESCE(rqd.quantity,0) -  COALESCE(dqd.quantity,0) as store_remaining_quantity
                    FROM
                        recievedQtyDetail rqd
                            left join
                        dispatchedQtyDetail dqd ON rqd.package_code = dqd.package_code
                    )
                    select
                       package_types.package_name,currentStockInStoreInventoryDetail.*
                    from
                     currentStockInStoreInventoryDetail
                     inner Join package_types on package_types.package_code = currentStockInStoreInventoryDetail.package_code
        ");

        return $inventoryQtyWithPackageType;

    }

}



