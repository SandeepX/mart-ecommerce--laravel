<?php


namespace App\Modules\Store\Database\seeds;


use Illuminate\Database\Seeder;

class StorePreOrderViewSeederV3 extends Seeder
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
         WITH storePreOrderInformation AS (
        SELECT
            spo.*

        FROM
            store_preorder spo
           # join store_preorder_details spod
            #on spod.store_preorder_code = spo.store_preorder_code

    ),preOrderPriceInfo AS(
     SELECT
        `store_preorder`.`store_preorder_code` AS `store_preorder_code`,
        ROUND(SUM((CASE `t2`.`is_taxable`
                    WHEN
                        '1'
                    THEN
                      (`t2`.`unit_rate` + (0.13 * `t2`.`unit_rate`))* `t2`.`quantity`
                    ELSE
                      `t2`.`unit_rate` * `t2`.`quantity`
                END)),
                2) AS `total_price`
    FROM
        `store_preorder`
        LEFT JOIN `store_pre_order_detail_view` `t2` ON (
        `store_preorder`.`store_preorder_code` = `t2`.`store_preorder_code`
            AND (`t2`.`deleted_at` IS NULL)
            AND (`t2`.`delivery_status` = 1)
           )
        LEFT JOIN `warehouse_preorder_products` `t1` ON
        (`t2`.`warehouse_preorder_product_code` = `t1`.`warehouse_preorder_product_code`)
    WHERE
        (`t1`.`is_active` = 1)
    GROUP BY `store_preorder`.`store_preorder_code`
    ),

    resultStorePreOrder AS (
       SELECT spo_info.* ,COALESCE(pop_info.total_price,0) as total_price
       from storePreOrderInformation spo_info
       left join preOrderPriceInfo pop_info
       on spo_info.store_preorder_code = pop_info.store_preorder_code
    )

    select * from resultStorePreOrder;
        ");
    }

}
