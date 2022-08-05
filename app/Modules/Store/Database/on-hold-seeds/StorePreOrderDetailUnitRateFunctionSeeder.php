<?php
namespace  App\Modules\Store\Database\seeds;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorePreOrderDetailUnitRateFunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $sql ="CREATE FUNCTION `pre_order_packaging_unit_rate_new`(`packageCode` VARCHAR(20), `packageHistoryCode` VARCHAR(20), `unitRate` DOUBLE) RETURNS varchar(25) CHARSET utf8mb4 DETERMINISTIC
//        BEGIN
//          DECLARE productPackagingHistory VARCHAR(255);
//             SET productPackagingHistory = (SELECT CASE WHEN micro_unit_code collate utf8mb4_unicode_ci =packageCode THEN unitRate WHEN unit_code collate utf8mb4_unicode_ci =packageCode THEN micro_to_unit_value*unitRate WHEN macro_unit_code collate utf8mb4_unicode_ci =packageCode THEN unit_to_macro_value*micro_to_unit_value*unitRate WHEN super_unit_code collate utf8mb4_unicode_ci =packageCode THEN macro_to_super_value*unit_to_macro_value*micro_to_unit_value*unitRate END from product_packaging_history where product_packaging_history_code collate utf8mb4_unicode_ci =packageHistoryCode LIMIT 1);
//          RETURN productPackagingHistory;
//        END";
//
//        DB::unprepared("DROP function IF EXISTS pre_order_packaging_unit_rate_new");
//        DB::unprepared($sql);



    }
}
