<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreOrderProductFromWarehouseStoredProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        $procedure = "
            CREATE PROCEDURE `preorderProductsFromWarehouse`(warehouseCode varchar(255), warehousePreorderListingCode varchar(255),Created_by varchar(255))
            BEGIN
                DECLARE done BOOLEAN DEFAULT 0;
                DECLARE isActive BOOLEAN DEFAULT 0;
                DECLARE maxID int default 0;
                DECLARE ids int default 0;
                DECLARE hasValue int default 0;
                DECLARE max_warehouse_PP_code varchar(100);
                DECLARE  mrp_var,admin_margin_value_var,wholesale_margin_value_var,retail_margin_value_var double;
                DECLARE product_code_var,product_variant_code_var,admin_margin_type_var,wholesale_margin_type_var,retail_margin_type_var,warehouseppc varchar(100);

                 DECLARE product_cursor CURSOR FOR
                    SELECT
                        wpm.product_code,
                        wpm.product_variant_code,
                        wppm.mrp,
                        wppm.admin_margin_type,
                        wppm.admin_margin_value,
                        wppm.wholesale_margin_type,
                        wppm.wholesale_margin_value,
                        wppm.retail_margin_type,
                        wppm.retail_margin_value,
                        wpm.is_active
                    FROM warehouse_product_master as wpm
                    Inner join warehouse_product_price_master as wppm
                    on wppm.warehouse_product_master_code =wpm.warehouse_product_master_code
                    WHERE warehouse_code collate utf8mb4_unicode_ci = warehouseCode;

                    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done=1;

                    SELECT  warehouse_preorder_product_code into max_warehouse_PP_code from warehouse_preorder_products
                    where id=(select max(id) from warehouse_preorder_products);
                    set maxID=REPLACE(max_warehouse_PP_code,'WPPC','');
                    open product_cursor;

                REPEAT
                        Fetch product_cursor into
                            product_code_var,
                            product_variant_code_var,
                            mrp_var,
                            admin_margin_type_var,
                            admin_margin_value_var,
                            wholesale_margin_type_var,
                            wholesale_margin_value_var,
                            retail_margin_type_var,
                            retail_margin_value_var,
                            isActive;
                       select count(id) into hasValue from warehouse_preorder_products
                       where warehouse_preorder_listing_code collate utf8mb4_unicode_ci =warehousePreorderListingCode
                       and
                       product_code collate utf8mb4_unicode_ci=product_code_var
                       and ( product_variant_code collate utf8mb4_unicode_ci=product_variant_code_var  or product_variant_code collate utf8mb4_unicode_ci is null);

                           case
                             when hasValue = 0 then
                                set maxID=ifNull(maxID,0)+1;
                                set warehouseppc = CONCAT('WPPC',maxID);

                                Insert into warehouse_preorder_products(
                                warehouse_preorder_product_code,
                                warehouse_preorder_listing_code,
                                product_code,
                                product_variant_code,
                                mrp,
                                admin_margin_type,
                                admin_margin_value,
                                wholesale_margin_type,
                                wholesale_margin_value,
                                retail_margin_type,
                                retail_margin_value,
                                is_active,
                                created_by,
                                updated_by,
                                created_at,
                                updated_at
                                ) values(
                                warehouseppc,
                                warehousePreorderListingCode,
                                product_code_var,
                                product_variant_code_var,
                                mrp_var,
                                admin_margin_type_var,
                                admin_margin_value_var,
                                wholesale_margin_type_var,
                                wholesale_margin_value_var,
                                retail_margin_type_var,
                                retail_margin_value_var,
                                isActive,
                                Created_by,
                                Created_by,
                                CURRENT_TIMESTAMP,
                                CURRENT_TIMESTAMP
                                );
                                else
                                 set hasValue=0;
                               end case ;
                    UNTIL done END REPEAT;
                    close product_cursor;


                    END
        ";

        DB::unprepared("DROP procedure IF EXISTS preorderProductsFromWarehouse");
        DB::unprepared($procedure);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS preorderProductsFromWarehouse");
    }
}
