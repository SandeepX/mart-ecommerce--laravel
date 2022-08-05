<?php


namespace App\Modules\Product\Database\seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPackagingToMicroQuantityFunctionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql ="CREATE FUNCTION `product_packaging_to_micro_quantity_function`(`packageCode` VARCHAR(20), `packageHistoryCode` VARCHAR(20), `quantity` DOUBLE) RETURNS varchar(25) CHARSET utf8mb4 DETERMINISTIC
        BEGIN
          DECLARE microQuantity VARCHAR(255);
             SET microQuantity = (SELECT CASE WHEN micro_unit_code collate utf8mb4_unicode_ci =packageCode THEN quantity WHEN unit_code collate utf8mb4_unicode_ci =packageCode THEN micro_to_unit_value*quantity WHEN macro_unit_code collate utf8mb4_unicode_ci =packageCode THEN unit_to_macro_value*micro_to_unit_value*quantity WHEN super_unit_code collate utf8mb4_unicode_ci =packageCode THEN macro_to_super_value*unit_to_macro_value*micro_to_unit_value*quantity END from product_packaging_history where product_packaging_history_code collate utf8mb4_unicode_ci =packageHistoryCode LIMIT 1);
          RETURN microQuantity;
        END";

        DB::unprepared("DROP function IF EXISTS product_packaging_to_micro_quantity_function");
        DB::unprepared($sql);
    }
}
