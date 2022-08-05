<?php


namespace App\Modules\Product\Database\seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPackagingUnitRateByProductCodeFunction extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql ="CREATE FUNCTION `product_packaging_unit_rate_function_by_product_code`(`packageCode` VARCHAR(20), `unitRate` DOUBLE, `productCode` VARCHAR(20), `productVariantCode` VARCHAR(20)) RETURNS double DETERMINISTIC
        BEGIN
          DECLARE productPackagingUnitRate VARCHAR(255);
             SET productPackagingUnitRate = (SELECT CASE WHEN micro_unit_code collate utf8mb4_unicode_ci =packageCode THEN unitRate WHEN unit_code collate utf8mb4_unicode_ci =packageCode THEN micro_to_unit_value*unitRate WHEN macro_unit_code collate utf8mb4_unicode_ci =packageCode THEN unit_to_macro_value*micro_to_unit_value*unitRate WHEN super_unit_code collate utf8mb4_unicode_ci =packageCode THEN macro_to_super_value*unit_to_macro_value*micro_to_unit_value*unitRate END from product_packaging_details where product_code collate utf8mb4_unicode_ci =productCode AND (product_variant_code collate utf8mb4_unicode_ci =productVariantCode OR product_variant_code collate utf8mb4_unicode_ci IS NULL) AND (micro_unit_code collate utf8mb4_unicode_ci =packageCode OR unit_code collate utf8mb4_unicode_ci =packageCode OR macro_unit_code collate utf8mb4_unicode_ci =packageCode OR super_unit_code collate utf8mb4_unicode_ci =packageCode) AND deleted_at IS NULL LIMIT 1);
          RETURN productPackagingUnitRate;
        END";
        DB::unprepared("DROP function IF EXISTS product_packaging_unit_rate_function_by_product_code");
        DB::unprepared($sql);
    }
}
