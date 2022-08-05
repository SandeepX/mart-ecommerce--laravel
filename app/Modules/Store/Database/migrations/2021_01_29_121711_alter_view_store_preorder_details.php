<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewStorePreorderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
          CREATE OR REPLACE VIEW store_pre_order_detail_view AS
            SELECT
            store_preorder_details.id,
            store_preorder_details.store_preorder_detail_code,
            store_preorder_details.store_preorder_code,
            store_preorder_details.warehouse_preorder_product_code,
            store_preorder_details.quantity,
            store_preorder_details.initial_order_quantity,
            store_preorder_details.is_taxable,
            store_preorder_details.delivery_status,
            ROUND((CASE when store_preorder_details.is_taxable = "1" THEN
               (mrp-
                  (CASE wholesale_margin_type when "p" THEN (wholesale_margin_value/100)*mrp ELSE wholesale_margin_value End)
                -
                 (CASE retail_margin_type when "p" Then (retail_margin_value/100)*mrp Else retail_margin_value End))/1.13
            ELSE
               (mrp-
                  (CASE wholesale_margin_type when "p" THEN (wholesale_margin_value/100)*mrp ELSE wholesale_margin_value End)
                -
               (CASE retail_margin_type when "p" Then (retail_margin_value/100)*mrp Else retail_margin_value End))
            END),2)
               as unit_rate,
              store_preorder_details.admin_updated_by,
              store_preorder_details.admin_updated_at,
            store_preorder_details.created_by,
            store_preorder_details.updated_by,
            store_preorder_details.deleted_by,
            store_preorder_details.deleted_at,
            store_preorder_details.created_at,
            store_preorder_details.updated_at
            FROM `store_preorder_details`
            LEFT JOIN warehouse_preorder_products
            ON store_preorder_details.warehouse_preorder_product_code = warehouse_preorder_products.warehouse_preorder_product_code
            GROUP BY store_preorder_details.store_preorder_detail_code
       ');
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
