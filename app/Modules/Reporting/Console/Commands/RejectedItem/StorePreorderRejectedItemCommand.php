<?php

namespace App\Modules\Reporting\Console\Commands\RejectedItem;

use App\Modules\Reporting\Mails\RejectedItemSyncDetailMail;
use App\Modules\Reporting\Models\RejectedItemReportSyncLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class StorePreorderRejectedItemCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sn:spori-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Its Sync all the preorder order item Rejected records to it respective table.';

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
            $filterPreOrderValuesQuery ='';
            $filterPreOrderValuesQueryOrderValuesPartial ='';

            $currentTime = Carbon::now();

            $storePreOrderRejectedItemSyncLog = RejectedItemReportSyncLog::create(
                [
                    'order_type' => 'preorder',
                    'sync_started_at' => $currentTime
                ]
            )
                ->fresh();
            $storePreOrderRejectedItemRecords = DB::select("SELECT store_preorder_updated_at from store_pre_order_rejected_item_report order by store_preorder_updated_at DESC limit 1");

            $lastUpdatedDate = NULL;
            if ($storePreOrderRejectedItemRecords) {
                $lastUpdatedDate = $storePreOrderRejectedItemRecords[0]->store_preorder_updated_at;
            }

            $groupedPreOrderValuesQuery = "SELECT
                 GROUP_CONCAT(distinct(main_table.store_preorder_code) SEPARATOR ',') AS synced_order_summary,
                 count(distinct(main_table.store_preorder_code)) AS total_synced_order
             from (
             ";

            $filterPreOrderValuesQuery .=
                    '  SELECT
                            preorder_rejected.store_preorder_detail_code,
                            preorder_rejected.store_preorder_code,
                            preorder_rejected.warehouse_code,
                            preorder_rejected.pre_order_product_code,
                            preorder_rejected.pre_order_product_variant_code,
                            preorder_rejected.package_code,
                            preorder_rejected.product_packaging_history_code,
                            preorder_rejected.pre_order_rejected_qty,
                            COALESCE( product_packaging_unit_rate_function(
                                preorder_rejected.package_code,
                                preorder_rejected.product_packaging_history_code,
                                preorder_rejected.micro_unit_rate
                                ),preorder_rejected.micro_unit_rate) as unit_rate,
                            preorder_rejected.remark,
                            preorder_rejected.store_preorder_updated_at,
                            preorder_rejected.created_at,
                            preorder_rejected.updated_at

                    FROM(
                        SELECT
                            spod.store_preorder_detail_code,
                            spod.store_preorder_code,
                            wplc.warehouse_code,
                            wppc.product_code as pre_order_product_code,
                            wppc.product_variant_code as pre_order_product_variant_code,
                            spod.package_code,
                            spod.product_packaging_history_code,
                            spod.quantity,
                            spod.initial_order_quantity,
                            spo.status,
                            spod.created_at,
                            spod.updated_at,
                            (wppc.mrp - (CASE wppc.wholesale_margin_type
                                WHEN "p" THEN ((wppc.wholesale_margin_value/100) * wppc.mrp)
                                ELSE wppc.admin_margin_value
                                END))
                             - (CASE wppc.retail_margin_type
                                WHEN "p" THEN ((wppc.retail_margin_value / 100) * wppc.mrp)
                                ELSE wppc.retail_margin_value
                                END) AS micro_unit_rate,

                            (case when (spo.status = "cancelled")  then
                                        spod.initial_order_quantity
                                  when (spo.status = "dispatched" and spod.delivery_status = 0) then
                                         spod.initial_order_quantity
                                  when (spo.status = "dispatched" and spod.delivery_status = 1 and spod.quantity < spod.initial_order_quantity) then
                                         spod.initial_order_quantity-spod.quantity
                                  END
                            ) as pre_order_rejected_qty,
                       ( CASE
                           WHEN (spo.status = "cancelled") THEN "order cancelled"
                           WHEN (spo.status = "dispatched" and spod.delivery_status = 0) THEN 	"order dispatched item cancelled"
                           WHEN (spo.status = "dispatched" and spod.delivery_status = 1 and (spod.quantity<	spod.initial_order_quantity)) THEN
                                   "partially cancelled"

                  		END ) as remark,
                   		spo.updated_at as store_preorder_updated_at
                   		';

            $filterPreOrderValuesQuery .= "  FROM
                        store_preorder spo
                        JOIN store_preorder_details spod ON spo.store_preorder_code = spod.store_preorder_code
                        JOIN warehouse_preorder_products wppc ON wppc.warehouse_preorder_product_code = spod.warehouse_preorder_product_code
                        JOIN warehouse_preorder_listings wplc ON wplc.warehouse_preorder_listing_code = wppc.warehouse_preorder_listing_code
                    WHERE
                        spo.deleted_at IS NULL
                            AND spod.deleted_at IS NULL
                            AND wplc.deleted_at IS NULL
                            AND spo.status IN ('cancelled' , 'dispatched')
                    HAVING pre_order_rejected_qty > 0) AS preorder_rejected

                where preorder_rejected.updated_at <= '".$currentTime."'";

            if($lastUpdatedDate) {
                $filterPreOrderValuesQuery .= " AND preorder_rejected.updated_at >= '" . $lastUpdatedDate . "' AND preorder_rejected.store_preorder_code NOT IN (
                     SELECT store_preorder_code from store_pre_order_rejected_item_report where store_preorder_updated_at = '" . $lastUpdatedDate . "'
                 )";
            }

            $filterPreOrderValuesQuery .= " ORDER BY preorder_rejected.updated_at,preorder_rejected.created_at ASC";

            $filterPreOrderValuesQueryOrderValuesPartial .= " ) as main_table";

            $groupedPreorderAndCount = DB::select($groupedPreOrderValuesQuery.$filterPreOrderValuesQuery.$filterPreOrderValuesQueryOrderValuesPartial);

//            dd($groupedPreorderAndCount);

            DB::beginTransaction();

            $insertionQueryForLog = "
                     INSERT INTO store_pre_order_rejected_item_report(
                        store_preorder_detail_code,
                        store_preorder_code,
                        warehouse_code,
                        product_code,
                        product_variant_code,
                        package_code,
                        product_packaging_history_code,
                        quantity,
                        unit_rate,
                        remark,
                        store_preorder_updated_at,
                        created_at,
                        updated_at
                    )
          ";

            DB::statement($insertionQueryForLog.$filterPreOrderValuesQuery);

            if(count($groupedPreorderAndCount)>0){
                $syncedOrders = $groupedPreorderAndCount[0]->synced_order_summary;
                $syncedOrdersCount = $groupedPreorderAndCount[0]->total_synced_order;
            }

            $storePreOrderRejectedItemSyncLog->update(
                [
                    'sync_status' => 'success',
                    'synced_orders' => $syncedOrders,
                    'synced_orders_count' => $syncedOrdersCount,
                    'sync_ended_at' => Carbon::now(),
                    'sync_remarks' => 'Happy Syncing !'
                ]);
            Mail::to('sandeep@gmail.com')
                ->send(new RejectedItemSyncDetailMail($storePreOrderRejectedItemSyncLog));
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            $storePreOrderRejectedItemSyncLog->update(
                [
                    'sync_status' => 'failed',
                    'sync_remarks' => $exception->getMessage()
                ]);
            Mail::to('sandeep@gmail.com')
                ->send(new RejectedItemSyncDetailMail($storePreOrderRejectedItemSyncLog));
            echo $exception->getMessage();
        }

    }

}



