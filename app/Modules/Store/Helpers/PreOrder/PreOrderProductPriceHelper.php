<?php

namespace App\Modules\Store\Helpers\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\Product\Models\ProductPackagingHistory;
use Illuminate\Support\Facades\DB;

class PreOrderProductPriceHelper
{
   public static function getMicroPriceOfPreOrderableProduct($warehousePreOrderProductCode){

       $micoPrice = WarehousePreOrderProduct::select(DB::raw(" mrp -
                                    (CASE
                                       wholesale_margin_type
                                     WHEN 'p'
                                     THEN
                                     ((wholesale_margin_value / 100) * `mrp`)
                                      ELSE `wholesale_margin_value`
                                    END
                                    )
                                    -
                                    (CASE `retail_margin_type`
                            WHEN 'p'
                            THEN
                            ((`retail_margin_value` / 100) * `mrp`)
                            ELSE `retail_margin_value`
                             END)
                        as micro_rate"))
                     ->where('warehouse_preorder_product_code',$warehousePreOrderProductCode)
                     ->first();

       return $micoPrice->micro_rate;

   }


  public static function getPackagePriceOfPreOrderableProduct($packageCode,$productPackagingHistoryCode,$microRate){
      $query = "SELECT product_packaging_unit_rate_function
                           (
                             '".$packageCode."',
                             '".$productPackagingHistoryCode."',
                              $microRate
                           )
                           as package_price";

      $results = DB::select($query);
      if($results[0]->package_price){
        return $results[0]->package_price;
      }
      return 0;
  }



}














