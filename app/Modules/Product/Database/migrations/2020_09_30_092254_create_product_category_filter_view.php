<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoryFilterView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
        CREATE VIEW product_category_price_filter AS
               select (category_code),product_code,product_variant_code,storePrice from (SELECT pc.category_code,ppl.product_code,ppl.product_variant_code,mrp,
    (Case  admin_margin_type when  'p' THEN ((admin_margin_value/100)*mrp) else admin_margin_value END) as AdminValue,
    (Case  wholesale_margin_type when  'p' THEN ((wholesale_margin_value/100)*mrp) else wholesale_margin_value END) as                     WholesaleValue,
    (Case  retail_store_margin_type when  'p' THEN ((retail_store_margin_value/100)*mrp) else retail_store_margin_value END)         as RetailValue,
    (select mrp-WholesaleValue-RetailValue )as storePrice
FROM `product_price_lists` as ppl
INNER JOIN    products_master on products_master.product_code=ppl.product_code
INNER join product_category as pc  on products_master.product_code=pc.product_code
where products_master.deleted_at is NULL
) as final_table
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
        DROP VIEW IF EXISTS `product_category_price_filter`
        ");
    }


}
