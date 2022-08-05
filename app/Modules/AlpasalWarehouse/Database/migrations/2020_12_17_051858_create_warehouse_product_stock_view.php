<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductStockView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
        CREATE VIEW warehouse_product_stock_view AS
           select DISTINCT purchaseStock.warehouse_product_master_code as code,
           purchaseStock.a as purchase,salesStock.b as sales, (IFNULL(purchaseStock.a, 0) -IFNULL(salesStock.b,0) ) as current_stock from warehouse_product_master
           INNER join ( select DISTINCT warehouse_product_master_code,sum(quantity) as a from warehouse_product_stock where action='purchase'
           GROUP by warehouse_product_master_code ) as purchaseStock Left join ( select DISTINCT warehouse_product_master_code,sum(quantity) as b from warehouse_product_stock
            where action='sales' GROUP by warehouse_product_master_code ) as salesStock on salesStock.warehouse_product_master_code=purchaseStock.warehouse_product_master_code

        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_product_stock_view');
    }
}
