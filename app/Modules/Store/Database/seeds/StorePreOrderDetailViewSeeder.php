<?php

namespace  App\Modules\Store\Database\seeds;

use Illuminate\Database\Seeder;

class StorePreOrderDetailViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement("
          CREATE OR REPLACE VIEW `store_pre_order_detail_view` AS
     select t1.*,(SELECT  COALESCE(product_packaging_unit_rate_function(t1.package_code,t1.product_packaging_history_code,t1.micro_unit_rate),t1.micro_unit_rate)) as unit_rate  from (SELECT
        `store_preorder_details`.`id` AS `id`,
        `store_preorder_details`.`store_preorder_detail_code` AS `store_preorder_detail_code`,
        `store_preorder_details`.`store_preorder_code` AS `store_preorder_code`,
        `store_preorder_details`.`warehouse_preorder_product_code` AS `warehouse_preorder_product_code`,
        `store_preorder_details`.`package_code` AS `package_code`,
        `store_preorder_details`.`product_packaging_history_code` AS `product_packaging_history_code`,
        `store_preorder_details`.`quantity` AS `quantity`,
        `store_preorder_details`.`initial_order_quantity` AS `initial_order_quantity`,
        `store_preorder_details`.`is_taxable` AS `is_taxable`,
        `store_preorder_details`.`delivery_status` AS `delivery_status`,
        (CASE
                    WHEN
                        (`store_preorder_details`.`is_taxable` = '1')
                    THEN
                        (((`warehouse_preorder_products`.`mrp` - (CASE `warehouse_preorder_products`.`wholesale_margin_type`
                            WHEN 'p' THEN ((`warehouse_preorder_products`.`wholesale_margin_value` / 100) * `warehouse_preorder_products`.`mrp`)
                            ELSE `warehouse_preorder_products`.`wholesale_margin_value`
                        END)) - (CASE `warehouse_preorder_products`.`retail_margin_type`
                            WHEN 'p' THEN ((`warehouse_preorder_products`.`retail_margin_value` / 100) * `warehouse_preorder_products`.`mrp`)
                            ELSE `warehouse_preorder_products`.`retail_margin_value`
                        END)) / 1.13)
                    ELSE ((`warehouse_preorder_products`.`mrp` - (CASE `warehouse_preorder_products`.`wholesale_margin_type`
                        WHEN 'p' THEN ((`warehouse_preorder_products`.`wholesale_margin_value` / 100) * `warehouse_preorder_products`.`mrp`)
                        ELSE `warehouse_preorder_products`.`wholesale_margin_value`
                    END)) - (CASE `warehouse_preorder_products`.`retail_margin_type`
                        WHEN 'p' THEN ((`warehouse_preorder_products`.`retail_margin_value` / 100) * `warehouse_preorder_products`.`mrp`)
                        ELSE `warehouse_preorder_products`.`retail_margin_value`
                    END))
                END)
              AS `micro_unit_rate`,
        `store_preorder_details`.`admin_updated_by` AS `admin_updated_by`,
        `store_preorder_details`.`admin_updated_at` AS `admin_updated_at`,
        `store_preorder_details`.`created_by` AS `created_by`,
        `store_preorder_details`.`updated_by` AS `updated_by`,
        `store_preorder_details`.`deleted_by` AS `deleted_by`,
        `store_preorder_details`.`deleted_at` AS `deleted_at`,
        `store_preorder_details`.`created_at` AS `created_at`,
        `store_preorder_details`.`updated_at` AS `updated_at`
    FROM
        (`store_preorder_details`
         JOIN `warehouse_preorder_products` ON ((`store_preorder_details`.`warehouse_preorder_product_code` = `warehouse_preorder_products`.`warehouse_preorder_product_code`)))
    GROUP BY `store_preorder_details`.`store_preorder_detail_code`) as t1

        ");
    }
}
