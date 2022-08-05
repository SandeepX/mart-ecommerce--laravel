<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisitedWarehouseProductStockView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       \Illuminate\Support\Facades\DB::statement("
         CREATE OR REPLACE VIEW warehouse_product_stock_view AS
WITH stockPurchase AS (
    SELECT
        warehouse_product_master_code,
        SUM(quantity) as purchase
    FROM
        warehouse_product_stock
    WHERE
        action = 'purchase'
        GROUP by warehouse_product_master_code
),
stockSales AS (
    SELECT
        warehouse_product_master_code,
        SUM(quantity) as sales
    FROM
        warehouse_product_stock
    WHERE
        action = 'sales'
        GROUP by warehouse_product_master_code
),
stockPreOrderSales AS (
    SELECT
        warehouse_product_master_code,
        SUM(quantity) as preorder_sales
    FROM
        warehouse_product_stock
    WHERE
        action = 'preorder_sales'
        GROUP by warehouse_product_master_code
),
resultStock AS (
     select
      stockPurchase.warehouse_product_master_code,
      stockPurchase.purchase,
      stockSales.sales,
      stockPreOrderSales.preorder_sales
      from stockPurchase
      left join stockSales
      USING (warehouse_product_master_code)
      left join stockPreOrderSales
      USING (warehouse_product_master_code)
      )
SELECT
    warehouse_product_master_code as code,
    purchase,
    sales,
    preorder_sales,
    (purchase - (COALESCE(sales,0) + COALESCE(preorder_sales,0))) as current_stock
FROM
    resultStock;
       ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sadvh', function (Blueprint $table) {
            //
        });
    }
}
