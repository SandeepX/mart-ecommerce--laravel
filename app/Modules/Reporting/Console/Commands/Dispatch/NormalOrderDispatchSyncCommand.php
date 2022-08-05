<?php

namespace App\Modules\Reporting\Console\Commands\Dispatch;

use App\Modules\Reporting\Models\DispatchReportSyncLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class NormalOrderDispatchSyncCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:nodr-sync';

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
             $currentTime = Carbon::now();

            $dispatchOrderSyncLog = DispatchReportSyncLog::create(
                    ['order_type'=>'normal_order','sync_started_at'=>$currentTime]
                )
                ->fresh();

              $lastStoreOrderDispatchRecords = DB::select("SELECT store_order_updated_at from store_order_dispatch_report_records order by store_order_updated_at DESC limit 1");

              $lastUpdatedDate = NULL;
              if($lastStoreOrderDispatchRecords){
                  $lastUpdatedDate = $lastStoreOrderDispatchRecords[0]->store_order_updated_at;
              }

             $groupedOrderValuesQuery = "SELECT
                 GROUP_CONCAT(distinct(store_order_details.store_order_code) SEPARATOR ',') AS synced_order_summary,
                 count(distinct(store_order_details.store_order_code)) AS total_synced_order";

            $filterOrderValuesQuery =  " FROM `store_order_details`
                join store_orders on store_orders.store_order_code = store_order_details.store_order_code
                 and store_orders.delivery_status = 'dispatched' and store_orders.deleted_at is null
                WHERE
                      store_order_details.`acceptance_status` = 'accepted'
                      and store_order_details.deleted_at is NULL
            ";

            if($lastUpdatedDate){
                $filterOrderValuesQuery .= " AND store_orders.updated_at >= '".$lastUpdatedDate."'  AND store_orders.store_order_code not in (
                     SELECT store_order_code from store_order_dispatch_report_records where store_order_updated_at = '".$lastUpdatedDate."'
               )";
            }

            $filterOrderValuesQuery .= " AND store_orders.updated_at <= '".$currentTime."'
                ORDER BY store_orders.updated_at,store_order_details.created_at ASC;";

            $groupedOrderAndCount = DB::select($groupedOrderValuesQuery.$filterOrderValuesQuery);

              DB::beginTransaction();

             $insertionQueryForLog = "
               INSERT INTO store_order_dispatch_report_records (
                  store_order_detail_code,
                  store_order_code,
                  warehouse_code,
                  product_code,
                  product_variant_code,
                  package_code,
                  product_packaging_history_code,
                  quantity,
                  store_order_updated_at,
                  created_at,
                  updated_at,
                  unit_rate)
                SELECT
                store_order_details.store_order_detail_code,
                store_order_details.store_order_code,
                store_order_details.warehouse_code,
                store_order_details.product_code,
                store_order_details.product_variant_code,
                store_order_details.package_code,
                store_order_details.product_packaging_history_code,
                store_order_details.quantity,
                store_orders.updated_at as store_order_updated_at,
                store_order_details.created_at,
                store_order_details.updated_at,
                CASE WHEN store_order_details.is_taxable_product THEN
                store_order_details.unit_rate + store_order_details.unit_rate * 0.13
                ELSE
                store_order_details.unit_rate
                END as actual_unit_rate
            ";

            DB::statement($insertionQueryForLog.$filterOrderValuesQuery);
            if(count($groupedOrderAndCount)>0){
                $syncedOrders = $groupedOrderAndCount[0]->synced_order_summary;
                $syncedOrdersCount = $groupedOrderAndCount[0]->total_synced_order;
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
