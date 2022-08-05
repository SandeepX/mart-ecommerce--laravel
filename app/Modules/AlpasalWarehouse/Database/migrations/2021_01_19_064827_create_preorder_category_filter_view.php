<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreOrderCategoryFilterView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
          CREATE VIEW preorder_category_filter AS
           SELECT
             wpol.warehouse_preorder_listing_code,
             wpol.warehouse_code,
            pc.category_code,
            ppl.product_code,
            ppl.product_variant_code,
            mrp,
             (Case admin_margin_type when 'p' THEN ((admin_margin_value/100)*mrp) else admin_margin_value END) as AdminValue,
             (Case wholesale_margin_type when 'p' THEN ((wholesale_margin_value/100)*mrp) else wholesale_margin_value END) as WholesaleValue,
             (Case retail_margin_type when 'p' THEN ((retail_margin_value/100)*mrp) else retail_margin_value END) as RetailValue,
             (select mrp-WholesaleValue-RetailValue )as store_pre_order_price
             FROM `warehouse_preorder_products` as ppl
              INNER JOIN products_master on products_master.product_code=ppl.product_code
             INNER join product_category as pc on products_master.product_code=pc.product_code
             INNER join warehouse_preorder_listings wpol on wpol.warehouse_preorder_listing_code=ppl.warehouse_preorder_listing_code
         ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preOrder_category_filters');
    }
}
