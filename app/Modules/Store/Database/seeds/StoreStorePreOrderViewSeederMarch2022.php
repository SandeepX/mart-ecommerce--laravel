<?php
namespace App\Modules\Store\Database\seeds;

use Illuminate\Database\Seeder;

class StoreStorePreOrderViewSeederMarch2022 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement("
          CREATE OR REPLACE VIEW store_pre_orders_view AS
        SELECT
        `spo`.`id` AS `id`,
        `spo`.`store_preorder_code` AS `store_preorder_code`,
        `spo`.`warehouse_preorder_listing_code` AS `warehouse_preorder_listing_code`,
        `spo`.`store_code` AS `store_code`,
        `spo`.`payment_status` AS `payment_status`,
        `spo`.`status` AS `status`,
        `spo`.`has_merged` AS `has_merged`,
        `spo`.`early_finalized` AS `early_finalized`,
        `spo`.`early_cancelled` AS `early_cancelled`,
        `spo`.`created_by` AS `created_by`,
        `spo`.`updated_by` AS `updated_by`,
        `spo`.`deleted_by` AS `deleted_by`,
        `spo`.`deleted_at` AS `deleted_at`,
        `spo`.`created_at` AS `created_at`,
        `spo`.`updated_at` AS `updated_at`,
        ROUND(SUM((CASE `spodv`.`is_taxable`
                    WHEN '1' THEN ((`spodv`.`unit_rate` + (0.13 * `spodv`.`unit_rate`)) * `spodv`.`quantity`)
                    ELSE (`spodv`.`unit_rate` * `spodv`.`quantity`)
                END)),
                2) AS `total_price`
    FROM
        (`store_preorder` `spo`
        JOIN `store_pre_order_detail_view` `spodv` ON (((`spo`.`store_preorder_code` = `spodv`.`store_preorder_code`)
            AND (`spodv`.`deleted_at` IS NULL)
            AND (`spodv`.`delivery_status` = 1))))
    WHERE
        (`spo`.`deleted_at` IS NULL)
    GROUP BY `spo`.`store_preorder_code`
        ");
    }
}
