<?php
namespace App\Modules\Store\Database\seeds;

use Illuminate\Database\Seeder;

class StoreStorePreOrderDetailViewSeederMarch2022 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement("
          CREATE OR REPLACE VIEW store_pre_order_detail_view AS
SELECT
        `dt`.`id` AS `id`,
        `dt`.`store_preorder_detail_code` AS `store_preorder_detail_code`,
        `dt`.`store_preorder_code` AS `store_preorder_code`,
        `dt`.`warehouse_preorder_product_code` AS `warehouse_preorder_product_code`,
        `dt`.`package_code` AS `package_code`,
        `dt`.`product_packaging_history_code` AS `product_packaging_history_code`,
        `dt`.`quantity` AS `quantity`,
        `dt`.`initial_order_quantity` AS `initial_order_quantity`,
        `dt`.`is_taxable` AS `is_taxable`,
        `dt`.`delivery_status` AS `delivery_status`,
        `dt`.`created_by` AS `created_by`,
        `dt`.`updated_by` AS `updated_by`,
        `dt`.`admin_updated_by` AS `admin_updated_by`,
        `dt`.`admin_updated_at` AS `admin_updated_at`,
        `dt`.`deleted_by` AS `deleted_by`,
        `dt`.`deleted_at` AS `deleted_at`,
        `dt`.`created_at` AS `created_at`,
        `dt`.`updated_at` AS `updated_at`,
        `dt`.`micro_to_unit_value` AS `micro_to_unit_value`,
        `dt`.`micro_unit_code` AS `micro_unit_code`,
        `dt`.`unit_code` AS `unit_code`,
        `dt`.`unit_to_macro_value` AS `unit_to_macro_value`,
        `dt`.`macro_to_super_value` AS `macro_to_super_value`,
        `dt`.`macro_unit_code` AS `macro_unit_code`,
        `dt`.`super_unit_code` AS `super_unit_code`,
        `dt`.`micro_unit_rate` AS `micro_unit_rate`,
        IFNULL((CASE
                    WHEN (`dt`.`micro_unit_code` = `dt`.`package_code`) THEN `dt`.`micro_unit_rate`
                    WHEN (`dt`.`unit_code` = `dt`.`package_code`) THEN (`dt`.`micro_to_unit_value` * `dt`.`micro_unit_rate`)
                    WHEN (`dt`.`macro_unit_code` = `dt`.`package_code`) THEN ((`dt`.`unit_to_macro_value` * `dt`.`micro_to_unit_value`) * `dt`.`micro_unit_rate`)
                    WHEN (`dt`.`super_unit_code` = `dt`.`package_code`) THEN (((`dt`.`macro_to_super_value` * `dt`.`unit_to_macro_value`) * `dt`.`micro_to_unit_value`) * `dt`.`micro_unit_rate`)
                END),
                `dt`.`micro_unit_rate`) AS `unit_rate`
    FROM
        (SELECT
            `spod`.`id` AS `id`,
                `spod`.`store_preorder_detail_code` AS `store_preorder_detail_code`,
                `spod`.`store_preorder_code` AS `store_preorder_code`,
                `spod`.`warehouse_preorder_product_code` AS `warehouse_preorder_product_code`,
                `spod`.`package_code` AS `package_code`,
                `spod`.`product_packaging_history_code` AS `product_packaging_history_code`,
                `spod`.`quantity` AS `quantity`,
                `spod`.`initial_order_quantity` AS `initial_order_quantity`,
                `spod`.`is_taxable` AS `is_taxable`,
                `spod`.`delivery_status` AS `delivery_status`,
                `spod`.`created_by` AS `created_by`,
                `spod`.`updated_by` AS `updated_by`,
                `spod`.`admin_updated_by` AS `admin_updated_by`,
                `spod`.`admin_updated_at` AS `admin_updated_at`,
                `spod`.`deleted_by` AS `deleted_by`,
                `spod`.`deleted_at` AS `deleted_at`,
                `spod`.`created_at` AS `created_at`,
                `spod`.`updated_at` AS `updated_at`,
                `pph`.`micro_to_unit_value` AS `micro_to_unit_value`,
                `pph`.`micro_unit_code` AS `micro_unit_code`,
                `pph`.`unit_code` AS `unit_code`,
                `pph`.`unit_to_macro_value` AS `unit_to_macro_value`,
                `pph`.`macro_to_super_value` AS `macro_to_super_value`,
                `pph`.`macro_unit_code` AS `macro_unit_code`,
                `pph`.`super_unit_code` AS `super_unit_code`,
                (CASE
                    WHEN
                        (`spod`.`is_taxable` = '1')
                    THEN
                        (((`wpp`.`mrp` - (CASE `wpp`.`wholesale_margin_type`
                            WHEN 'p' THEN ((`wpp`.`wholesale_margin_value` / 100) * `wpp`.`mrp`)
                            ELSE `wpp`.`wholesale_margin_value`
                        END)) - (CASE `wpp`.`retail_margin_type`
                            WHEN 'p' THEN ((`wpp`.`retail_margin_value` / 100) * `wpp`.`mrp`)
                            ELSE `wpp`.`retail_margin_value`
                        END)) / 1.13)
                    ELSE ((`wpp`.`mrp` - (CASE `wpp`.`wholesale_margin_type`
                        WHEN 'p' THEN ((`wpp`.`wholesale_margin_value` / 100) * `wpp`.`mrp`)
                        ELSE `wpp`.`wholesale_margin_value`
                    END)) - (CASE `wpp`.`retail_margin_type`
                        WHEN 'p' THEN ((`wpp`.`retail_margin_value` / 100) * `wpp`.`mrp`)
                        ELSE `wpp`.`retail_margin_value`
                    END))
                END) AS `micro_unit_rate`
        FROM
            ((`store_preorder_details` `spod`
        JOIN `warehouse_preorder_products` `wpp` ON (((`wpp`.`warehouse_preorder_product_code` = `spod`.`warehouse_preorder_product_code`)
            AND (`wpp`.`deleted_at` IS NULL))))
        LEFT JOIN `product_packaging_history` `pph` ON ((`pph`.`product_packaging_history_code` = `spod`.`product_packaging_history_code`)))
        WHERE
            (`spod`.`deleted_at` IS NULL)) `dt`
        ");
    }
}
