<?php

namespace App\Modules\Store\Helpers\PreOrder;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\Store\Models\PreOrder\PreorderCategoryFilter;
use App\Modules\Product\Models\ProductMaster;

class PreorderProductCategoryHelper
{
    public static function getWarehouseCode($warehouseListingCode)
    {
        $warehouseDetail = WarehousePreOrderListing::where('warehouse_preorder_listing_code', $warehouseListingCode)->latest()->first();

        if (!$warehouseDetail) {
            throw new \Exception('No warehouse with that warehouse preorder listing code found', 404);
        }
        $warehouseCode = $warehouseDetail['warehouse_code'];
        return $warehouseCode;
    }

    public static function filterProductsByParameters(array $filterParameters){
 //dd($filterParameters);
        $productsCode =PreorderCategoryFilter::where('warehouse_code',$filterParameters['warehouse_code'])
            ->when($filterParameters['category_codes'],function ($query) use($filterParameters){
                $query-> whereIn('category_code',$filterParameters['category_codes']);
            })->when($filterParameters['min_price'] && $filterParameters['max_price'],function ($query) use($filterParameters){
                $query->whereBetween('storePrice',[$filterParameters['min_price'], $filterParameters['max_price']]);
            })->groupBy('product_code')->pluck('product_code')->toArray();


        $products = ProductMaster::qualifiedToDisplay()->whereIn('product_code',$productsCode)->paginate(ProductMaster::PRODUCT_PER_PAGE);

        return $products;
    }

}














