<?php

namespace App\Modules\Reporting\Console\Commands\RejectedItem;

use App\Modules\Reporting\Mails\RejectedItemSyncDetailMail;
use App\Modules\Reporting\Models\RejectedItemReportSyncLog;
use App\Modules\User\Jobs\SendWelcomeEmailJob;
use App\Modules\User\Mails\WelcomeEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StoreOrderItemRejectedCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:sori-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Its Sync all the normal order rejected item records to its respective table.';

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
        try {
            $filterOrderValuesQuery ='';
            $filterOrderValuesPartial ='';

            $currentTime = Carbon::now();

            $storeOrderRejectedItemSyncLog = RejectedItemReportSyncLog::create(
                [
                    'order_type' => 'normal_order',
                    'sync_started_at' => $currentTime
                ]
            )
                ->fresh();
            $storeOrderRejectedItemRecords = DB::select("SELECT store_order_updated_at from store_order_rejected_item_report order by store_order_updated_at DESC limit 1");

            $lastUpdatedDate = NULL;
            if ($storeOrderRejectedItemRecords) {
                $lastUpdatedDate = $storeOrderRejectedItemRecords[0]->store_order_updated_at;
            }

            $groupedOrderValuesQuery = "SELECT
                 GROUP_CONCAT(distinct(main_table.store_order_code) SEPARATOR ',') AS synced_order_summary,
                 count(distinct(main_table.store_order_code)) AS total_synced_order
             from (
             ";

            $filterOrderValuesQuery .=
                '  SELECT
                        normal_rejected.store_order_detail_code,
                        normal_rejected.store_order_code,
                        normal_rejected.warehouse_code,
                        normal_rejected.product_code,
                        normal_rejected.product_variant_code,
                        normal_rejected.package_code,
                        normal_rejected.product_packaging_history_code,
                        normal_rejected.rejected_qty,
                        normal_rejected.actual_unit_rate,
                        normal_rejected.remark,
                        normal_rejected.store_order_updated_at,
                        normal_rejected.created_at,
                        normal_rejected.updated_at
                        FROM(
                                SELECT
                                    sod.store_order_detail_code,
                                    so.store_order_code,
                                    sod.warehouse_code,
                                    sod.product_code,
                                    sod.product_variant_code,
                                    sod.package_code,
                                    sod.product_packaging_history_code,
                                    sod.initial_order_quantity,
                                    sod.quantity,
                                    so.delivery_status,
                                    sod.is_accepted,
                                    sod.created_at,
                                    sod.updated_at,
                                    (CASE WHEN sod.is_taxable_product THEN
                                        sod.unit_rate + sod.unit_rate * 0.13
                                      ELSE
                                       sod.unit_rate
                                      END
                                    ) as actual_unit_rate,
                                    (case WHEN (so.delivery_status = "cancelled") THEN
                                            COALESCE(sod.initial_order_quantity,sod.quantity)
                                           WHEN (so.delivery_status = "dispatched" AND sod.acceptance_status = "rejected")THEN 	COALESCE(sod.initial_order_quantity,sod.quantity)
                                           WHEN (so.delivery_status = "dispatched" AND sod.acceptance_status = "accepted" AND (sod.quantity < sod.initial_order_quantity)) THEN
                                              sod.initial_order_quantity-sod.quantity

                                    END ) as rejected_qty,
                                  (CASE
                                       WHEN (so.delivery_status = "cancelled") THEN "Order Cancellation"
                                       WHEN (so.delivery_status = "dispatched" and sod.acceptance_status = "rejected") THEN "Order Dispatched But Item Rejection"
                                       WHEN (so.delivery_status = "dispatched" and sod.acceptance_status = "accepted" AND
                                            (sod.quantity < sod.initial_order_quantity ))  THEN
                                                "Order Dispatched But Partial Qty Rejection"

                                  END ) as remark,
                                   so.updated_at as store_order_updated_at

            ';


            $filterOrderValuesQuery .= '  FROM store_order_details sod
                       JOIN  store_orders so ON so.store_order_code = sod.store_order_code
                    WHERE
                        sod.deleted_at IS NULL and so.deleted_at is null
                        AND so.delivery_status IN ("cancelled" , "dispatched")
                        having rejected_qty > 0
                 ) as normal_rejected

                where normal_rejected.updated_at <= "'.$currentTime.'"';

            if($lastUpdatedDate) {
                $filterOrderValuesQuery .= " AND normal_rejected.updated_at >= '" . $lastUpdatedDate . "' AND normal_rejected.store_order_code NOT IN (
                     SELECT store_order_code from store_order_rejected_item_report where store_order_updated_at = '" . $lastUpdatedDate . "'
                 )";
            }

            $filterOrderValuesQuery .= " ORDER BY normal_rejected.updated_at,normal_rejected.created_at ASC";

            $filterOrderValuesPartial .= " ) as main_table";

            $groupedOrderAndCount = DB::select($groupedOrderValuesQuery.$filterOrderValuesQuery.$filterOrderValuesPartial);

            DB::beginTransaction();

            $insertionQueryForLog = "
                    INSERT INTO store_order_rejected_item_report(
                        store_order_detail_code,
                        store_order_code,
                        warehouse_code,
                        product_code,
                        product_variant_code,
                        package_code,
                        product_packaging_history_code,
                        quantity,
                        unit_rate,
                        remark,
                        store_order_updated_at,
                        created_at,
                        updated_at
                    )
          ";

            DB::statement($insertionQueryForLog.$filterOrderValuesQuery);

            if(count($groupedOrderAndCount)>0){
                $syncedOrders = $groupedOrderAndCount[0]->synced_order_summary;
                $syncedOrdersCount = $groupedOrderAndCount[0]->total_synced_order;
            }

            $storeOrderRejectedItemSyncLog->update(
                [
                    'sync_status' => 'success',
                    'synced_orders' => $syncedOrders,
                    'synced_orders_count' => $syncedOrdersCount,
                    'sync_ended_at' => Carbon::now(),
                    'sync_remarks' => 'Happy Syncing !'
                ]);
            Mail::to('sandeep@gmail.com')
                ->send(new RejectedItemSyncDetailMail($storeOrderRejectedItemSyncLog));
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            $storeOrderRejectedItemSyncLog->update(
                [
                    'sync_status' => 'failed',
                    'sync_remarks' => $exception->getMessage()
                ]);
            Mail::to('sandeep@gmail.com')
                ->send(new RejectedItemSyncDetailMail($storeOrderRejectedItemSyncLog));

            echo $exception->getMessage();
        }
    }

}




















