<?php


namespace App\Modules\Store\Helpers;


use App\Modules\AlpasalWarehouse\Helpers\WarehouseHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductPriceHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Store\Models\StoreOrderDetails;

Use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductOrderQuanityLimitHelper;

use Exception;
use Illuminate\Support\Facades\DB;

class StoreOrderHelper
{

    //auth store
    public static function isProductEligibleToOrderByStore($warehouseCode,$quantity
        ,$productCode,$productVariantCode){

        $returnMessage=[
            'message' =>'Something went wrong',
            'isEligible'=>false,
            'product_code'=>$productCode,
            'product_variant_code' =>$productVariantCode,
            'warehouse_code' =>$warehouseCode
        ];

        $warehouseCodes=StoreWarehouseHelper::getActiveWarehousesCodeAssociatedWithStore(getAuthStoreCode());
        $openWarehouseCodes = WarehouseHelper::getAllOpenWarehousesCode();
        $warehouseCodes=array_unique(array_merge($warehouseCodes,$openWarehouseCodes));
        if (!in_array($warehouseCode,$warehouseCodes)){
            $returnMessage['message']='Warehouse not associated';
            return $returnMessage;
            // throw new Exception('Warehouse not associated');
        }

        $warehouseProductMaster = WarehouseProductHelper::findWarehouseProductByWarehouseCode(
            $warehouseCode,
            $productCode,
            $productVariantCode);
        if (!$warehouseProductMaster){
            $returnMessage['message']='Product not found in warehouse';
            return $returnMessage;
            //throw new Exception('Product does not belongs to warehouse');
        }
        if (!$warehouseProductMaster->isActive()){
            $returnMessage['message']='Product is inactive in the warehouse';
            return $returnMessage;
            //throw new Exception('Product does not belongs to warehouse');
        }

        $productStock = WarehouseProductStockHelper::findCurrentProductStockInWarehouse($warehouseProductMaster->warehouse_product_master_code);

        if (!$productStock){
            $returnMessage['message']='Insufficient quantity in warehouse';
            return $returnMessage;
        }
        if ($productStock && $productStock->current_stock < $quantity){
            $returnMessage['message']='Insufficient quantity in warehouse';
            return $returnMessage;
            // throw new Exception('Insufficient quantity in warehouse');
        }

        $warehouseProductOrderLimit = WarehouseProductOrderQuanityLimitHelper::findWarehouseProductOrderQuantityLimit($warehouseProductMaster->warehouse_product_master_code);

        if (isset($warehouseProductOrderLimit['min_order_quantity']) && !empty($warehouseProductOrderLimit['min_order_quantity'])) {
            if ($warehouseProductOrderLimit['min_order_quantity'] > $quantity) {
                $returnMessage['message'] = 'order quantity cannot be less then min order quantity';
                return $returnMessage;
            }
        }
        if (isset($warehouseProductOrderLimit['min_order_quantity']) && !empty($warehouseProductOrderLimit['min_order_quantity'])) {
            if ($warehouseProductOrderLimit['max_order_quantity'] < $quantity) {
                $returnMessage['message'] = 'Max product order quantity Exceeded';
                return $returnMessage;
            }
        }


        $priceSetting=WarehouseProductPriceHelper::findWarehouseProductPriceByWarehouseProductCode($warehouseProductMaster->warehouse_product_master_code);
        if (!$priceSetting){
            $returnMessage['message']='Price setting not found for the product';
            return $returnMessage;
        }
        $returnMessage['message']='Eligible';
        $returnMessage['isEligible']=true;
        return $returnMessage;
    }

