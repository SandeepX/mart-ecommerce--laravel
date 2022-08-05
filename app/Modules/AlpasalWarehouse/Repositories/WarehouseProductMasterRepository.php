<?php


namespace App\Modules\AlpasalWarehouse\Repositories;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductPriceMaster;
use App\Modules\Product\Models\ProductVariant;
use Exception;
use Illuminate\Support\Facades\DB;
class WarehouseProductMasterRepository
{

    public function paginateProductsByProductCode($productCode,$paginateBy,$with=[]){

        return WarehouseProductMaster::with($with)->where('product_code',$productCode)
            ->latest()->paginate($paginateBy);
    }

    public function findProductByWarehouseCode($warehouseCode,$productCode,$productVariantCode=null,$select='*',$with=[]){

        return WarehouseProductMaster::where('warehouse_code',$warehouseCode)
            ->where('product_code',$productCode)->where('product_variant_code',$productVariantCode)
            ->select($select)
            ->with($with)
            ->first();

    }

    public function findOrFailProductByWarehouseCode($warehouseCode,$productCode,$productVariantCode=null){

        $warehouseProduct= WarehouseProductMaster::where('warehouse_code',$warehouseCode)
            ->where('product_code',$productCode)->where('product_variant_code',$productVariantCode)->first();

        if (!$warehouseProduct){
            throw new Exception('No such product of warehouse found');
        }

        return $warehouseProduct;

    }

    public function findOrFailQualifiedProductBySlug($warehouseCode,$productSlug,$with=[]){

        $warehouseProduct= WarehouseProductMaster::with($with)->where('warehouse_code',$warehouseCode)
            ->whereHas('product',function ($query) use ($productSlug){
                $query->where('slug',$productSlug);
            })->first();

        if (!$warehouseProduct){
            throw new Exception('No such product of warehouse found');
        }

        return $warehouseProduct;

    }

    public function findOrFailProductByCode($warehouseProductMasterCode,$warehouseCode,$with=[]){

        $warehouseProduct= WarehouseProductMaster::with($with)->where('warehouse_product_master_code',$warehouseProductMasterCode)
            ->where('warehouse_code',$warehouseCode)->first();

        if (!$warehouseProduct){
            throw new Exception('No such product of warehouse found');
        }

        return $warehouseProduct;
    }

    public function findOrFailProductByProductCode($productCode,$warehouseCode,$with=[]){

        $warehouseProduct= WarehouseProductMaster::with($with)->where('product_code',$productCode)
            ->where('warehouse_code',$warehouseCode)->first();

        if (!$warehouseProduct){
            throw new Exception('No such product of warehouse found');
        }

        return $warehouseProduct;
    }

    //returns with warehouse product price
    public function getProductVariants($warehouseCode,$productCode){

        $productVariants = WarehouseProductMaster::with(['warehouseProductPriceMaster'])
            ->join('product_variants', function ($join) use ($productCode,$warehouseCode) {
            $join->on('product_variants.product_variant_code',
                '=',
                'warehouse_product_master.product_variant_code')
            ->whereNull('product_variants.deleted_at');
        })->where('warehouse_product_master.product_code', $productCode)
            ->where('warehouse_product_master.warehouse_code',$warehouseCode)
            ->select(
                'product_variants.product_variant_code',
                'product_variants.product_code',
                'product_variants.product_variant_name',
                'warehouse_product_master.warehouse_product_master_code',
                'warehouse_product_master.is_active',
                'warehouse_product_master.min_order_quantity',
                'warehouse_product_master.max_order_quantity',
                'warehouse_product_master.current_stock'
            )
            ->get();
       //dd($productVariants);

       /* $productVariants = WarehouseProductMaster::join('product_variants', function ($join) use ($productCode,$warehouseCode) {
            $join->on('product_variants.product_variant_code', '=', 'warehouse_product_master.product_variant_code')
                ->where('warehouse_product_master.product_code', $productCode)
                ->where('warehouse_product_master.warehouse_code',$warehouseCode);
        })->rightJoin('warehouse_product_price_master', function ($join) {
            $join->on('warehouse_product_price_master.warehouse_product_master_code', '=', 'warehouse_product_master.warehouse_product_master_code');
        })->select('product_variants.product_variant_code','product_variants.product_code','product_variants.product_variant_name',
            'warehouse_product_master.warehouse_product_master_code','warehouse_product_price_master.*')
            ->get();*/

       // dd($productVariants);
        return $productVariants;
    }


    public function storeWarehouseProduct($validatedWarehouseProduct){
        $validatedWarehouseProduct['is_active']=1;
        return WarehouseProductMaster::create($validatedWarehouseProduct)->fresh();
    }

    /****toggle status of product****/

    public function toggleWPMStatus($wpmCode)
    {

        $warehouseCode = getAuthWarehouseCode();

        $productDetail = WarehouseProductMaster::where('warehouse_product_master_code',$wpmCode)
                                                ->where('warehouse_code',$warehouseCode)
                                                ->first();
        //dd($productDetail->is_active);
        if (!$productDetail){
            throw new Exception('No such product of warehouse found');
        }
        $statusChanged = $productDetail->update([
            'is_active' => !$productDetail->is_active,
        ]);
        return $statusChanged;
    }

    public function ChangeProductStatus($validatedData)
    {
        try{
            $warehouseCode = getAuthWarehouseCode();
            $changedstatus = DB::table('warehouse_product_master')
                ->where('product_code',$validatedData['product_code'])
                ->where('warehouse_code',$warehouseCode)
                ->update(['warehouse_product_master.is_active'=>  $validatedData['is_active']]);
            return $changedstatus;

        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function warehouseAllProductStatusChange($changeStatusTo)
    {
        try{
            $warehouseCode = getAuthWarehouseCode();
            $changedstatus = WarehouseProductMaster::where('warehouse_code',$warehouseCode)
                                                     ->update(['is_active'=> $changeStatusTo['is_active']]);
            return $changedstatus;

        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function setWarehouseProductQtyOrderLimit($validatedData)
    {

        try{
            $warehouseCode = getAuthWarehouseCode();
            $productDetail = WarehouseProductMaster::where('warehouse_product_master_code',$validatedData['warehouse_product_master_code'])
                ->where('warehouse_code',$warehouseCode)
                ->first();

            return $productDetail->update($validatedData);

        }catch(Exception $exception){
            return $exception;
        }
    }

    public function updateWarehouseProductCurrentStock(WarehouseProductMaster $warehouseProductMaster,$validatedCurrentStock){
        $warehouseProductMaster->update([
           'current_stock' => $validatedCurrentStock
        ]);

        return $warehouseProductMaster->refresh();
    }


}
