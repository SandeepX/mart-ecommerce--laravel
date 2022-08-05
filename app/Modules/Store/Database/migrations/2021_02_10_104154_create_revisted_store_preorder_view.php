<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevistedStorePreorderView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("
         CREATE OR REPLACE VIEW store_pre_orders_view AS
         SELECT
    store_preorder.id,
    store_preorder.store_preorder_code,
    store_preorder.warehouse_preorder_listing_code,
    store_preorder.store_code,
    store_preorder.payment_status,
    store_preorder.status,
    ROUND(SUM(CASE t2.is_taxable
                WHEN
                    '1'
                THEN
                    (((t1.mrp - (CASE t1.wholesale_margin_type
                        WHEN 'p' THEN (t1.wholesale_margin_value / 100) * t1.mrp
                        ELSE t1.wholesale_margin_value
                    END) - (CASE t1.retail_margin_type
                        WHEN 'p' THEN (t1.retail_margin_value / 100) * mrp
                        ELSE t1.retail_margin_value
                    END)) / 1.13) * t2.quantity + (0.13 * ((t1.mrp - (CASE t1.wholesale_margin_type
                        WHEN 'p' THEN (t1.wholesale_margin_value / 100) * t1.mrp
                        ELSE t1.wholesale_margin_value
                    END) - (CASE t1.retail_margin_type
                        WHEN 'p' THEN (t1.retail_margin_value / 100) * mrp
                        ELSE t1.retail_margin_value
                    END)) / 1.13) * t2.quantity))
                ELSE (t1.mrp - (CASE t1.wholesale_margin_type
                    WHEN 'p' THEN (t1.wholesale_margin_value / 100) * t1.mrp
                    ELSE t1.wholesale_margin_value
                END) - (CASE t1.retail_margin_type
                    WHEN 'p' THEN (t1.retail_margin_value / 100) * mrp
                    ELSE t1.retail_margin_value
                END)) * t2.quantity
            END),
            2) AS total_price,
    store_preorder.created_by,
    store_preorder.updated_by,
    store_preorder.deleted_by,
    store_preorder.deleted_at,
    store_preorder.created_at,
    store_preorder.updated_at
FROM
    `store_preorder`
        INNER JOIN
    store_preorder_details AS t2 ON store_preorder.store_preorder_code = t2.store_preorder_code
        AND t2.deleted_at IS NULL and t2.delivery_status = 1
        INNER JOIN
    warehouse_preorder_products AS t1 ON t2.warehouse_preorder_product_code = t1.warehouse_preorder_product_code
WHERE
    t1.is_active = 1
GROUP BY store_preorder.store_preorder_code
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
