<?php

namespace App\Modules\Reporting\Console\Commands\Dispatch;

use App\Modules\Reporting\Models\DispatchReportSyncLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\DB;

class PreOrderDispatchSyncCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:podr-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Its Sync all the normal order dispatch records to itd respective table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $filterPartialQuery = '';
            $currentTime = Carbon::now();
            $dispatchOrderSyncLog = DispatchReportSyncLog::create(
                [
                    'order_type'=>'preorder',
                    'sync_started_at'=>$currentTime
                ]
            )
                ->fresh();
            $storePreOrderDispatchRecords = DB::select("SELECT store_preorder_updated_at from store_preorder_dispatch_report_records order by store_preorder_updated_at DESC limit 1");

            $lastUpdatedDate = NULL;
            if($storePreOrderDispatchRecords){
                $lastUpdatedDate = $storePreOrderDispatchRecords[0]->store_preorder_updated_at;
            }

            $groupedPreOrdersPartialQuery = "SELECT
               GROUP_CONCAT(distinct(t1.store_preorder_code) SEPARATOR ',') AS synced_preorder_summary,
                 count(distinct(t1.store_preorder_code)) AS total_synced_preorder
                     from (
                     SELECT
                         store_preorder_details.id,
                         store_preorder_details.store_preorder_detail_code,
                         store_preorder_details.store_preorder_code,
                         store_preorder_details.package_code,
                         store_preorder_details.product_packaging_history_code,
                         store_preorder_details.quantity,
                         store_preorder.updated_at as store_preorder_updated_at,
                         store_preorder_details.created_at,
                         store_preorder_details.updated_at,
                         warehouse_preorder_products.product_code,
                         warehouse_preorder_products.product_variant_code,
                         warehouse_preorder_listings.warehouse_code,
                                (`warehouse_preorder_products`.`mrp` - (CASE                `warehouse_preorder_products`.`wholesale_margin_type`
                                WHEN 'p' THEN ((`warehouse_preorder_products`.`wholesale_margin_value` / 100) * `warehouse_preorder_products`.`mrp`)
                                ELSE `warehouse_preorder_products`.`wholesale_margin_value`
                            END)) - (CASE `warehouse_preorder_products`.`retail_margin_type`
                                WHEN 'p' THEN ((`warehouse_preorder_products`.`retail_margin_value` / 100) * `warehouse_preorder_products`.`mrp`)
                                ELSE `warehouse_preorder_products`.`retail_margin_value`
                            END)  AS `micro_unit_rate`
                FROM
                    store_preorder_details
                        JOIN
                    `warehouse_preorder_products`
                    ON `store_preorder_details`.`warehouse_preorder_product_code` = `warehouse_preorder_products`.`warehouse_preorder_product_code`
                         and (`warehouse_preorder_products`.`is_active` = 1)
                        JOIN warehouse_preorder_listings ON warehouse_preorder_listings.warehouse_preorder_listing_code = warehouse_preorder_products.warehouse_preorder_listing_code
                         JOIN store_preorder on store_preorder.store_preorder_code = store_preorder_details.store_preorder_code
                WHERE
                    store_preorder_details.store_preorder_code IN (SELECT
                            store_preorder_code
                        FROM
                            store_preorder
                        WHERE
                            status = 'dispatched')
                        AND store_preorder_details.delivery_status = 1
                        AND store_preorder_details.deleted_at IS NULL";

            if($lastUpdatedDate){
                $filterPartialQuery = " AND store_preorder.updated_at >= '".$lastUpdatedDate."' AND store_preorder.store_preorder_code NOT IN (
                     SELECT store_preorder_code from store_preorder_dispatch_report_records where store_preorder_updated_at = '".$lastUpdatedDate."'
                 )";
            }

            $filterPartialQuery .= " AND store_preorder.created_at <= '".$currentTime."'
                        ) t1 order by t1.store_preorder_updated_at,t1.created_at ASC";

            $groupedPreOrderAndCount = DB::select($groupedPreOrdersPartialQuery.$filterPartialQuery);

            DB::beginTransaction();

            $query = "
               INSERT INTO store_preorder_dispatch_report_records (
                 store_preorder_detail_code,
                 store_preorder_code,
                 warehouse_code,
                 product_code,
                 product_variant_code,
                 package_code,
                 product_packaging_history_code,
                 quantity,
                 store_preorder_updated_at,
                 created_at,
                 updated_at,
                 unit_rate)
               SELECT
                t1.store_preorder_detail_code,
                t1.store_preorder_code,
                t1.warehouse_code,
                t1.product_code,
                t1.product_variant_code,
                t1.package_code,
                t1.product_packaging_history_code,
                t1.quantity,
                t1.store_preorder_updated_at,
                t1.created_at,
                t1.updated_at,
                (SELECT
                     COALESCE(
                     product_packaging_unit_rate_function
                     (      t1.package_code,
                            t1.product_packaging_history_code,
                            t1.micro_unit_rate
                     ),t1.micro_unit_rate)) as unit_rate
                     from (
                     SELECT
                         store_preorder_details.id,
                         store_preorder_details.store_preorder_detail_code,
                         store_preorder_details.store_preorder_code,
                         store_preorder_details.package_code,
                         store_preorder_details.product_packaging_history_code,
                         store_preorder_details.quantity,
                         store_preorder.updated_at as store_preorder_updated_at,
                         store_preorder_details.created_at,
                         store_preorder_details.updated_at,
                         warehouse_preorder_products.product_code,
                         warehouse_preorder_products.product_variant_code,
                         warehouse_preorder_listings.warehouse_code,
                                (`warehouse_preorder_products`.`mrp` - (CASE  `warehouse_preorder_products`.`wholesale_margin_type`
                                WHEN 'p' THEN ((`warehouse_preorder_products`.`wholesale_margin_value` / 100) * `warehouse_preorder_products`.`mrp`)
                                ELSE `warehouse_preorder_products`.`wholesale_margin_value`
                            END)) - (CASE `warehouse_preorder_products`.`retail_margin_type`
                                WHEN 'p' THEN ((`warehouse_preorder_products`.`retail_margin_value` / 100) * `warehouse_preorder_products`.`mrp`)
                                ELSE `warehouse_preorder_products`.`retail_margin_value`
                            END)  AS `micro_unit_rate`
                FROM
                    store_preorder_details
                        JOIN
                    `warehouse_preorder_products`
                    ON `store_preorder_details`.`warehouse_preorder_product_code` = `warehouse_preorder_products`.`warehouse_preorder_product_code`
                         and (`warehouse_preorder_products`.`is_active` = 1)
                        JOIN warehouse_preorder_listings ON warehouse_preorder_listings.warehouse_preorder_listing_code = warehouse_preorder_products.warehouse_preorder_listing_code
                         JOIN store_preorder on store_preorder.store_preorder_code = store_preorder_details.store_preorder_code
                WHERE
                    store_preorder_details.store_preorder_code IN (SELECT
                            store_preorder_code
                        FROM
                            store_preorder
                        WHERE
                            status = 'dispatched')
                        AND store_preorder_details.delivery_status = 1
                        AND store_preorder_details.deleted_at IS NULL
           ";


            $query = DB::statement($query.$filterPartialQuery);
            if(count($groupedPreOrderAndCount)>0){
                $syncedOrders = $groupedPreOrderAndCount[0]->synced_preorder_summary;
                $syncedOrdersCount = $groupedPreOrderAndCount[0]->total_synced_preorder;
            }

            $dispatchOrderSyncLog->update(
                [
                    'sync_status'=>'success',
                    'synced_orders'=>$syncedOrders,
                    'synced_orders_count'=>$syncedOrdersCount,
                    'sync_ended_at'=>Carbon::now(),
                    'sync_remarks' => 'Happy Syncing !'
                ]);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            $dispatchOrderSyncLog->update(
                [
                    'sync_status'=>'failed',
                    'sync_remarks'=>$exception->getMessage()
                ]);
        }
    }

}
