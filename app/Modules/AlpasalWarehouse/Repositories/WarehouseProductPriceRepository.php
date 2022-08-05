<?php


namespace App\Modules\AlpasalWarehouse\Repositories;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPriceHistory;
use Carbon\Carbon;

class WarehouseProductPriceRepository
{

    public function getProductPriceHistories($warehouseProductMasterCode){
        return WarehouseProductPriceHistory::where('warehouse_product_master_code',$warehouseProductMasterCode)->latest()->get();
    }

    public function updateProductPrice(WarehouseProductMaster $warehouseProductMaster,$validatedData){
        $warehouseProductMaster->warehouseProductPriceMaster()->updateOrCreate(
            ['warehouse_product_master_code' => $warehouseProductMaster->warehouse_product_master_code],
            $validatedData
        );

        $warehouseProductPriceHistory = WarehouseProductPriceHistory::where('warehouse_product_master_code',$warehouseProductMaster->warehouse_product_master_code)
            ->latest('id')->first();
        //dd($warehouseProductPriceHistory);
        if ($warehouseProductPriceHistory){
            $warehouseProductPriceHistory->to_date = Carbon::now();
            $warehouseProductPriceHistory->save();
        }
        $validatedData['from_date'] = Carbon::now();

        $warehouseProductMaster->warehouseProductPriceHistories()->create($validatedData);

        return $warehouseProductMaster;
    }
}