    public static function newisProductEligibleToOrderByStore($warehouseCode,$microQuantity
        ,$validatedData){

        $returnMessage=[
            'message' =>'Something went wrong',
            'isEligible'=>false,
            'product_code'=>$validatedData['product_code'],
            'product_variant_code' =>$validatedData['product_variant_code'],
            'warehouse_code' =>$warehouseCode
        ];


        $warehouseProductMasterwithStockAndPrice = WarehouseProductMaster::select(
            'warehouse_product_master.warehouse_product_master_code',
            'warehouse_product_master.warehouse_code',
            'warehouse_product_master.product_code',
            'warehouse_product_master.product_variant_code',
            'warehouse_product_master.min_order_quantity',
            'warehouse_product_master.max_order_quantity',
            'warehouse_product_master.is_active',
            'warehouse_product_master.current_stock',
            'warehouse_product_price_master.mrp'
        )
            ->where('warehouse_code',$warehouseCode)
            ->where('product_code',$validatedData['product_code'])
            ->where('product_variant_code',$validatedData['product_variant_code'])
//            ->leftJoin('warehouse_product_stock_view',
//                'warehouse_product_master.warehouse_product_master_code'
//                ,'=',
//                'warehouse_product_stock_view.code')
            ->leftJoin('warehouse_product_price_master',
                'warehouse_product_price_master.warehouse_product_master_code',
                '=',
                'warehouse_product_master.warehouse_product_master_code'
            )
            ->first();

        //dd($warehouseProductMasterwithStockAndPrice);

        if(!$warehouseProductMasterwithStockAndPrice){
            $returnMessage['message']='Product not found in warehouse';
            return $returnMessage;
        }

        //for disabled unit list
        $productPackagingHistory = $validatedData['product_packaging_detail'];
        $disabledUnitList = WarehouseProductPackagingUnitDisableList::where('warehouse_product_master_code'
            ,$warehouseProductMasterwithStockAndPrice->warehouse_product_master_code)
            ->pluck('unit_name')->toArray();

        if( $validatedData['package_code'] == $productPackagingHistory->micro_unit_code){
            $orderedPackageName ='micro';
        }elseif ( $validatedData['package_code'] == $productPackagingHistory->unit_code){
            $orderedPackageName ='unit';
        }
        elseif ( $validatedData['package_code'] == $productPackagingHistory->macro_unit_code){
            $orderedPackageName ='macro';
        }
        elseif ( $validatedData['package_code'] == $productPackagingHistory->super_unit_code){
            $orderedPackageName ='super';
        }else{
            $returnMessage['message']='Invalid order package type.';
            return $returnMessage;
        }

        if (in_array($orderedPackageName,$disabledUnitList)){
            $returnMessage['message']='Invalid order package type.';
            return $returnMessage;
        }
        //end of disabled unit list

        if (!$warehouseProductMasterwithStockAndPrice->is_active){
            $returnMessage['message']='Product is inactive in the warehouse';
            return $returnMessage;
        }
        if (!$warehouseProductMasterwithStockAndPrice->current_stock
            ||
            $warehouseProductMasterwithStockAndPrice->current_stock < $microQuantity){

            $returnMessage['message']='Insufficient quantity in warehouse';
            return $returnMessage;
        }

        if (!is_null($warehouseProductMasterwithStockAndPrice->min_order_quantity))
           if( $warehouseProductMasterwithStockAndPrice->min_order_quantity > $microQuantity){
            $returnMessage['message']= 'cannot order product with quantity less than minimum order quantity : '.(int) $warehouseProductMasterwithStockAndPrice->min_order_quantity.'';
            return $returnMessage;
        }

        if (!is_null($warehouseProductMasterwithStockAndPrice->max_order_quantity))
            if($warehouseProductMasterwithStockAndPrice->max_order_quantity < $microQuantity){

            $returnMessage['message']='cannot update product with quantity greater than maximum order quantity : '.(int) $warehouseProductMasterwithStockAndPrice->max_order_quantity.'';
            return $returnMessage;
        }

        if (!$warehouseProductMasterwithStockAndPrice->mrp){
            $returnMessage['message']='Price setting not found for the product';
            return $returnMessage;
        }

        $returnMessage['message']='Eligible';
        $returnMessage['isEligible']=true;
        return $returnMessage;
    }


