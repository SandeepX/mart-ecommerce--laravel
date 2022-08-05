<?php
namespace App\Modules\Brand\Helpers;

use App\Modules\Brand\Models\Brand;

class BrandProductsHelper{

    public static function test($wareHouseCodes){
        $productsCount=Brand::withCount('productsCount')
            ->whereHas('products',function($query)use($wareHouseCodes){
                $query->whereHas('warehouseProducts',function($query)use($wareHouseCodes){
                    $query->whereIn('warehouse_code',$wareHouseCodes);
                });
            })
            ->where('is_featured',1)->get();
//        $productsCount=Brand::with('products')
//            ->where('is_featured',1)->get();

        return $productsCount;
    }

public static function getBrandProductsCount($filterParameters){
    $issetWarehouseCodes=(isset($filterParameters['warehouseCodes'])&& !empty($filterParameters['warehouseCodes'])) ? true :false;
    $products = Brand::withCount(['products'=>function ($query) use ($issetWarehouseCodes,$filterParameters){
        //dd($issetWarehouseCodes);
            $query->when($issetWarehouseCodes,function($query)use($filterParameters){
                    $query->whereHas('warehouseProducts',function ($query) use ($filterParameters){
                        $query->whereIn('warehouse_code',$filterParameters['warehouseCodes'])
                            ->where('is_active',1)
                            ->where('current_stock','>',0)
                            ->whereHas('warehouseProductPriceMaster');
                    });
            });
    }])
        ->where('is_featured',1)
        ->having('products_count','>',0)
        ->get();
    return $products;
}
}
