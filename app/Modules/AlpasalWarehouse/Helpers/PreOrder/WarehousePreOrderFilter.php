<?php


namespace App\Modules\AlpasalWarehouse\Helpers\PreOrder;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;


class WarehousePreOrderFilter
{


    public static function getProductsInVendor($vendor_code){
           $productsInVendor=ProductMaster::where('vendor_code',$vendor_code)
               ->qualifiedToDisplay()->get();
           return $productsInVendor;
    }

    public static function getVariantsOfProduct($product_code)
    {
        $varientsInProduct=ProductVariant::where('product_code',$product_code)
            ->get();
        return $varientsInProduct;
    }
}
