<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePreorderView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('
        CREATE VIEW store_pre_orders_view AS

        SELECT
            store_preorder.id,
            store_preorder.store_preorder_code,
            store_preorder.warehouse_preorder_listing_code,
            store_preorder.store_code,
            store_preorder.payment_status,
            store_preorder.status,
             ROUND(SUM(
               Case t2.is_taxable when "1" Then
                    (
                       (
                    (
                         t1.mrp
                              -
                              (
                                  CASE t1.wholesale_margin_type when "p"
                                  Then
                                      (t1.wholesale_margin_value/100)*t1.mrp
                                  Else
                                      t1.wholesale_margin_value End
                              )
                              -
                              (
                                  CASE t1.retail_margin_type when "p"
                                  Then
                                      (t1.retail_margin_value/100)*mrp
                                  Else
                                      t1.retail_margin_value End
                              )
                     )/1.13
                ) * t2.quantity + (
                           0.13 * (
                    (
                         t1.mrp
                              -
                              (
                                  CASE t1.wholesale_margin_type when "p"
                                  Then
                                      (t1.wholesale_margin_value/100)*t1.mrp
                                  Else
                                      t1.wholesale_margin_value End
                              )
                              -
                              (
                                  CASE t1.retail_margin_type when "p"
                                  Then
                                      (t1.retail_margin_value/100)*mrp
                                  Else
                                      t1.retail_margin_value End
                              )
                     )/1.13
                ) * t2.quantity
                           )
                    )
                     ELSE
                 (
                     t1.mrp
                  -
                  (
                      CASE t1.wholesale_margin_type when "p"
                      Then
                          (t1.wholesale_margin_value/100)*t1.mrp
                      Else
                          t1.wholesale_margin_value End
                  )
                  -
                  (
                      CASE t1.retail_margin_type when "p"
                      Then
                          (t1.retail_margin_value/100)*mrp
                      Else
                          t1.retail_margin_value End
                  )
         ) * t2.quantity
         END
               ),2) as total_price,
            store_preorder.created_by,
            store_preorder.updated_by,
            store_preorder.deleted_by,
            store_preorder.deleted_at,
            store_preorder.created_at,
            store_preorder.updated_at
            FROM `store_preorder`
            LEFT JOIN store_preorder_details as t2
            on store_preorder.store_preorder_code = t2.store_preorder_code AND t2.deleted_at is null
            LEFT JOIN warehouse_preorder_products as t1
            on t2.warehouse_preorder_product_code = t1.warehouse_preorder_product_code
            Group By store_preorder.store_preorder_code
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_pre_orders_view');

    }
}
