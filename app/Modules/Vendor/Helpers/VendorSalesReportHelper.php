<?php


namespace App\Modules\Vendor\Helpers;


use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseReturn;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class VendorSalesReportHelper
{
    public static function getVendorSalesReportByVendorCode($vendorCode,$validateMonth){
        $lastYearDate=Carbon::now()->subMonths(($validateMonth['months']) - 1);
        $todayDate=Carbon::now();
        $salesData=WareHousePurchaseOrder::select(
            DB::raw('DATE_FORMAT(order_date, "%M %Y" ) as months'),
            DB::raw("IFNULL(SUM(total_amount),0) as amount"))
            ->where('vendor_code',$vendorCode)
            ->where('status','received')
            ->where('order_date','>=',$lastYearDate)
            ->groupBy('months')

            ->orderBy('order_date')
            ->get();

        $keyed = $salesData->mapWithKeys(function ($item, $key) {
            return [$item['months'] => $item['amount']];
        });
        $period = CarbonPeriod::create($lastYearDate, $todayDate);
        $periodFormat = array();
        foreach ($period as $date) {
            $periodFormat[] = $date->format('F Y');
        }
        $totalMonth =collect($periodFormat)->unique()->flip();
        $data=array();
        foreach($totalMonth as $key=>$value){
            if(isset($keyed[$key])){
                $data[$key] = $keyed[$key];
                }
            else{
                $data[$key] =0;
            }
        }
        return $data;

    }
}
