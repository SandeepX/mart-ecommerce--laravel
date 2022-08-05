<?php

namespace App\Modules\Product\Helpers;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetailView;
use App\Modules\Store\Models\StoreOrderDetails;
use Illuminate\Support\Facades\DB;

class PopularProductsHelper
{

    public static function getAllPopularProductsOfWarehouse($warehouseCode,$paginateBy = 8){

        $products = ProductMaster::join('most_popular_products',function ($join) use ($warehouseCode){
                            $join->on('most_popular_products.product_code','=','products_master.product_code')
                                ->where('most_popular_products.warehouse_code',$warehouseCode);
                        })
                        ->whereHas('warehouseProducts',function($query) use ($warehouseCode){
                            $query->where('warehouse_code',$warehouseCode)
                                ->qualifiedToDisplay()
                                ->havingRaw('SUM(warehouse_product_master.current_stock) > 0');
                        })
                        ->whereHas('unitPackagingDetails')
                        ->groupBy('products_master.product_code')
                        ->orderBy('total_amount','DESC')
                        ->paginate($paginateBy);
        return $products;
    }

    public static function getLimitedPoupularProductsOfWarehouse($warehouseCode,$limitBy = 8){

        $products = ProductMaster::join('most_popular_products',function ($join) use ($warehouseCode){
                  $join->on('most_popular_products.product_code','=','products_master.product_code')
                        ->where('most_popular_products.warehouse_code',$warehouseCode);
            })
            ->whereHas('warehouseProducts',function($query) use ($warehouseCode){
                $query->where('warehouse_code',$warehouseCode)
                    ->qualifiedToDisplay()
                    ->havingRaw('SUM(warehouse_product_master.current_stock) > 0');
            })
            ->whereHas('unitPackagingDetails')
            ->groupBy('products_master.product_code')
            ->orderBy('total_amount','DESC')
            ->limit($limitBy)
            ->get();

        return $products;

    }

}
