<?php


namespace App\Modules\Reporting\Helpers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemandProjectionHelper
{

    public static function getWarehouseDemandProjectionReport($filterParameters)
    {
        $filterParameters['reporting_date_from'] = config('ReportingFilterDate.reporting_date_from');
        $filterParameters['reporting_demand_date_from'] = config('ReportingFilterDate.reporting_demand_date_from');

        $query = self::getQueryForDemandProjectionReportOfWarehouse($filterParameters);
        $records['total_number_of_records'] = count(DB::select($query));
        if(!$filterParameters['download_excel']) {
            $query .= $filterParameters['paginate'];
        }

        $demandProjectionData = DB::select($query);
        array_push($demandProjectionData,$records['total_number_of_records']);
        return $demandProjectionData;
    }

    public static function getQueryForDemandProjectionReportOfWarehouse($filterParameters)
    {
        $query = "
       WITH purchaseStock AS (
                   	SELECT
                        	wpm.product_code,
                        	wpm.product_variant_code,
                        	SUM(wps.quantity) as total_purchase_qty
                    	FROM
                    	warehouse_product_stock wps
                    	join warehouse_product_master wpm on wpm.warehouse_product_master_code =                                             	wps.warehouse_product_master_code
                    	where wps.action = 'purchase'
                    	 and wpm.warehouse_code = '".$filterParameters['warehouse_code']."'
                    	  and wps.updated_at >= '".$filterParameters['reporting_date_from']."'
                    	GROUP BY wpm.product_code,wpm.product_variant_code
                 	),
                receivedStock AS (
                            SELECT
                                    wpm.product_code,
                                    wpm.product_variant_code,
                                    SUM(wps.quantity) as total_received_qty
                                FROM
                                warehouse_product_stock wps
                                join warehouse_product_master wpm on wpm.warehouse_product_master_code =                                             	wps.warehouse_product_master_code
                                where wps.action = 'received-stock-transfer'
                                 and wpm.warehouse_code = '".$filterParameters['warehouse_code']."'
                                 and wps.updated_at >= '".$filterParameters['reporting_date_from']."'
                                GROUP BY wpm.product_code,wpm.product_variant_code
                ),
     	        normalOrderDispatched AS (
                     	SELECT
                     	SOD.product_code,
                     	SOD.product_variant_code,
                     	SUM(CASE WHEN SOD.package_code is NOT NULL THEN
                         	product_packaging_to_micro_quantity_function(
                             	SOD.package_code,
                             	SOD.product_packaging_history_code,
                             	SOD.quantity
                         	)
                         	ELSE
                         	SOD.quantity
                        	END) AS total_dispatched_qty
                	FROM
                    	`store_orders`
                    	INNER JOIN store_order_details SOD
                        	ON store_orders.store_order_code = SOD.store_order_code
                        	and SOD.deleted_at IS NULL
                        	 and SOD.acceptance_status = 'accepted'
                        	 and SOD.updated_at >= '".$filterParameters['reporting_date_from']."'
                	WHERE
                    	(store_orders.delivery_status = 'dispatched')
                    	AND
                    	(store_orders.wh_code = 0)
                    	AND
                    	store_orders.updated_at >= '".$filterParameters['reporting_date_from']."'
                 	AND store_orders.deleted_at IS NULL

                	GROUP BY
                	SOD.product_code,
                	SOD.product_variant_code
                ),
            	preOrderDispatch AS (
                	SELECT
                    	WPP.product_code,
                    	WPP.product_variant_code,
                    	SUM(
                        	CASE WHEN SPOD.package_code is NOT NULL THEN
                         	product_packaging_to_micro_quantity_function(
                             	SPOD.package_code,
                             	SPOD.product_packaging_history_code,
                             	SPOD.quantity
                         	)
                         	ELSE
                         	SPOD.quantity
                        	END
                    	) as store_preorder_qty
                	FROM
                    	`store_preorder`
                    	INNER JOIN store_preorder_details SPOD
                        	ON store_preorder.store_preorder_code = SPOD.store_preorder_code
                        	 and	SPOD.deleted_at is NULL
                    	INNER JOIN warehouse_preorder_products WPP
                       	ON WPP.warehouse_preorder_product_code = SPOD.warehouse_preorder_product_code
                       	 and WPP.deleted_at is NULL AND WPP.is_active = 1
                    	INNER JOIN warehouse_preorder_listings WPL
                       	ON WPL.warehouse_preorder_listing_code = WPP.warehouse_preorder_listing_code
                       	  and WPL.deleted_at is NULL
                	WHERE
                    	(store_preorder.status = 'dispatched')
                	and SPOD.delivery_status = 1
                	and store_preorder.updated_at >= '".$filterParameters['reporting_date_from']."'

                    and (WPL.warehouse_code = '".$filterParameters['warehouse_code']."')

                	GROUP BY
                	WPP.product_code,
                	WPP.product_variant_code
            	),
            	stockTransfer AS (
                	SELECT
                        	wpm.product_code,
                        	wpm.product_variant_code,
                        	SUM(wps.quantity) as total_stock_transfer_qty
                    	FROM
                    	warehouse_product_stock wps
                    	join warehouse_product_master wpm on wpm.warehouse_product_master_code =                                             	wps.warehouse_product_master_code
                    	where wps.action = 'stock-transfer'
                    	and wpm.warehouse_code = '".$filterParameters['warehouse_code']."'
                    	and wps.updated_at >= '".$filterParameters['reporting_date_from']."'
                    	GROUP BY wpm.product_code,wpm.product_variant_code
            	),
            	demandNormalOrder AS (
                	SELECT
                    	SOD.product_code,
                    	SOD.product_variant_code,
                    	SUM(
                        	CASE WHEN SOD.package_code is NOT NULL THEN
                         	product_packaging_to_micro_quantity_function(
                             	SOD.package_code,
                             	SOD.product_packaging_history_code,
                             	SOD.quantity
                         	)
                         	ELSE
                         	SOD.quantity
                        	END
                    	) AS normal_order_demand_qty
                	FROM
                    	`store_orders`
                    	INNER JOIN store_order_details SOD
                        	ON store_orders.store_order_code = SOD.store_order_code and SOD.deleted_at is NULL and SOD.acceptance_status = 'accepted'
                	WHERE
                    	(store_orders.delivery_status != 'dispatched' and store_orders.delivery_status != 'cancelled')
                    	and ( store_orders.wh_code = '".$filterParameters['warehouse_code']."')
                    	and store_orders.updated_at >= '".$filterParameters['reporting_demand_date_from']."'

                	GROUP BY
                	SOD.product_code,
                	SOD.product_variant_code
            	),
            	demandPreOrder AS (
                            SELECT
                        WPP.product_code AS demend_preorder_product_code,
                        WPP.product_variant_code AS demand_preorder_product_variant_code,
                        SUM(
                            CASE WHEN SPD.package_code is NOT NULL THEN
                                    product_packaging_to_micro_quantity_function(
                                        SPD.package_code,
                                        SPD.product_packaging_history_code,
                                        SPD.quantity
                                    )
                                    ELSE
                                    SPD.quantity
                                    END
                        ) AS demand_preorder_qty
                    FROM
                        `store_preorder`
                        INNER JOIN store_preorder_details SPD
                            ON store_preorder.store_preorder_code = SPD.store_preorder_code and SPD.deleted_at is NULL
                        INNER JOIN warehouse_preorder_products WPP
                            ON WPP.warehouse_preorder_product_code = SPD.warehouse_preorder_product_code
                            and WPP.deleted_at is NULL AND WPP.is_active = 1
                        INNER JOIN warehouse_preorder_listings WPL
                            ON WPL.warehouse_preorder_listing_code = WPP.warehouse_preorder_listing_code
                            and WPL.deleted_at is NULL
                    WHERE
                        (store_preorder.status != 'dispatched' and store_preorder.status != 'cancelled' and store_preorder.updated_at >= '".$filterParameters['reporting_demand_date_from']."')
                        AND
                        (WPL.warehouse_code = '".$filterParameters['warehouse_code']."')
                            and SPD.delivery_status = 1
                            and store_preorder.updated_at >= '".$filterParameters['reporting_demand_date_from']."'
                    GROUP BY
                    WPP.product_code,
                    WPP.product_variant_code
                ),
      	demandProjection AS (
                	SELECT
                  	wpm.product_code,
                  	wpm.product_variant_code,
                  	purchaseStock.total_purchase_qty,
                  	receivedStock.total_received_qty,
                  	normalOrderDispatched.total_dispatched_qty AS normal_order_dispacthed_qty,
                  	preOrderDispatch.store_preorder_qty AS pre_order_dispatched_qty,
                  	stockTransfer.total_stock_transfer_qty,
                  	demandNormalOrder.normal_order_demand_qty
                	FROM
                    	warehouse_product_master wpm
                   	left join purchaseStock on purchaseStock.product_code = wpm.product_code and (
                        	purchaseStock.product_variant_code = wpm.product_variant_code or (                                             	purchaseStock.product_variant_code is NUll
                           	and wpm.product_variant_code is NULL))
                    	left join receivedStock on receivedStock.product_code = wpm.product_code and (
                        	receivedStock.product_variant_code = wpm.product_variant_code or (                                             	receivedStock.product_variant_code is NUll
                           	and wpm.product_variant_code is NULL))
                   	left join normalOrderDispatched on normalOrderDispatched.product_code = wpm.product_code and (
                        	normalOrderDispatched.product_variant_code = wpm.product_variant_code or (                                             	normalOrderDispatched.product_variant_code is NUll
                           	and wpm.product_variant_code is NULL))
                   	left join preOrderDispatch on preOrderDispatch.product_code = wpm.product_code and (
                        	preOrderDispatch.product_variant_code = wpm.product_variant_code or (                                                 	preOrderDispatch.product_variant_code is NUll
                           	and wpm.product_variant_code is NULL))
                    	left join stockTransfer on stockTransfer.product_code = wpm.product_code and (
                        	stockTransfer.product_variant_code = wpm.product_variant_code or (                                                     	stockTransfer.product_variant_code is NUll
                           	and wpm.product_variant_code is NULL))
                    	left join demandNormalOrder on demandNormalOrder.product_code = wpm.product_code and (
                        	demandNormalOrder.product_variant_code = wpm.product_variant_code or (                                                 	demandNormalOrder.product_variant_code is NUll
                           	and wpm.product_variant_code is NULL))
                  	where wpm.warehouse_code  = '".$filterParameters['warehouse_code']."'
          	),
            demandProjectionRequiredData AS (SELECT
                COALESCE(allCombinedTable.product_code,allCombinedTable.demend_preorder_product_code) as product_code,
                COALESCE(allCombinedTable.product_variant_code,allCombinedTable.demand_preorder_product_variant_code) as product_variant_code,
                allCombinedTable.total_purchase_qty,
                allCombinedTable.total_received_qty,
                allCombinedTable.normal_order_dispacthed_qty,
                allCombinedTable.pre_order_dispatched_qty,
                allCombinedTable.total_stock_transfer_qty,
                allCombinedTable.normal_order_demand_qty,
                allCombinedTable.demand_preorder_qty
                from (select
                        *
                from
                demandProjection
                left Join demandPreOrder on demandPreOrder.demend_preorder_product_code = demandProjection.product_code and 	(demandPreOrder.demand_preorder_product_variant_code = demandProjection.product_variant_code or ( demandPreOrder.demand_preorder_product_variant_code  is null and demandProjection.product_variant_code is null))
                UNION
                select
                        *
                from
                demandProjection
                Right Join demandPreOrder on demandPreOrder.demend_preorder_product_code = demandProjection.product_code             	and (demandPreOrder.demand_preorder_product_variant_code = demandProjection.product_variant_code or             	( demandPreOrder.demand_preorder_product_variant_code  is null and demandProjection.product_variant_code is null)))
                allCombinedTable
            ),
            projectionMainTable  AS (SELECT
                    products_master.product_name,
                    demandProjectionRequiredData.product_code,
                    product_variants.product_variant_name,
                    demandProjectionRequiredData.product_variant_code,
                    products_master.vendor_code,
                    vendors_detail.vendor_name,
                    demandProjectionRequiredData.total_purchase_qty,
                    demandProjectionRequiredData.total_received_qty,
                    demandProjectionRequiredData.normal_order_dispacthed_qty,
                    demandProjectionRequiredData.pre_order_dispatched_qty,
                    demandProjectionRequiredData.total_stock_transfer_qty,
                    demandProjectionRequiredData.normal_order_demand_qty,
                    demandProjectionRequiredData.demand_preorder_qty,
                    ((COALESCE(total_purchase_qty,0) + COALESCE(total_received_qty,0)) -
                    (COALESCE(normal_order_dispacthed_qty,0) + COALESCE(pre_order_dispatched_qty,0) +  COALESCE(total_stock_transfer_qty,0))) as actual_stock,
                    (COALESCE(normal_order_demand_qty,0) +  COALESCE(demand_preorder_qty,0)) as demand_stock
                from
                demandProjectionRequiredData
                join products_master on products_master.product_code = demandProjectionRequiredData.product_code
                join vendors_detail on vendors_detail.vendor_code = products_master.vendor_code
                left join product_variants on product_variants.product_variant_code = demandProjectionRequiredData.product_variant_code)

                SELECT
                projectionMainTable.*,
                (actual_stock- demand_stock) as demand_projection
                from projectionMainTable
           ";

        if(isset($filterParameters['vendor_code'])){
            $vendorsCodes = "'".implode("','",$filterParameters['vendor_code'])."'";

            //dd($vendorsCodes);
            $query .= 'WHERE projectionMainTable.vendor_code IN ('.$vendorsCodes.')';
        }
        if(isset($filterParameters['product_name'])){
            $query .= 'WHERE projectionMainTable.product_name LIKE '.'"'.'%'.$filterParameters['product_name'].'%'.'" ';
        }
        if($filterParameters['product_name'] && $filterParameters['product_variant_name']){
            $query .= 'AND projectionMainTable.product_variant_name LIKE '.'"'.'%'.$filterParameters['product_variant_name'].'%'.'" ';
        }
        if(!$filterParameters['product_name'] && $filterParameters['product_variant_name']){
            $query .= 'WHERE projectionMainTable.product_variant_name LIKE '.'"'.'%'.$filterParameters['product_variant_name'].'%'.'" ';
        }
        $query .=' ORDER BY demand_projection ASC';

        return $query;

    }

}
