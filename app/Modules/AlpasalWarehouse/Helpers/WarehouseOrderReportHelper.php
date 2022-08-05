<?php

namespace App\Modules\AlpasalWarehouse\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class WarehouseOrderReportHelper
{

    public static function  getNewWarehouseDispatchOrderReport($filterParameters){
        try{

           // dd($filterParameters);
            $perPage = $filterParameters['perPage'];
            $query = self::getNewQueryForWarehouseDispatchOrderReport($filterParameters);
            $totalCount = count(DB::select($query));
            $offset = (($filterParameters['page'] - 1) * $perPage);
            if($perPage && !$filterParameters['download_excel']){
                $query .=  ' LIMIT '.$perPage. ' OFFSET '.$offset;
            }
            $results =  DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
            //dd($paginator);
            return $paginator;
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public static function getNewQueryForWarehouseDispatchOrderReport($filterParameters){

        $query = "WITH storeOrderDispatchedRecords AS (SELECT
            sodrr.product_code,
            sodrr.product_variant_code,
            SUM(CASE WHEN sodrr.package_code IS NOT NULL THEN
              product_packaging_to_micro_quantity_function(
                  sodrr.package_code,
                  sodrr.product_packaging_history_code,
                  sodrr.quantity
              )
              ELSE sodrr.quantity
              END) as normal_order_micro_quantity,
              SUM(sodrr.quantity * sodrr.unit_rate) as normal_order_amount,
              latestDispatchOrder.store_order_updated_at
            from
            store_order_dispatch_report_records sodrr
            Join store_orders on store_orders.store_order_code = sodrr.store_order_code
            INNER JOIN (
               SELECT
               product_code,
               product_variant_code,
               store_order_updated_at
               from
               store_order_dispatch_report_records
               where id  IN ( SELECT max(id) from store_order_dispatch_report_records
               where store_order_dispatch_report_records.warehouse_code = '".$filterParameters['warehouse_code']."'
               GROUP BY product_code,product_variant_code
               )
            ) as latestDispatchOrder on latestDispatchOrder.product_code = sodrr.product_code and (
            latestDispatchOrder.product_variant_code = sodrr.product_variant_code or (latestDispatchOrder.product_variant_code IS NULL and  sodrr.product_variant_code IS NULL))
            where sodrr.warehouse_code = '".$filterParameters['warehouse_code']."'";

            if($filterParameters['store_code']){
              $query .= " AND store_orders.store_code = '".$filterParameters['store_code']."'";
            }

            if($filterParameters['product_code']){
                $productVariant = explode('-',$filterParameters['product_code']);
                $query .= ' AND sodrr.product_code = "'.$productVariant[0].'" ';
                if(isset($productVariant[1]) && $productVariant[1]){
                    $query .= ' AND sodrr.product_variant_code = "'.$productVariant[1].'" ';
                }
            }

            if($filterParameters['from_date']){
                $query .= ' AND sodrr.store_order_updated_at >= "'.$filterParameters['from_date'].'" ';
            }

            if($filterParameters['to_date']){
                $query .= ' AND sodrr.store_order_updated_at <= "'.$filterParameters['to_date'].'" ';
            }

         $query .= "GROUP BY sodrr.product_code,sodrr.product_variant_code
            ),
            storePreOrderDispatchRecords AS (
                SELECT
                 spdrr.product_code as preorder_product_code,
                 spdrr.product_variant_code as preorder_product_variant_code,
                 SUM(CASE WHEN spdrr.package_code IS NOT NULL THEN
              product_packaging_to_micro_quantity_function(
                  spdrr.package_code,
                  spdrr.product_packaging_history_code,
                  spdrr.quantity
              )
              ELSE spdrr.quantity
              END) as pre_order_micro_quantity,
              SUM(spdrr.quantity * spdrr.unit_rate) as pre_order_amount,
              latestDispatchPreOrder.store_preorder_updated_at
                from
                store_preorder_dispatch_report_records spdrr
                JOIN store_preorder on store_preorder.store_preorder_code = spdrr.store_preorder_code
                INNER JOIN (
                  SELECT
                  product_code,
                  product_variant_code,
                  store_preorder_updated_at
                  from
                  store_preorder_dispatch_report_records
                  where id  IN (SELECT max(id) from store_preorder_dispatch_report_records
                  where store_preorder_dispatch_report_records.warehouse_code = '".$filterParameters['warehouse_code']."'
                  GROUP BY product_code,product_variant_code
               )
            ) as latestDispatchPreOrder on latestDispatchPreOrder.product_code = spdrr.product_code and (
            latestDispatchPreOrder.product_variant_code = spdrr.product_variant_code or (latestDispatchPreOrder.product_variant_code IS NULL and  spdrr.product_variant_code IS NULL))
                where spdrr.warehouse_code = '".$filterParameters['warehouse_code']."' ";

            if($filterParameters['store_code']){
              $query .= " AND store_preorder.store_code = '".$filterParameters['store_code']."'";
            }

            if($filterParameters['product_code']){
                $productVariant = explode('-',$filterParameters['product_code']);
                $query .= ' AND spdrr.product_code = "'.$productVariant[0].'" ';
                if(isset($productVariant[1]) && $productVariant[1]){
                    $query .= ' AND spdrr.product_variant_code = "'.$productVariant[1].'" ';
                }
            }

            if($filterParameters['from_date']){
                $query .= ' AND spdrr.store_preorder_updated_at >= "'.$filterParameters['from_date'].'" ';
            }

            if($filterParameters['to_date']){
                $query .= ' AND spdrr.store_preorder_updated_at <= "'.$filterParameters['to_date'].'" ';
            }

         $query .=   "GROUP BY spdrr.product_code,spdrr.product_variant_code
            ),
            normalOrderandPreOrderCombinedData AS (
                SELECT
                COALESCE(allCombinedData.product_code,allCombinedData.preorder_product_code) as product_code,
                COALESCE(allCombinedData.product_variant_code, allCombinedData.preorder_product_variant_code) as product_variant_code,
                 allCombinedData.normal_order_micro_quantity,
                 allCombinedData.pre_order_micro_quantity,
                 allCombinedData.normal_order_amount,
                 allCombinedData.pre_order_amount,
                  (COALESCE(allCombinedData.normal_order_amount,0) + COALESCE(allCombinedData.pre_order_amount,0)) as total_amount,
                  GREATEST(COALESCE(allCombinedData.store_order_updated_at,0),COALESCE(allCombinedData.store_preorder_updated_at,0)) as latest_updated_at
                from  (select * from storeOrderDispatchedRecords left join storePreOrderDispatchRecords on 							storePreOrderDispatchRecords.preorder_product_code = storeOrderDispatchedRecords.product_code and (storePreOrderDispatchRecords.preorder_product_variant_code = storeOrderDispatchedRecords.product_variant_code or(
               storePreOrderDispatchRecords.preorder_product_variant_code is NULL and  storeOrderDispatchedRecords.product_variant_code is NULL))
                UNION
                  select * from storeOrderDispatchedRecords RIGHT join storePreOrderDispatchRecords on 							storePreOrderDispatchRecords.preorder_product_code = storeOrderDispatchedRecords.product_code and (storePreOrderDispatchRecords.preorder_product_variant_code = storeOrderDispatchedRecords.product_variant_code or(
               storePreOrderDispatchRecords.preorder_product_variant_code is NULL and  storeOrderDispatchedRecords.product_variant_code is NULL))) allCombinedData
            )
            SELECT
             products_master.product_name,
             finalTable.product_code,
             product_variants.product_variant_name,
             finalTable.product_variant_code,
             vendors_detail.vendor_name,
             vendors_detail.vendor_code,
             finalTable.normal_order_micro_quantity,
             finalTable.pre_order_micro_quantity,
             finalTable.normal_order_amount,
             finalTable.pre_order_amount,
             finalTable.total_amount,
             finalTable.latest_updated_at
            from
            normalOrderandPreOrderCombinedData finalTable
            join products_master on products_master.product_code = finalTable.product_code
            left join product_variants on product_variants.product_variant_code = finalTable.product_variant_code
            join vendors_detail on vendors_detail.vendor_code = products_master.vendor_code";
            if($filterParameters['vendor_code']){
                $vendorsCodes = "'".implode("','",$filterParameters['vendor_code'])."'";
                $query .=  "  AND  vendors_detail.vendor_code in (".$vendorsCodes.") ";
            }
        $query .= " ORDER BY finalTable.latest_updated_at DESC ";

       return $query;
    }

    public static function getStoreWiseDispatchReportOfWarehouse($filterParameters){
        try{
            $perPage = $filterParameters['perPage'];

            $query = self::getStoreWiseDispatchProductQuantityQuery($filterParameters);
            $totalCount = count(DB::select($query));

            $offset = (($filterParameters['page'] - 1) * $perPage);

            if($perPage && !$filterParameters['download_excel']){
                $query .=  ' LIMIT '.$perPage. ' OFFSET '.$offset;
            }
            $results =  DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
             //dd($paginator);
            return $paginator;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public static function getStoreWiseDispatchProductQuantityQuery($filterParameters){

         $query = "WITH storeOrderDispatchedRecords AS (SELECT
            store_orders.store_code,
            SUM(CASE WHEN sodrr.package_code IS NOT NULL THEN
              product_packaging_to_micro_quantity_function(
                  sodrr.package_code,
                  sodrr.product_packaging_history_code,
                  sodrr.quantity
              )
              ELSE sodrr.quantity
              END) as normal_order_micro_quantity,
               SUM(sodrr.quantity * sodrr.unit_rate) as normal_order_amount
            from
            store_order_dispatch_report_records sodrr
            Join store_orders on store_orders.store_order_code = sodrr.store_order_code
            where sodrr.warehouse_code = '".$filterParameters['warehouse_code']."'
            and sodrr.product_code = '".$filterParameters['product_code']."' ";

           if($filterParameters['product_variant_code']){
                 $query .= " and sodrr.product_variant_code = '".$filterParameters['product_variant_code']."' ";
           }
            if($filterParameters['from_date']){
                $query .= ' AND sodrr.store_order_updated_at >= "'.$filterParameters['from_date'].'" ';
            }

            if($filterParameters['to_date']){
                $query .= ' AND sodrr.store_order_updated_at <= "'.$filterParameters['to_date'].'" ';
            }

         $query .= "  GROUP BY store_orders.store_code
            ),
            storePreOrderDispatchRecords AS (
                SELECT
                 store_preorder.store_code as pre_order_store_code,
                 SUM(CASE WHEN spdrr.package_code IS NOT NULL THEN
              product_packaging_to_micro_quantity_function(
                  spdrr.package_code,
                  spdrr.product_packaging_history_code,
                  spdrr.quantity
              )
              ELSE spdrr.quantity
              END) as pre_order_micro_quantity,
               SUM(spdrr.quantity * spdrr.unit_rate) as pre_order_amount
                from
                store_preorder_dispatch_report_records spdrr
                JOIN store_preorder on store_preorder.store_preorder_code = spdrr.store_preorder_code
                where spdrr.warehouse_code = '".$filterParameters['warehouse_code']."'
                and spdrr.product_code = '".$filterParameters['product_code']."' ";

            if($filterParameters['product_variant_code']){
               $query .=  " and spdrr.product_variant_code = '".$filterParameters['product_variant_code']."' ";
            }

            if($filterParameters['from_date']){
                $query .= ' AND spdrr.store_preorder_updated_at >= "'.$filterParameters['from_date'].'" ';
            }

            if($filterParameters['to_date']){
                $query .= ' AND spdrr.store_preorder_updated_at <= "'.$filterParameters['to_date'].'" ';
            }

          $query .=  " GROUP BY store_preorder.store_code
            ),
            normalOrderandPreOrderCombinedData AS (
                SELECT
                COALESCE(allCombinedData.store_code,allCombinedData.pre_order_store_code) as store_code,
                 allCombinedData.normal_order_micro_quantity,
                 allCombinedData.pre_order_micro_quantity,
                 allCombinedData.normal_order_amount,
                 allCombinedData.pre_order_amount,
                  (COALESCE(allCombinedData.normal_order_amount,0) + COALESCE(allCombinedData.pre_order_amount,0)) as total_amount
                from  (select * from storeOrderDispatchedRecords left join storePreOrderDispatchRecords on
                storePreOrderDispatchRecords.pre_order_store_code = storeOrderDispatchedRecords.store_code
                UNION
                  select * from storeOrderDispatchedRecords RIGHT join storePreOrderDispatchRecords on
                  storePreOrderDispatchRecords.pre_order_store_code = storeOrderDispatchedRecords.store_code  ) allCombinedData
            )
            SELECT
             stores_detail.store_name,
             finalTable.store_code,
             finalTable.normal_order_micro_quantity,
             finalTable.pre_order_micro_quantity,
             finalTable.normal_order_amount,
             finalTable.pre_order_amount,
             finalTable.total_amount
            from
            normalOrderandPreOrderCombinedData finalTable
            JOIN stores_detail on stores_detail.store_code = finalTable.store_Code";
            if($filterParameters['store_code']){
                  $query .=  "  AND  stores_detail.store_code = '".$filterParameters['store_code']."'";
            }

      return $query;
    }


    public static function getDispatchStatementOfProduct($filterParameters){
        try{
            $perPage = $filterParameters['perPage'];
            $query = self::getDispatchStatementOfProductQuery($filterParameters);
            $totalCount = count(DB::select($query));
            $offset = (($filterParameters['page'] - 1) * $perPage);
            if($perPage){
                $query .=  ' LIMIT '.$perPage. ' OFFSET '.$offset;
            }
            $results =  DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
            return $paginator;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public static function getDispatchStatementOfProductQuery($filterParameters){

        $query = "WITH storeOrderDispatchedRecords AS (SELECT
            sodrr.store_order_updated_at as order_date,
            'normal_order' as order_type,
            sodrr.store_order_code as order_code,
            CASE WHEN sodrr.package_code IS NOT NULL
            THEN
            CONCAT(sodrr.quantity,' ',package_types.package_name)
            ELSE
            sodrr.quantity END as 'package_quantity',
            sodrr.unit_rate,
            sodrr.quantity * sodrr.unit_rate as order_amount
            from
            store_order_dispatch_report_records sodrr
            JOIN store_orders on store_orders.store_order_code = sodrr.store_order_code
            LEFT JOIN package_types on package_types.package_code = sodrr.package_code
            where sodrr.warehouse_code = '".$filterParameters['warehouse_code']."'
            and sodrr.product_code = '".$filterParameters['product_code']."' ";
        if($filterParameters['product_variant_code']){
            $query .= " and sodrr.product_variant_code = '".$filterParameters['product_variant_code']."' ";
        }
         $query .= " and store_orders.store_code = '".$filterParameters['store_code']."'
            ),
            storePreOrderDispatchRecords AS (
                SELECT
                  spdrr.store_preorder_updated_at as order_date,
                 'preorder' as order_type,
                 spdrr.store_preorder_code as order_code,
                CASE WHEN spdrr.package_code IS NOT NULL
                THEN
                CONCAT(spdrr.quantity,' ',package_types.package_name)
                ELSE
                spdrr.quantity END as 'package_quantity',
                spdrr.unit_rate,
                spdrr.quantity * spdrr.unit_rate as order_amount
                from
                store_preorder_dispatch_report_records spdrr
                JOIN store_preorder on store_preorder.store_preorder_code = spdrr.store_preorder_code
                LEFT JOIN package_types on package_types.package_code = spdrr.package_code
                where spdrr.warehouse_code = '".$filterParameters['warehouse_code']."'
                and spdrr.product_code = '".$filterParameters['product_code']."'  ";
            if($filterParameters['product_variant_code']){
                $query .=  " and spdrr.product_variant_code = '".$filterParameters['product_variant_code']."' ";
            }
             $query .=  "and  store_preorder.store_code = '".$filterParameters['store_code']."'
            ),
            allPreOrderandNormalOrderData AS (
            SELECT
             * from storeOrderDispatchedRecords
             union
             Select * from storePreOrderDispatchRecords
            )
            select * from allPreOrderandNormalOrderData
            where order_date IS NOT NULL ";
        if($filterParameters['from_date']){
            $query .= ' AND allPreOrderandNormalOrderData.order_date >= "'.$filterParameters['from_date'].'" ';
        }

        if($filterParameters['to_date']){
            $query .= ' AND allPreOrderandNormalOrderData.order_date <= "'.$filterParameters['to_date'].'" ';
        }
        if($filterParameters['order_type']){
            $query .= ' AND allPreOrderandNormalOrderData.order_type = "'.$filterParameters['order_type'].'" ';
        }
           $query .= "ORDER BY allPreOrderandNormalOrderData.order_date DESC";

        return $query;
    }

    public static function generateDispatchStatementReferenceLink($orderType,$referenceCode){

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

    public static function getLatestDispatchStatementOfWarehouse($filterParameters){
        try{
            $perPage = $filterParameters['perPage'];
            $query = self::getLatestDispatchStatementQuery($filterParameters);
            $totalCount = count(DB::select($query));
            $offset = (($filterParameters['page'] - 1) * $perPage);
            if($perPage && !$filterParameters['download_excel']){
                $query .=  ' LIMIT '.$perPage. ' OFFSET '.$offset;
            }
            $results =  DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
            return $paginator;
        }catch (Exception $exception){
          throw $exception;
        }
    }

    public static function getLatestDispatchStatementQuery($filterParameters){

        $productCode = NULL;
        $productVariantCode = NULL;
        if($filterParameters['product_code']){
            $productVariant = explode('-',$filterParameters['product_code']);
            $productCode = $productVariant[0];
            if(isset($productVariant[1]) && $productVariant[1]){
                $productVariantCode = $productVariant[1];
            }
        }

        $query = "WITH storeOrderDispatchedRecords AS (SELECT
            store_orders.store_code,
            sodrr.store_order_updated_at as order_date,
            sodrr.product_code,
            sodrr.product_variant_code,
            'normal_order' as order_type,
            sodrr.store_order_code as order_code,
            CASE WHEN sodrr.package_code IS NOT NULL
            THEN
            CONCAT(sodrr.quantity,' ',package_types.package_name)
            ELSE
            sodrr.quantity END as 'package_quantity',
            sodrr.unit_rate,
            sodrr.quantity * sodrr.unit_rate as order_amount
            from
            store_order_dispatch_report_records sodrr
            JOIN store_orders on store_orders.store_order_code = sodrr.store_order_code
            LEFT JOIN package_types on package_types.package_code = sodrr.package_code
            where sodrr.warehouse_code = '".$filterParameters['warehouse_code']."' ";

            if($productCode){
                $query .= " and sodrr.product_code = '".$productCode."' ";
            }
            if($productVariantCode){
                $query .= " and sodrr.product_variant_code = '".$productVariantCode."' ";
            }
            if($filterParameters['store_code']){
                $query .= " and store_orders.store_code = '".$filterParameters['store_code']."' ";
            }
             $query .=  "),
                storePreOrderDispatchRecords AS (
                    SELECT
                    store_preorder.store_code,
                    spdrr.store_preorder_updated_at as order_date,
                    spdrr.product_code,
                    spdrr.product_variant_code,
                   'preorder' as pre_order,
                   spdrr.store_preorder_code as order_code,
                   CASE WHEN spdrr.package_code IS NOT NULL
                    THEN
                    CONCAT(spdrr.quantity,' ',package_types.package_name)
                    ELSE
                    spdrr.quantity END as 'package_quantity',
                    spdrr.unit_rate,
                    spdrr.quantity * spdrr.unit_rate as order_amount
                    from
                    store_preorder_dispatch_report_records spdrr
                    JOIN store_preorder on store_preorder.store_preorder_code = spdrr.store_preorder_code
                    LEFT JOIN package_types on package_types.package_code = spdrr.package_code
                    where spdrr.warehouse_code = '".$filterParameters['warehouse_code']."' ";

                    if($productCode){
                        $query .=  " and spdrr.product_code = '".$productCode."'  ";
                    }
                    if($productVariantCode){
                        $query .=  " and spdrr.product_variant_code = '".$productVariantCode."' ";
                    }
                    if($filterParameters['store_code']){
                        $query .=" and  store_preorder.store_code = '".$filterParameters['store_code']."' ";
                    }
                $query .="),
                    allPreOrderAndNormalOrderData AS (
                    SELECT
                     * from storeOrderDispatchedRecords
                     union
                     Select * from storePreOrderDispatchRecords
                    )
                    select
                       allPreOrderAndNormalOrderData.*,
                       stores_detail.store_name,
                       products_master.product_name,
                       product_variants.product_variant_name,
                       vendors_detail.vendor_name
                     from allPreOrderAndNormalOrderData
                    JOIN stores_detail on stores_detail.store_code = allPreOrderAndNormalOrderData.store_code
                    JOIN products_master on products_master.product_code = allPreOrderAndNormalOrderData.product_code
                    LEFT JOIN product_variants on product_variants.product_variant_code = allPreOrderAndNormalOrderData.product_variant_code
                    JOIN vendors_detail on vendors_detail.vendor_code = products_master.vendor_code
                    where order_date IS NOT NULL ";

                if($filterParameters['vendor_code']){
                    $vendorsCodes = "'".implode("','",$filterParameters['vendor_code'])."'";
                    $query .= " AND vendors_detail.vendor_code  in (".$vendorsCodes.") ";
                }
                if($filterParameters['from_date']){
                    $query .= ' AND allPreOrderAndNormalOrderData.order_date >= "'.$filterParameters['from_date'].'" ';
                }
                if($filterParameters['to_date']){
                    $query .= ' AND allPreOrderAndNormalOrderData.order_date <= "'.$filterParameters['to_date'].'" ';
                }
                $query .= " ORDER BY allPreOrderAndNormalOrderData.order_date DESC ";

        return $query;
    }

}
