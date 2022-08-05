<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewVendorProductPriceViewRevisted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      \DB::statement("
        CREATE OR REPLACE VIEW vendor_product_price_view as
                select * from
(select
pm.product_code,
ppl.product_variant_code,
pm.vendor_code,
mrp,
admin_margin_type,
wholesale_margin_type,
retail_store_margin_type as retail_margin_type,
(Case admin_margin_type when 'p' THEN ((admin_margin_value/100)*mrp) else admin_margin_value END) as adminMargin,
 (Case wholesale_margin_type when 'p' THEN ((wholesale_margin_value/100)*mrp) else wholesale_margin_value END) as wholesaleMargin,
 (Case retail_store_margin_type when 'p' THEN ((retail_store_margin_value/100)*mrp) else retail_store_margin_value END) as retailMargin,
 (select mrp-(adminMargin+wholesaleMargin+retailMargin)) as vendor_price
from products_master as pm
inner join product_price_lists as ppl on ppl.product_code=pm.product_code
inner join vendors_detail as vd on vd.vendor_code=pm.vendor_code
where pm.deleted_at is NULL and ppl.deleted_at is null) as final_table

      ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_product_price_view', function (Blueprint $table) {
            //
            \DB::statement("
        DROP VIEW IF EXISTS `vendor_product_price_view`
        ");
        });
    }
}
