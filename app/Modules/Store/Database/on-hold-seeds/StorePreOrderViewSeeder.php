<?php
namespace  App\Modules\Store\Database\seeds;

use Illuminate\Database\Seeder;

class StorePreOrderViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement("

        SELECT
    `dt1`.`store_preorder_code` AS `store_preorder_code`,
    ROUND(SUM((CASE
                WHEN
                    (`dt1`.`is_taxable` = '1')
                THEN
                    ROUND((ROUND((`dt1`.`unit_rate` * `dt1`.`quantity`),
                                    2) + (0.13 * ROUND((`dt1`.`unit_rate` * `dt1`.`quantity`),
                                    2))),
                            2)
                ELSE ROUND((`dt1`.`unit_rate` * `dt1`.`quantity`),
                        2)
            END)),
            2) AS `total_price`,
    `dt1`.`warehouse_preorder_listing_code` AS `warehouse_preorder_listing_code`,
    `dt1`.`store_code` AS `store_code`,
    `dt1`.`payment_status` AS `payment_status`,
    `dt1`.`status` AS `status`,
    `dt1`.`created_by` AS `created_by`,
    `dt1`.`updated_by` AS `updated_by`,
    `dt1`.`deleted_by` AS `deleted_by`,
    `dt1`.`deleted_at` AS `deleted_at`,
    `dt1`.`created_at` AS `created_at`,
    `dt1`.`updated_at` AS `updated_at`
FROM
    (SELECT
       `store_preorder`.`id` AS `id`,
           `store_preorder`.`store_preorder_code` AS `store_preorder_code`,
           `store_preorder`.`warehouse_preorder_listing_code` AS `warehouse_preorder_listing_code`,
           `store_preorder`.`store_code` AS `store_code`,
           `store_preorder`.`payment_status` AS `payment_status`,
           `store_preorder`.`status` AS `status`,
            `t2`.`is_taxable` AS `is_taxable`,
            `t2`.`quantity` AS `quantity`,
            (CASE
                WHEN
                    (`t2`.`is_taxable` = '1')
                THEN
                    ROUND(((SELECT
                            ((`t1`.`mrp` - (CASE `t1`.`wholesale_margin_type`
                                    WHEN 'p' THEN ((`t1`.`wholesale_margin_value` / 100) * `t1`.`mrp`)
                                    ELSE `t1`.`wholesale_margin_value`
                                END)) - (CASE `t1`.`retail_margin_type`
                                    WHEN 'p' THEN ((`t1`.`retail_margin_value` / 100) * `t1`.`mrp`)
                                    ELSE `t1`.`retail_margin_value`
                                END))
                        ) / 1.13), 2)
                ELSE ROUND((SELECT
                        ((`t1`.`mrp` - (CASE `t1`.`wholesale_margin_type`
                                WHEN 'p' THEN ((`t1`.`wholesale_margin_value` / 100) * `t1`.`mrp`)
                                ELSE `t1`.`wholesale_margin_value`
                            END)) - (CASE `t1`.`retail_margin_type`
                                WHEN 'p' THEN ((`t1`.`retail_margin_value` / 100) * `t1`.`mrp`)
                                ELSE `t1`.`retail_margin_value`
                            END))
                    ), 2)
            END) AS `unit_rate`,
           `store_preorder`.`created_by` AS `created_by`,
           `store_preorder`.`updated_by` AS `updated_by`,
           `store_preorder`.`deleted_by` AS `deleted_by`,
           `store_preorder`.`deleted_at` AS `deleted_at`,
           `store_preorder`.`created_at` AS `created_at`,
           `store_preorder`.`updated_at` AS `updated_at`
    FROM
        ((`store_preorder`
     JOIN`store_preorder_details` `t2` ON (((`store_preorder`.`store_preorder_code` = `t2`.`store_preorder_code`)
        AND (`t2`.`deleted_at` IS NULL))))
     JOIN`warehouse_preorder_products` `t1` ON ((`t2`.`warehouse_preorder_product_code` = `t1`.`warehouse_preorder_product_code`)))) `dt1`
GROUP BY `dt1`.`store_preorder_code`
        ");
    }
}
