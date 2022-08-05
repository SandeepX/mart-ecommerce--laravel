<?php


namespace App\Modules\AlpasalWarehouse\Helpers\StockTransfer;



use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;

use App\Modules\Product\Models\ProductUnitPackageDetail;
use Illuminate\Support\Facades\DB;


class StockTransferHelper
{


    public static function getWarehouseTransferableProducts(Warehouse $warehouse){

        $products = WarehouseProductMaster::select(
                                                'warehouse_product_master_code',
                                                'warehouse_code',
                                                'product_code',
                                                'product_variant_code',
                                                'vendor_code',
                                                'current_stock'
                                            )
//                                            ->join(
//                                                'warehouse_product_stock_view as wpsv',
//                                                'wpsv.code',
//                                                '=',
//                                                'warehouse_product_master.warehouse_product_master_code'
//                                            )
                                           ->where('warehouse_product_master.warehouse_code',$warehouse->warehouse_code)
                                           ->where('current_stock','>',0)
                                           ->get();
        return $products;
    }


    public static function getOrderedProductPackagingDetailForStockTransfer($productCode, $productVariantCode,$packageCode){
        $productPackagingDetail =ProductUnitPackageDetail::where('product_code',$productCode)
            ->where('product_variant_code',$productVariantCode)
            ->where(function ($q) use ($packageCode){
                $q->where('micro_unit_code',$packageCode)
                    ->orWhere('unit_code',$packageCode)
                    ->orWhere('macro_unit_code',$packageCode)
                    ->orWhere('super_unit_code',$packageCode);
            })->first();

        if (!$productPackagingDetail){
            throw new \Exception('Product packaging detail not found for the product.');
        }
        // dd($productPackagingDetail);
        $packagingType = array_search($packageCode,$productPackagingDetail->toArray());
        //.if ()

        $productPackagingDetail->ordered_package_type = ProductUnitPackageDetail::PACKAGING_UNIT_TYPES[$packagingType];

        return $productPackagingDetail;
    }
}
