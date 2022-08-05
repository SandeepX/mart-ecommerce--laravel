<?php
namespace App\Modules\Product\Helpers;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Product\Models\ProductMaster;


class ProductBrandHelper{
    public static function getWarehouseProductBrandByCode($filterParameters){
        $products = ProductMaster::where('brand_code',$filterParameters['brand_code'])
            ->whereHas('warehouseProducts',function($query) use ($filterParameters){
                $query->whereIn('warehouse_code',$filterParameters['warehouse_codes'])
                  //  ->qualifiedToDisplay();
                    ->havingRaw('SUM(warehouse_product_master.current_stock) > 0');
            })
          //  ->whereHas('unitPackagingDetails')
            ->paginate($filterParameters['paginated']);
        return $products;
    }
}
