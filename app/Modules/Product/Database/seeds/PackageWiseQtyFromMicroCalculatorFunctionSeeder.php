<?php


namespace App\Modules\Product\Database\seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageWiseQtyFromMicroCalculatorFunctionSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = "CREATE FUNCTION `packageWiseQtyFromMicroCalculator`(`microToUnitValue` DOUBLE, `unitToMacroValue` DOUBLE, `macroToSuperValue` DOUBLE, `convertInPackageName` VARCHAR(20), `microQuantity` DOUBLE) RETURNS int NO SQL DETERMINISTIC
            BEGIN
            DECLARE convertedPackageQty DOUBLE;
            IF convertInPackageName = 'micro' THEN
               SET convertedPackageQty = microQuantity;
            ELSEIF convertInPackageName = 'unit' THEN
               SET convertedPackageQty = microQuantity DIV (microToUnitValue);
            ELSEIF convertInPackageName = 'macro' THEN
               SET convertedPackageQty = microQuantity DIV ( microToUnitValue *    unitToMacroValue);
            ELSEIF convertInPackageName = 'super' THEN
               SET convertedPackageQty = microQuantity DIV ( microToUnitValue *    unitToMacroValue*macroToSuperValue);
            END IF;
            RETURN convertedPackageQty;
            END";

        DB::unprepared("DROP function IF EXISTS packageWiseQtyFromMicroCalculator");
        DB::unprepared($sql);
    }

}