    public static function storeOrderPlacingLookUp($cartCodes,$authUserCode){
        $bindings =[
            'store_user_code' =>$authUserCode,
            'cart_codes' =>implodeArray($cartCodes)
        ];
        $rawQuery = "
               SELECT
           t1.warehouse_code,
           t1.product_name,
           t1.image,
           t1.product_variant_name,
           t1.warehouse_product_master_code,
           t1.quantity,
           t1.current_stock,
           t1.cart_code,
           t1.product_variant_code,
           t1.product_code,
           t1.is_taxable,
           t1.store_price,
           t1.is_active,
           t1.package_code,
           t1.package_name,
           t1.min_order_quantity,
           t1.max_order_quantity,
           t1.current_stock as micro_current_stock,
           CASE
               WHEN
                   t1.current_stock >= t1.quantity
               THEN
                   0
               ELSE 1
           END AS not_eligible_stock,

           CASE
               WHEN t1.min_order_quantity is null or
                   t1.min_order_quantity <= t1.quantity
               THEN
                   0
               ELSE 1
           END AS not_eligible_min_order_quantity,

            CASE
               WHEN t1.max_order_quantity is null or
                   t1.max_order_quantity >= t1.quantity
               THEN
                   0
               ELSE 1
           END AS not_eligible_max_order_quantity,

            CASE
               WHEN
                  t1.store_price != 0
               THEN
                  0
               ELSE
                   1
            End AS not_eligible_price,

           CASE
               WHEN
                   t1.is_active = 1
               THEN
                   0
               ELSE
                   1
           END AS not_eligible_active,
           CASE
               WHEN t1.is_taxable = '1' THEN t1.store_price / 1.13
               ELSE t1.store_price
           END AS unit_rate,
          Case
               when t1.is_taxable = '1' Then
               (t1.store_price/1.13) * t1.quantity
               ELSE
               (t1.store_price * t1.quantity)
               end as sub_total
       FROM
           (SELECT
               product_name,
                   product_variants.product_variant_name,
                   productImages.image,
                   carts.quantity,
                   cart_code,
                   carts.product_variant_code,
                   carts.product_code,
                   carts.warehouse_code,
                   carts.package_code,
                   products_master.is_taxable,
                   wpm.current_stock,
                   wpm.warehouse_product_master_code,
                   wpm.is_active,
                   wpm.min_order_quantity,
                   wpm.max_order_quantity,
                   package_types.package_name,
                   IFNULL((SELECT
                           mrp - (CASE wholesale_margin_type
                                   WHEN 'p' THEN ((wholesale_margin_value / 100) * mrp)
                                   ELSE wholesale_margin_value
                               END) - (CASE retail_margin_type
                                   WHEN 'p' THEN ((retail_margin_value / 100) * mrp)
                                   ELSE retail_margin_value
                               END)
                       ), 0) AS store_price
           FROM
               `carts`
           INNER JOIN warehouse_product_master AS wpm ON wpm.warehouse_code = carts.warehouse_code
               AND wpm.product_code = carts.product_code
               AND (carts.product_variant_code = wpm.product_variant_code
               OR carts.product_variant_code IS NULL
               AND wpm.product_variant_code IS NULL)
             INNER JOIN products_master ON wpm.product_code = products_master.product_code
              INNER JOIN
               (
                   SELECT product_code,image from product_images where id in (SELECT min(id) from product_images group by product_code)
               )
               as  productImages on wpm.product_code=productImages.product_code
           LEFT JOIN product_variants ON wpm.product_variant_code = product_variants.product_variant_code
           INNER JOIN warehouse_product_price_master ON wpm.warehouse_product_master_code = warehouse_product_price_master.warehouse_product_master_code
           INNER JOIN package_types ON package_types.package_code = carts.package_code
           WHERE
               carts.user_code = '".
            $bindings['store_user_code']."'
           and carts.cart_code IN (".$bindings['cart_codes'].")
                   AND carts.deleted_at IS NULL
           ORDER BY carts.updated_at DESC) AS t1
";
        $results = DB::select($rawQuery);
        return $results;
    }

    public static function getOrderedProductPackagingDetail($storeLookUpResult){
        $productPackagingDetail =ProductUnitPackageDetail::where('product_code',$storeLookUpResult->product_code)
            ->where('product_variant_code',$storeLookUpResult->product_variant_code)
            ->where(function ($q) use ($storeLookUpResult){
                $q->where('micro_unit_code',$storeLookUpResult->package_code)
                    ->orWhere('unit_code',$storeLookUpResult->package_code)
                    ->orWhere('macro_unit_code',$storeLookUpResult->package_code)
                    ->orWhere('super_unit_code',$storeLookUpResult->package_code);
            })->first();

        if (!$productPackagingDetail){
            throw new Exception('Product packaging detail not found for the product.');
        }
     // dd($productPackagingDetail);
        $packagingType = array_search($storeLookUpResult->package_code,$productPackagingDetail->toArray());
        //.if ()

        $productPackagingDetail->ordered_package_type = ProductUnitPackageDetail::PACKAGING_UNIT_TYPES[$packagingType];

        return $productPackagingDetail;

    }

    public static function calculateOrderCost($productPackagingDetail,$storeLookUpResult){

        //dd(gettype( $productPackagingDetail->micro_to_unit_value));
        switch ($productPackagingDetail->ordered_package_type) {
            case 'MICRO_UNIT_TYPE':
                return $storeLookUpResult->unit_rate * $storeLookUpResult->quantity;
            case 'UNIT_TYPE':
                return $storeLookUpResult->unit_rate * $storeLookUpResult->quantity *
                    (float)$productPackagingDetail->micro_to_unit_value;
            case 'MACRO_UNIT_TYPE':
                return $storeLookUpResult->unit_rate * $storeLookUpResult->quantity
                    * (float)$productPackagingDetail->micro_to_unit_value * (float)$productPackagingDetail->unit_to_macro_value;
            case 'SUPER_UNIT_TYPE':
                return $storeLookUpResult->unit_rate * $storeLookUpResult->quantity
                    * (float)$productPackagingDetail->micro_to_unit_value * (float)$productPackagingDetail->unit_to_macro_value
                    * (float)$productPackagingDetail->macro_to_super_value;
            default:
                throw new Exception('Invalid order package type.');
        }
    }


}
