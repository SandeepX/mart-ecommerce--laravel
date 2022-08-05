<?php


namespace App\Modules\Product\Database\seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageWisePriceFromMicroCalculatorFunctionSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql ="CREATE FUNCTION `packageWisePriceFromMicroCalculator`(`microToUnitValue` DOUBLE, `unitToMacroValue` DOUBLE, `mactoToSuperValue` DOUBLE, `convertInPackageName` VARCHAR(20), `microPrice` DOUBLE) RETURNS double NO SQL DETERMINISTIC
               BEGIN
               DECLARE convertedPackagePrice DOUBLE;
                IF convertInPackageName = 'micro' THEN
                   SET convertedPackagePrice = microPrice;
                ELSEIF convertInPackageName = 'unit' THEN
                   SET convertedPackagePrice = microPrice * microToUnitValue;
                ELSEIF convertInPackageName = 'macro' THEN
                   SET convertedPackagePrice = microPrice *  microToUnitValue *    unitToMacroValue;
                ELSEIF convertInPackageName = 'super' THEN
                   SET convertedPackagePrice = microPrice *  microToUnitValue *    unitToMacroValue*mactoToSuperValue;
                END IF;
                RETURN convertedPackagePrice;
                END";

            DB::unprepared("DROP function IF EXISTS packageWisePriceFromMicroCalculator");
            DB::unprepared($sql);
    }

}
