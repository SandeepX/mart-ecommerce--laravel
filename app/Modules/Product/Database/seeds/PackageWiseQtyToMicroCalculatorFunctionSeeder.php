<?php


namespace App\Modules\Product\Database\seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageWiseQtyToMicroCalculatorFunctionSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql ="CREATE FUNCTION `packageWiseQtyToMicroCalculator`(`microToUnitValue` DOUBLE, `unitToMacroValue` DOUBLE, `macroToSuperValue` DOUBLE, `convertInPackageName` VARCHAR(20), `quantity` FLOAT) RETURNS int
        NO SQL
        DETERMINISTIC
        BEGIN
        DECLARE convertedPackageQty DOUBLE;

        IF convertInPackageName = 'micro' THEN
           SET convertedPackageQty = quantity;
        ELSEIF convertInPackageName = 'unit' THEN
           SET convertedPackageQty = quantity * microToUnitValue;
        ELSEIF convertInPackageName = 'macro' THEN
           SET convertedPackageQty = quantity *  microToUnitValue * unitToMacroValue;
        ELSEIF convertInPackageName = 'super' THEN
           SET convertedPackageQty = quantity * microToUnitValue * unitToMacroValue * macroToSuperValue;
        END IF;
        RETURN convertedPackageQty;
        END";

        DB::unprepared("DROP function IF EXISTS packageWiseQtyToMicroCalculator");
        DB::unprepared($sql);
    }

}
