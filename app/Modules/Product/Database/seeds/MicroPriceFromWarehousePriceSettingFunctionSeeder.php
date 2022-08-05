<?php


namespace App\Modules\Product\Database\seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MicroPriceFromWarehousePriceSettingFunctionSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "CREATE  FUNCTION `microPriceFromWarehousePriceSetting`(`mrp` DOUBLE, `wholesale_margin_type` VARCHAR(5), `wholesale_margin_value` DOUBLE, `retail_margin_type` VARCHAR(5), `retail_margin_value` DOUBLE) RETURNS double NO SQL DETERMINISTIC
                BEGIN
                DECLARE microPrice DOUBLE;
                SET microPrice = mrp-(CASE wholesale_margin_type WHEN 'p' then
                       (wholesale_margin_value/100 * mrp )
                       ELSE
                     wholesale_margin_value END) - (CASE retail_margin_type WHEN 'p' then (retail_margin_value/100 * mrp) ELSE retail_margin_value END);
                RETURN microPrice;
                END";

        DB::unprepared("DROP function IF EXISTS  microPriceFromWarehousePriceSetting");
        DB::unprepared($sql);
    }


}
