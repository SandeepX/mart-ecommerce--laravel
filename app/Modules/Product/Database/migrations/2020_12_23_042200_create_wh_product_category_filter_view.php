<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWhProductCategoryFilterView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            \DB::statement("
        CREATE VIEW wh_product_category_filter_view AS
           select * from
(SELECT pc.category_code,warehouse_product_master.warehouse_code,warehouse_product_master.product_code,warehouse_product_master.product_variant_code,mrp,
 (Case admin_margin_type when 'p' THEN ((admin_margin_value/100)*mrp) else admin_margin_value END) as AdminValue,
 (Case wholesale_margin_type when 'p' THEN ((wholesale_margin_value/100)*mrp) else wholesale_margin_value END) as WholesaleValue,
 (Case retail_margin_type when 'p' THEN ((retail_margin_value/100)*mrp) else retail_margin_value END) as RetailValue,
 (select mrp-WholesaleValue-RetailValue )as storePrice
 FROM `warehouse_product_price_master` as ppl
 INNER JOIN warehouse_product_master on warehouse_product_master.warehouse_product_master_code =ppl.warehouse_product_master_code
 INNER join product_category as pc on warehouse_product_master.product_code=pc.product_code) as final_table
        ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("
          DROP VIEW IF EXISTS `wh_product_category_filter_view`
        ");
    }
}
