<?php


namespace App\Modules\AlpasalWarehouse\Helpers;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStockView;
use Illuminate\Support\Facades\DB;

class WarehouseProductStockHelper
{

    public static  function findWarehouseProductStockByWarehouseCode($warehouseCode,$productCode,$productVariantCode=null,$with=[],$select='*'){

       return  $warehouseProductMaster = WarehouseProductHelper::findWarehouseProductByWarehouseCode($warehouseCode,$productCode,$productVariantCode,$with,$select);

    }

    public static function findOrFailCurrentProductStockInWarehouse($warehouseProductMasterCode){

        //$warehouseProductStock = WarehouseProductStockView::where('code',$warehouseProductMasterCode)->firstOrFail();
        $warehouseProductStock = WarehouseProductMaster::where('warehouse_product_master_code',$warehouseProductMasterCode)->firstOrFail();

        return $warehouseProductStock;

    }

    public static function findCurrentProductStockInWarehouse($warehouseProductMasterCode,$with=[],$select='*'){

       // $warehouseProductStock = WarehouseProductStockView::with($with)->select($select)->where('code',$warehouseProductMasterCode)->first();
        $warehouseProductStock = WarehouseProductMaster::with($with)->select($select)->where('warehouse_product_master_code',$warehouseProductMasterCode)->first();

        return $warehouseProductStock;
    }

    public static function getTotalStockOfWarehouseProduct($productCode,$warehouseCode){

//        $warehouseProductStock = WarehouseProductStockView::select('warehouse_product_stock_view.current_stock) as current_stock')
//            ->Join('warehouse_product_master',function ($join) use ($productCode,$warehouseCode){
//                $join->on('warehouse_product_stock_view.code','warehouse_product_master.warehouse_product_master_code')
//                    ->where('warehouse_product_master.product_code',$productCode)
//                    ->where('warehouse_product_master.warehouse_code',$warehouseCode);
//            })->sum('current_stock');

        $warehouseProductStock = WarehouseProductMaster::select('warehouse_product_master.current_stock) as current_stock')
                    ->where('warehouse_product_master.product_code',$productCode)
                    ->where('warehouse_product_master.warehouse_code',$warehouseCode)
                    ->sum('current_stock');

        return $warehouseProductStock;
    }

    public static function getTotalStockOfWarehouseProductByProductCode(
        $warehouseCode,$productCode,$productVariantCode=null){

//        $warehouseProductStock = WarehouseProductStockView::select('warehouse_product_stock_view.current_stock) as current_stock')
//            ->Join('warehouse_product_master',function ($join) use ($warehouseCode,$productCode,$productVariantCode){
//                $join->on('warehouse_product_stock_view.code','warehouse_product_master.warehouse_product_master_code')
//                    ->where('warehouse_product_master.product_code',$productCode)
//                    ->where('warehouse_product_master.product_variant_code',$productVariantCode)
//                    ->where('warehouse_product_master.warehouse_code',$warehouseCode);
//            })->sum('current_stock');

        $warehouseProductStock = WarehouseProductMaster::select('warehouse_product_master.current_stock) as current_stock')
                    ->where('warehouse_product_master.product_code',$productCode)
                    ->where('warehouse_product_master.product_variant_code',$productVariantCode)
                    ->where('warehouse_product_master.warehouse_code',$warehouseCode)
                    ->sum('current_stock');
        //dd($warehouseProductStock);
        return $warehouseProductStock;
    }

}
