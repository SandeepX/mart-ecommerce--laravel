<?php


namespace App\Modules\Accounting\Helpers\FiscalYear;


use App\Modules\Accounting\Models\FiscalYear;

class FiscalYearHelper
{
    public static function getCurrentFiscalYearCode(){
        try{
            $fiscalYear =   FiscalYear::where('is_closed',0)->latest()->first();

            if(!$fiscalYear){
               throw new \Exception('Fiscal Year Not Found!');
            }
            $fiscalYearCode = $fiscalYear->fiscal_year_code;
            return $fiscalYearCode;

        }catch (\Exception $exception){
            throw $exception;
        }
    }

}
