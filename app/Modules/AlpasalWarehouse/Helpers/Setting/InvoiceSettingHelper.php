<?php


namespace App\Modules\AlpasalWarehouse\Helpers\Setting;



use App\Modules\AlpasalWarehouse\Models\Setting\InvoiceSetting;
use Illuminate\Support\Facades\DB;

class InvoiceSettingHelper
{

    public static function isInvoiceSettingStartingandEndingNumberOverlapping($orderType,$startingNumber,$endingNumber,
    $fiscalYearCode,$exceptWarehoueSettingInvoiceCode=null){

       $qs= " SELECT * FROM `settings_warehouse_invoice`
        where order_type ='$orderType'
                and (starting_number  between '$startingNumber' and  '$endingNumber'
                or ending_number between '$startingNumber' and '$endingNumber'
                or
                '$startingNumber'
            BETWEEN starting_number and ending_number
                or
                '$endingNumber'
            BETWEEN starting_number and ending_number)
            AND setting_warehouse_invoice_code !='$exceptWarehoueSettingInvoiceCode'
            and fiscal_year_code ='$fiscalYearCode'
            and deleted_at is NULL ";

        $invoiceSettings = DB::select($qs);

        if ($invoiceSettings) {
            return true;
        }
        return false;

    }
    public static function canCreateNextInvoiceSetting($orderType,$fiscalYearCode){

      $invoiceSetting = InvoiceSetting::select('ending_number','next_number')->where('order_type',$orderType)
                       ->where('fiscal_year_code',$fiscalYearCode)->latest()->first();

      if(!$invoiceSetting || $invoiceSetting->ending_number < $invoiceSetting->next_number){
          return false;
      }
      return true;
    }
}
