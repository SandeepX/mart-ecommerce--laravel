<?php


namespace App\Modules\Cart\Helpers;


use App\Modules\Cart\Models\Cart;
use Illuminate\Support\Facades\DB;

class CartHelper
{
    public static function getTotalOrderedMicroQuantityOfProduct(
        $warehouseCode,$userCode,$productCode,$productVariantCode=null){
        $carts =Cart::where('product_code', $productCode)
            ->where('product_variant_code',$productVariantCode)
            ->where('user_code',$userCode)
            ->where('warehouse_code',$warehouseCode)
            ->select(DB::raw('SUM(product_packaging_micro_quantity_function_by_product_code(carts.package_code,carts.quantity,carts.product_code,carts.product_variant_code)) as total_micro_ordered_quantity'))
            ->first();
        if ($carts){
            return $carts->total_micro_ordered_quantity;
        }
    }

    public static function getTotalOrderedMicroQuantityOfProductByProductCode(
        $warehouseCode,$userCode,$productCode){
        $carts =Cart::where('product_code', $productCode)
            ->where('user_code',$userCode)
            ->where('warehouse_code',$warehouseCode)
            ->select(DB::raw('SUM(product_packaging_micro_quantity_function_by_product_code(carts.package_code,carts.quantity,carts.product_code,carts.product_variant_code)) as total_micro_ordered_quantity'))
            ->first();
        if ($carts){
            return $carts->total_micro_ordered_quantity;
        }
    }

    public static function getCartsOfUserByProductCode(
        $warehouseCode,$userCode,$productCode,$productVariantCode=null){
        $carts = Cart::where('warehouse_code',$warehouseCode)
            ->where('product_code',$productCode)
            ->where('product_variant_code',$productVariantCode)
            ->where('user_code',$userCode)->get();
        return $carts;
    }


    public static function getQuantityAddedInCart(
        $userCode,
        $warehouseCode,
        $productCode,
        $packageCode,
        $productVariantCode = null

    ){
        $cart =Cart::where('product_code', $productCode)
            ->where('package_code',$packageCode)
            ->where('product_variant_code',$productVariantCode)
            ->where('user_code',$userCode)
            ->where('warehouse_code',$warehouseCode)
            ->first();
        if ($cart){
            return $cart->quantity;
        }
        return 0;
    }
}
