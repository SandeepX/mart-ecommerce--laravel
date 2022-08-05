<?php


namespace App\Modules\Reporting\Helpers;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WarehouseRejectedItemReportingHelper
{

    public static function getWarehouseRejectedItemReport($filterParameters)
    {
        try {
            $perPage = $filterParameters['perPage'];
            $query = self::getQueryForRejectedItemReportOfWarehouseFromRejectionTables($filterParameters);
            $totalCount = count(DB::select($query));
            $offset = (($filterParameters['page'] - 1) * $perPage);
            if ($perPage) {
                $query .= ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
            }
            $results = DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
            return $paginator;
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public static function getQueryForRejectedItemReportOfWarehouseFromRejectionTables($filterParameters)
    {
        $query = "
                WITH storeOrderRejectedItemData As (
                SELECT
                store_order_rejected_item_report.product_code,
                store_order_rejected_item_report.product_variant_code,
                store_order_rejected_item_report.unit_rate AS normal_unit_rate,
                sum(CASE
                    WHEN
                        store_order_rejected_item_report.package_code IS NOT NULL
                    THEN
                        PRODUCT_PACKAGING_TO_MICRO_QUANTITY_FUNCTION(store_order_rejected_item_report.package_code,
                                store_order_rejected_item_report.product_packaging_history_code,
                                store_order_rejected_item_report.quantity)
                    ELSE store_order_rejected_item_report.quantity
                END) AS store_order_rejected_micro_qty,
                max(store_order_rejected_item_report.store_order_updated_at) as store_order_updated_at,
                SUM(store_order_rejected_item_report.quantity * store_order_rejected_item_report.unit_rate) as normal_amount
            FROM
                store_order_rejected_item_report
            WHERE
                store_order_rejected_item_report.warehouse_code = '" . $filterParameters['warehouse_code'] . "' ";

        if ($filterParameters['from_date']) {
            $query .= " AND store_order_rejected_item_report.store_order_updated_at >= '" . $filterParameters['from_date'] . "' ";
        }

        if ($filterParameters['to_date']) {
            $query .= " AND store_order_rejected_item_report.store_order_updated_at <= '" . $filterParameters['to_date'] . "' ";
        }

        $query .= "

                group by
                store_order_rejected_item_report.product_code,
                store_order_rejected_item_report.product_variant_code
                ),

                storePreorderRejectedItemData AS (
                SELECT
                SPORIR.product_code AS preorder_product_code,
                SPORIR.product_variant_code AS preorder_product_variant_code,
                SPORIR.unit_rate AS preorder_unit_rate,
                sum(CASE
                    WHEN
                        SPORIR.package_code IS NOT NULL
                    THEN
                        PRODUCT_PACKAGING_TO_MICRO_QUANTITY_FUNCTION(SPORIR.package_code,
                                SPORIR.product_packaging_history_code,
                                SPORIR.quantity)
                    ELSE SPORIR.quantity
                END) AS preorder_rejected_micro_qty,
                max(SPORIR.store_preorder_updated_at) as preorder_updated_at,
                SUM(SPORIR.quantity * SPORIR.unit_rate) as preorder_amount
            FROM
                store_pre_order_rejected_item_report SPORIR
            WHERE
                SPORIR.warehouse_code = '" . $filterParameters['warehouse_code'] . "' ";

            if ($filterParameters['from_date']) {
                $query .= " AND SPORIR.store_preorder_updated_at >= '" . $filterParameters['from_date'] . "' ";
            }

            if ($filterParameters['to_date']) {
                $query .= " AND SPORIR.store_preorder_updated_at <= '" . $filterParameters['to_date'] . "' ";
            }

            $query .= "
                    group by
                    SPORIR.product_code,
                    SPORIR.product_variant_code
                    ),

                rejectedItemInStoreOrderAndPreorderData AS (
                select
                    COALESCE(allCombinedTable.product_code,allCombinedTable.preorder_product_code) as product_code,
                    COALESCE(allCombinedTable.product_variant_code,allCombinedTable.preorder_product_variant_code) as product_variant_code,
                    COALESCE(allCombinedTable.normal_unit_rate,allCombinedTable.preorder_unit_rate) as unit_rate,
                    COALESCE(allCombinedTable.store_order_rejected_micro_qty,0) as normal_rejected_micro_qty,
                    COALESCE(allCombinedTable.preorder_rejected_micro_qty,0) as preorder_rejected_micro_qty,
                    COALESCE(allCombinedTable.normal_amount,0) as normal_amount,
                    COALESCE(allCombinedTable.preorder_amount,0) as preorder_amount,

                    COALESCE(allCombinedTable.store_order_updated_at,allCombinedTable.preorder_updated_at) as updated_at
                    from (
                            select
                                    *
                            from
                            storeOrderRejectedItemData
                            left Join storePreorderRejectedItemData on storePreorderRejectedItemData.preorder_product_code = storeOrderRejectedItemData.product_code
                            and (storePreorderRejectedItemData.preorder_product_variant_code = storeOrderRejectedItemData.product_variant_code)
                            UNION
                            select
                                    *
                            from
                            storeOrderRejectedItemData
                            Right Join storePreorderRejectedItemData on storePreorderRejectedItemData.preorder_product_code = storeOrderRejectedItemData.product_code
                            and (storePreorderRejectedItemData.preorder_product_variant_code = storeOrderRejectedItemData.product_variant_code)
                    ) as allCombinedTable
                ),

              rejectedItemInStoreNormalOrderAndPreorderFinalReport As (
                              select
                                  rejectedItemInStoreOrderAndPreorderData.product_code,
                                  products_master.product_name,
                                  rejectedItemInStoreOrderAndPreorderData.product_variant_code,
                                  product_variants.product_variant_name,
                                  vendors_detail.vendor_code,
                                  vendors_detail.vendor_name,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.normal_rejected_micro_qty) as total_normal_rejected_qty,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.normal_amount) as total_normal_rejected_price,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.preorder_rejected_micro_qty) as total_preorder_rejected_qty,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.preorder_amount) as total_preorder_rejected_price,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.normal_rejected_micro_qty + rejectedItemInStoreOrderAndPreorderData.preorder_rejected_micro_qty) as  total_rejected_qty,
                                  sum(rejectedItemInStoreOrderAndPreorderData.normal_amount
                                      + rejectedItemInStoreOrderAndPreorderData.preorder_amount) as  total_rejected_price,
                                  rejectedItemInStoreOrderAndPreorderData.updated_at
                              from
                                rejectedItemInStoreOrderAndPreorderData
                                JOIN products_master on products_master.product_code = rejectedItemInStoreOrderAndPreorderData.product_code
                                JOIN vendors_detail on products_master.vendor_code = vendors_detail.vendor_code
                                LEFT JOIN product_variants on product_variants.product_variant_code = rejectedItemInStoreOrderAndPreorderData.product_variant_code
                              group by
                                  rejectedItemInStoreOrderAndPreorderData.product_code,
                                  rejectedItemInStoreOrderAndPreorderData.product_variant_code
                              )
                  select * from rejectedItemInStoreNormalOrderAndPreorderFinalReport where total_rejected_qty > 0
        ";

        if($filterParameters['product_code']){
            $productVariant = explode('-',$filterParameters['product_code']);
            $query .= ' AND rejectedItemInStoreNormalOrderAndPreorderFinalReport.product_code = "'.$productVariant[0].'" ';
            if(isset($productVariant[1]) && $productVariant[1]){
                $query .= ' AND rejectedItemInStoreNormalOrderAndPreorderFinalReport.product_variant_code = "'.$productVariant[1].'" ';
            }
        }

        if($filterParameters['vendor_code']){
            $vendorsCodes = "'".implode("','",$filterParameters['vendor_code'])."'";
            $query .=  "  AND  rejectedItemInStoreNormalOrderAndPreorderFinalReport.vendor_code in (".$vendorsCodes.") ";
        }

        $query .= ' ORDER BY updated_at DESC';

        return $query;

    }

    public static function getStoreWiseRejectedItemReportOfWarehouse($filterParameters)
    {
        try {
            $perPage = $filterParameters['perPage'];
            $query = self::getStoreWiseRejectedItemQtyReportOfWarehouse($filterParameters);
            $totalCount = count(DB::select($query));
            $offset = (($filterParameters['page'] - 1) * $perPage);
            if ($perPage) {
                $query .= ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
            }
            $results = DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
            //dd($results);
            return $paginator;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public static function getStoreWiseRejectedItemQtyReportOfWarehouse($filterParameters)
    {
        $query = "
                WITH storeOrderRejectedItemData As (
                SELECT
                store_orders.store_code,
                store_order_rejected_item_report.product_code,
                store_order_rejected_item_report.product_variant_code,
                store_order_rejected_item_report.unit_rate AS normal_unit_rate,
                SUM(CASE WHEN store_order_rejected_item_report.package_code IS NOT NULL THEN
                        product_packaging_to_micro_quantity_function(
                        store_order_rejected_item_report.package_code,
                        store_order_rejected_item_report.product_packaging_history_code,
                        store_order_rejected_item_report.quantity
                 )
                 ELSE store_order_rejected_item_report.quantity
                 END) as store_order_rejected_micro_qty,
                sum(store_order_rejected_item_report.unit_rate * store_order_rejected_item_report.quantity) as total_amount,
                max(store_order_rejected_item_report.store_order_updated_at) as store_order_updated_at
            FROM
                store_order_rejected_item_report
                join store_orders on store_orders.store_order_code = store_order_rejected_item_report.store_order_code
                LEFT JOIN package_types on package_types.package_code = store_order_rejected_item_report.package_code
            WHERE
                store_order_rejected_item_report.warehouse_code = '" . $filterParameters['warehouse_code'] . "'
                AND store_order_rejected_item_report.product_code = '" . $filterParameters['product_code'] . "' ";

        if ($filterParameters['product_variant_code']) {
            $query .= " and store_order_rejected_item_report.product_variant_code = '" . $filterParameters['product_variant_code'] . "' ";
        }

        if ($filterParameters['from_date']) {
            $query .= " AND store_order_rejected_item_report.store_order_updated_at >= '" . $filterParameters['from_date'] . "' ";
        }

        if ($filterParameters['to_date']) {
            $query .= " AND store_order_rejected_item_report.store_order_updated_at <= '" . $filterParameters['to_date'] . "' ";
        }

        $query .= "   group by
                    store_orders.store_code,
                    store_order_rejected_item_report.product_code,
                    store_order_rejected_item_report.product_variant_code
                ),

                storePreorderRejectedItemData AS (
                SELECT
                store_preorder.store_code as preorder_store_code,
                SPORIR.product_code AS preorder_product_code,
                SPORIR.product_variant_code AS preorder_product_variant_code,
                SPORIR.unit_rate AS preorder_unit_rate,
                SUM(CASE WHEN SPORIR.package_code IS NOT NULL THEN
                        product_packaging_to_micro_quantity_function(
                        SPORIR.package_code,
                        SPORIR.product_packaging_history_code,
                        SPORIR.quantity
                 )
                 ELSE SPORIR.quantity
                 END)   AS preorder_rejected_micro_qty,

                sum(SPORIR.unit_rate * SPORIR.quantity) as preorder_total_amount,
                max(SPORIR.store_preorder_updated_at) as preorder_updated_at
            FROM
                store_pre_order_rejected_item_report SPORIR
                join store_preorder on store_preorder.store_preorder_code = SPORIR.store_preorder_code
                LEFT JOIN package_types on package_types.package_code = SPORIR.package_code
            WHERE
                SPORIR.warehouse_code = '" . $filterParameters['warehouse_code'] . "'
                AND SPORIR.product_code = '" . $filterParameters['product_code'] . "' ";

        if ($filterParameters['product_variant_code']) {
            $query .= "  and SPORIR.product_variant_code = '" . $filterParameters['product_variant_code'] . "' ";
        }

        if ($filterParameters['from_date']) {
            $query .= " AND SPORIR.store_preorder_updated_at >= '" . $filterParameters['from_date'] . "' ";
        }

        if ($filterParameters['to_date']) {
            $query .= " AND SPORIR.store_preorder_updated_at <= '" . $filterParameters['to_date'] . "' ";
        }

        $query .= "  group by
                        SPORIR.product_code,
                        SPORIR.product_variant_code,
                        store_preorder.store_code
                ),


                rejectedItemInStoreOrderAndPreorderData AS (
                select
                    COALESCE(allCombinedTable.product_code,allCombinedTable.preorder_product_code) as product_code,
                    COALESCE(allCombinedTable.store_code,allCombinedTable.preorder_store_code) as store_code,
                    COALESCE(allCombinedTable.product_variant_code,allCombinedTable.preorder_product_variant_code) as product_variant_code,
                    COALESCE(allCombinedTable.normal_unit_rate,allCombinedTable.preorder_unit_rate) as unit_rate,
                    COALESCE(allCombinedTable.store_order_rejected_micro_qty,0) as normal_rejected_micro_qty,
                    COALESCE(allCombinedTable.preorder_rejected_micro_qty,0) as preorder_rejected_micro_qty,
                    COALESCE(allCombinedTable.total_amount,0) as normal_total_amount,
                    COALESCE(allCombinedTable.preorder_total_amount,0) as preorder_total_amount,
                    COALESCE(allCombinedTable.store_order_updated_at,allCombinedTable.preorder_updated_at) as updated_at
                    from (
                            select
                                    *
                            from
                            storeOrderRejectedItemData
                            left Join storePreorderRejectedItemData on storePreorderRejectedItemData.preorder_product_code = storeOrderRejectedItemData.product_code
                            and (storePreorderRejectedItemData.preorder_product_variant_code = storeOrderRejectedItemData.product_variant_code)
                            UNION
                            select
                                    *
                            from
                            storeOrderRejectedItemData
                            Right Join storePreorderRejectedItemData on storePreorderRejectedItemData.preorder_product_code = storeOrderRejectedItemData.product_code
                            and (storePreorderRejectedItemData.preorder_product_variant_code = storeOrderRejectedItemData.product_variant_code)
                    ) as allCombinedTable
                ),

              rejectedItemInStoreNormalOrderAndPreorderFinalReport As (
                              select
                                  rejectedItemInStoreOrderAndPreorderData.product_code,
                                  products_master.product_name,
                                  rejectedItemInStoreOrderAndPreorderData.product_variant_code,
                                  rejectedItemInStoreOrderAndPreorderData.store_code,
                                  stores_detail.store_name,
                                  product_variants.product_variant_name,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.normal_rejected_micro_qty) as total_normal_rejected_qty,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.normal_total_amount) as total_normal_rejected_price,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.preorder_rejected_micro_qty) as total_preorder_rejected_qty,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.preorder_total_amount) as total_preorder_rejected_price,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.normal_rejected_micro_qty + rejectedItemInStoreOrderAndPreorderData.preorder_rejected_micro_qty) as  total_rejected_qty,
                                  SUM(rejectedItemInStoreOrderAndPreorderData.normal_total_amount
                                      + rejectedItemInStoreOrderAndPreorderData.preorder_total_amount) as  total_rejected_price,
                                   rejectedItemInStoreOrderAndPreorderData.updated_at
                              from
                                rejectedItemInStoreOrderAndPreorderData
                                JOIN products_master on products_master.product_code = rejectedItemInStoreOrderAndPreorderData.product_code
                                JOIN stores_detail on stores_detail.store_code = rejectedItemInStoreOrderAndPreorderData.store_code
                                LEFT JOIN product_variants on product_variants.product_variant_code = rejectedItemInStoreOrderAndPreorderData.product_variant_code

                              group by
                                  rejectedItemInStoreOrderAndPreorderData.product_code,
                                  rejectedItemInStoreOrderAndPreorderData.product_variant_code,
                                  rejectedItemInStoreOrderAndPreorderData.store_code
                              )
                  select * from rejectedItemInStoreNormalOrderAndPreorderFinalReport where total_rejected_qty > 0
        ";

        if ($filterParameters['store_code']) {
            $query .= " AND rejectedItemInStoreNormalOrderAndPreorderFinalReport.store_code = '" . $filterParameters['store_code'] . "' ";
        }
        return $query;

    }

    public static function getRejectionDetailStatementOfProduct($filterParameters)
    {
        try {
            $perPage = $filterParameters['perPage'];
            $query = self::getRejectedItemDetailStatementOfProductQuery($filterParameters);
            $totalCount = count(DB::select($query));
            $offset = (($filterParameters['page'] - 1) * $perPage);
            if ($perPage) {
                $query .= ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
            }
            $results = DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
            return $paginator;
        } catch (Exception $exception) {
            throw $exception;
        }

    }

    public static function getRejectedItemDetailStatementOfProductQuery($filterParameters)
    {
        $query = "
                WITH storeOrderRejectedItemData As (
                SELECT
                store_order_rejected_item_report.store_order_updated_at as order_date,
                'normal_order' as order_type,
                store_order_rejected_item_report.store_order_code as order_code,
                store_order_rejected_item_report.product_code,
                store_order_rejected_item_report.product_variant_code,
                store_order_rejected_item_report.unit_rate AS unit_rate,
                ( CASE WHEN store_order_rejected_item_report.package_code IS NOT NULL
                THEN
                    CONCAT(store_order_rejected_item_report.quantity,' ',package_types.package_name)
                ELSE
                    store_order_rejected_item_report.quantity
                END ) as rejected_qty
            FROM
                store_order_rejected_item_report
                join store_orders on store_orders.store_order_code = store_order_rejected_item_report.store_order_code
                LEFT JOIN package_types on package_types.package_code = store_order_rejected_item_report.package_code
            WHERE
                store_order_rejected_item_report.warehouse_code = '" . $filterParameters['warehouse_code'] . "'
                AND store_order_rejected_item_report.product_code = '" . $filterParameters['product_code'] . "' ";

        $query .= " AND store_orders.store_code = '" . $filterParameters['store_code'] . "' ";

        if ($filterParameters['product_variant_code']) {
            $query .= " AND store_order_rejected_item_report.product_variant_code = '" . $filterParameters['product_variant_code'] . "' ";
        }

        $query .= "

                ),

                storePreorderRejectedItemData AS (
                SELECT
                    SPORIR.store_preorder_updated_at as order_date,
                    'preorder' as order_type,
                    SPORIR.store_preorder_code AS order_code,
                    SPORIR.product_code AS product_code,
                    SPORIR.product_variant_code AS product_variant_code,
                    SPORIR.unit_rate,
                   (CASE WHEN SPORIR.package_code IS NOT NULL
                    THEN
                        CONCAT(SPORIR.quantity,' ',package_types.package_name)
                    ELSE
                        SPORIR.quantity
                    END) as rejected_qty
            FROM
                store_pre_order_rejected_item_report SPORIR
                join store_preorder on store_preorder.store_preorder_code = SPORIR.store_preorder_code
                LEFT JOIN package_types on package_types.package_code = SPORIR.package_code
            WHERE
                SPORIR.warehouse_code = '" . $filterParameters['warehouse_code'] . "'
                AND SPORIR.product_code = '" . $filterParameters['product_code'] . "' ";

        $query .= "  AND store_preorder.store_code = '" . $filterParameters['store_code'] . "' ";

        if ($filterParameters['product_variant_code']) {
            $query .= "  AND SPORIR.product_variant_code = '" . $filterParameters['product_variant_code'] . "' ";
        }

        $query .= "

                ),
            rejectedItemRequiredDataForStatement AS (
                SELECT
                 * from storeOrderRejectedItemData
                 union
                 Select * from storePreorderRejectedItemData
                )
                select
                    order_code,
                    order_type,
                    order_date,
                    unit_rate,
                    rejected_qty,
                    rejected_qty * unit_rate as total_amount
                from
                    rejectedItemRequiredDataForStatement
                where rejected_qty > 0 ";

        if ($filterParameters['from_date']) {
            $query .= ' AND rejectedItemRequiredDataForStatement.order_date >= "' . $filterParameters['from_date'] . '" ';
        }
        if ($filterParameters['to_date']) {
            $query .= ' AND rejectedItemRequiredDataForStatement.order_date <= "' . $filterParameters['to_date'] . '" ';
        }
        if ($filterParameters['order_type']) {
            $query .= ' AND rejectedItemRequiredDataForStatement.order_type = "' . $filterParameters['order_type'] . '" ';
        }

        $query .= "ORDER BY rejectedItemRequiredDataForStatement.order_date DESC";


        return $query;
    }

    public static function generateRejectedItemStatementReferenceLink($orderType, $referenceCode)
    {

        $link = null;
        $referenceCodeLinks = [
            'normal_order' => [
                'link' => route('admin.store.orders.show',
                    (isset($referenceCode)) ? $referenceCode : 'SO1000'
                )
            ],
            'preorder' => [
                'link' => route('admin.store.pre-orders.show',
                    (isset($referenceCode)) ? $referenceCode : 'SPO1000'
                )
            ]
        ];
        $link = (isset($referenceCodeLinks[$orderType]['link'])) ? $referenceCodeLinks[$orderType]['link'] : $link;
        return $link;
    }

    public static function getWarehouseRejectedItemReportForsatement($filterParameters)
    {
        try {
            $perPage = $filterParameters['perPage'];
            $query = self::getQueryForRejectedItemForStatement($filterParameters);
            $totalCount = count(DB::select($query));
            $offset = (($filterParameters['page'] - 1) * $perPage);
            if ($perPage) {
                $query .= ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
            }
            $results = DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
            return $paginator;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public static function getQueryForRejectedItemForStatement($filterParameters)
    {
        $query = "
                WITH storeOrderRejectedItemData As (
                SELECT
                store_order_rejected_item_report.store_order_updated_at as order_date,
                'normal_order' as order_type,
                store_order_rejected_item_report.store_order_code as order_code,
                store_order_rejected_item_report.product_code,
                store_order_rejected_item_report.product_variant_code,
                store_order_rejected_item_report.unit_rate AS unit_rate,
                store_orders.store_code,
                CASE WHEN store_order_rejected_item_report.package_code IS NOT NULL
                THEN
                    CONCAT(store_order_rejected_item_report.quantity,' ',package_types.package_name)
                ELSE
                    store_order_rejected_item_report.quantity
                END as rejected_qty,
                 store_order_rejected_item_report.quantity * store_order_rejected_item_report.unit_rate as total_amount
            FROM
                store_order_rejected_item_report
                join store_orders on store_orders.store_order_code = store_order_rejected_item_report.store_order_code
                LEFT JOIN package_types on package_types.package_code = store_order_rejected_item_report.package_code

                ";

        $query .= "

                ),

                storePreorderRejectedItemData AS (
                SELECT
                    SPORIR.store_preorder_updated_at as order_date,
                    'preorder' as order_type,
                    SPORIR.store_preorder_code AS order_code,
                    SPORIR.product_code AS product_code,
                    SPORIR.product_variant_code AS product_variant_code,
                    SPORIR.unit_rate,
                    store_preorder.store_code,
                    CASE WHEN SPORIR.package_code IS NOT NULL
                    THEN
                        CONCAT(SPORIR.quantity,' ',package_types.package_name)
                    ELSE
                        SPORIR.quantity
                    END as rejected_qty,
                    SPORIR.quantity * SPORIR.unit_rate as total_amount

            FROM
                store_pre_order_rejected_item_report SPORIR
                join store_preorder on store_preorder.store_preorder_code = SPORIR.store_preorder_code
                LEFT JOIN package_types on package_types.package_code = SPORIR.package_code
            ";
        if($filterParameters['warehouse_code']){
            $query .= "  where SPORIR.warehouse_code = '" . $filterParameters['warehouse_code'] . "' " ;
        }

        $query .= "
                ),
            rejectedItemRequiredDataForStatement AS (
                SELECT
                 * from storeOrderRejectedItemData
                 union all
                 Select * from storePreorderRejectedItemData
                )
                select
                    rejectedItemRequiredDataForStatement.order_code,
                    rejectedItemRequiredDataForStatement.order_type,
                    rejectedItemRequiredDataForStatement.order_date,
                    rejectedItemRequiredDataForStatement.unit_rate,
                    rejectedItemRequiredDataForStatement.rejected_qty,
                    rejectedItemRequiredDataForStatement.store_code,
                    rejectedItemRequiredDataForStatement.total_amount,
                    stores_detail.store_name,
                    rejectedItemRequiredDataForStatement.product_code,
                    products_master.product_name,
                    product_variants.product_variant_name,
                    rejectedItemRequiredDataForStatement.product_variant_code,
                    products_master.vendor_code,
                    vendors_detail.vendor_name,
                    rejected_qty * unit_rate as total_amount
                from
                    rejectedItemRequiredDataForStatement
                    join stores_detail on stores_detail.store_code = rejectedItemRequiredDataForStatement.store_code
                    join products_master on products_master.product_code = rejectedItemRequiredDataForStatement.product_code
                    join vendors_detail on vendors_detail.vendor_code = products_master.vendor_code
                    left join product_variants on product_variants.product_variant_code = rejectedItemRequiredDataForStatement.product_variant_code

                    where rejectedItemRequiredDataForStatement.order_date IS NOT NULL

                ";

        if($filterParameters['from_date']){
            $query .= ' AND rejectedItemRequiredDataForStatement.order_date >= "'.$filterParameters['from_date'].'" ';
        }
        if($filterParameters['to_date']){
            $query .= ' AND rejectedItemRequiredDataForStatement.order_date <= "'.$filterParameters['to_date'].'" ';
        }
        if($filterParameters['order_type']){
            $query .= ' AND rejectedItemRequiredDataForStatement.order_type = "'.$filterParameters['order_type'].'" ';
        }

        if ($filterParameters['store_code']) {
            $query .= " AND rejectedItemRequiredDataForStatement.store_code = '" . $filterParameters['store_code'] . "' ";
        }

        if (isset($filterParameters['product_name'])) {
            $query .= 'AND products_master.product_name LIKE ' . '"' . '%' . $filterParameters['product_name'] . '%' . '" ';
        }
        if ($filterParameters['product_variant_name']) {
            $query .= 'AND product_variants.product_variant_name LIKE ' . '"' . '%' . $filterParameters['product_variant_name'] . '%' . '" ';
        }

        $query .= "ORDER BY rejectedItemRequiredDataForStatement.order_date DESC";


        return $query;
    }

}







