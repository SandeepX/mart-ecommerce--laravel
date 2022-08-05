<?php


namespace App\Modules\Product\Database\seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPackagingMicroQuantityByProductCode extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql ="CREATE FUNCTION `product_packaging_micro_quantity_function_by_product_code`(`packageCode` VARCHAR(20), `quantity` DOUBLE, `productCode` VARCHAR(20), `productVariantCode` VARCHAR(20)) RETURNS double DETERMINISTIC
        BEGIN
          DECLARE productPackagingMicroQuantity VARCHAR(255);
             SET productPackagingMicroQuantity = (SELECT CASE WHEN micro_unit_code collate utf8mb4_unicode_ci =packageCode THEN quantity WHEN unit_code collate utf8mb4_unicode_ci =packageCode THEN micro_to_unit_value*quantity WHEN macro_unit_code collate utf8mb4_unicode_ci =packageCode THEN unit_to_macro_value*micro_to_unit_value*quantity WHEN super_unit_code collate utf8mb4_unicode_ci =packageCode THEN macro_to_super_value*unit_to_macro_value*micro_to_unit_value*quantity END from product_packaging_details where product_code collate utf8mb4_unicode_ci =productCode AND (product_variant_code collate utf8mb4_unicode_ci =productVariantCode OR product_variant_code collate utf8mb4_unicode_ci IS NULL) AND (micro_unit_code collate utf8mb4_unicode_ci =packageCode OR unit_code collate utf8mb4_unicode_ci =packageCode OR macro_unit_code collate utf8mb4_unicode_ci =packageCode OR super_unit_code collate utf8mb4_unicode_ci =packageCode) AND deleted_at IS NULL LIMIT 1);
          RETURN productPackagingMicroQuantity;
        END";
        DB::unprepared("DROP function IF EXISTS product_packaging_micro_quantity_function_by_product_code");
        DB::unprepared($sql);
    }
}
