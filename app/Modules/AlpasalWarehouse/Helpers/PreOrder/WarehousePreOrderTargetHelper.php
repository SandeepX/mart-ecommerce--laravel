<?php


namespace App\Modules\AlpasalWarehouse\Helpers\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderTarget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class WarehousePreOrderTargetHelper
{
   public static function getGroupPreOrderTarget($preOrderListingCode,$storeTypeCode)
   {
       $wptarget= WarehousePreOrderTarget::where('warehouse_preorder_listing_code',$preOrderListingCode)
           ->where('target_type','group')->where('store_type_code',$storeTypeCode)->first();
       if(isset($wptarget) && $wptarget->count())
       {
           return $wptarget->target_value;
       }

   }
    public static function getIndividualPreOrderTarget($preOrderListingCode,$storeTypeCode)
    {
        $wptarget= WarehousePreOrderTarget::where('warehouse_preorder_listing_code',$preOrderListingCode)
            ->where('target_type','individual')->where('store_type_code',$storeTypeCode)->first();

        if(isset($wptarget) && $wptarget->count())
        {
            return $wptarget->target_value;
        }
    }

    public static function preOrderTargetable($preOrderListingCode)
    {
        try {
            $warehousePreOrderListing=WarehousePreOrderListing::where('warehouse_preorder_listing_code',$preOrderListingCode)
                ->where('start_time', '<=', Carbon::now('Asia/Kathmandu')->toDateTimeString())
                ->where('end_time', '>=', Carbon::now('Asia/Kathmandu')->toDateTimeString())->get();
            return $warehousePreOrderListing;
        }
        catch (\Exception $exception){
            return redirect()->route('warehouse.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public static function isTargetAchieved($totalOrder,$totalTarget)
    {
        if($totalOrder>=$totalTarget)
        {
            return true;
        }
        else{
            return false;
        }
    }

}
