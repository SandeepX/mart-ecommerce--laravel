<?php


namespace App\Modules\Vendor\Repositories;


use App\Modules\Product\Models\ProductPackagingHistory;
use Carbon\Carbon;

class VendorProductPackagingHistoryRepository
{

    public function findProductPackagingHistoryByCode($productPackagingHistoryCode){

        $productPackagingHistory = ProductPackagingHistory::where('product_packaging_history_code',$productPackagingHistoryCode)
                                                            ->first();
        return $productPackagingHistory;
    }

    public function getLatestProductPackagingHistoryByPackageCode(
        $packageCode, $productCode, $productVariantCode = null)
    {

        $productPackagingDetail = ProductPackagingHistory::where('product_code', $productCode)
            ->where('product_variant_code', $productVariantCode)
            ->where(function ($query) use ($packageCode) {
                $query->where('micro_unit_code', $packageCode)
                    ->orWhere('unit_code', $packageCode)
                    ->orWhere('macro_unit_code', $packageCode)
                    ->orWhere('super_unit_code', $packageCode);
            })->orderBy('product_packaging_history.id','DESC')->first();

        return $productPackagingDetail;
    }

    public function getProductPackagingHistoryByProductCodeAndVariantCode($productCode,$productVariantCode=null){

        $productPackagingDetail = ProductPackagingHistory::where('product_code', $productCode)
                                                           ->where('product_variant_code', $productVariantCode)
                                                           ->orderBy('product_packaging_history.id','DESC')
                                                           ->first();
        return $productPackagingDetail;
    }

    public function saveProductPackagingHistory($validatedData){
            $validatedData['from_date'] = Carbon::now();
            $validatedData['created_by'] = getAuthUserCode();
            return ProductPackagingHistory::create($validatedData);
    }

    public function updateEndOfProductPackagingHistory($productPackagingHistory){
            $validatedData = [];
            $validatedData['end_date'] =  Carbon::now();
            $validatedData['updated_by'] =  getAuthUserCode();
            $productPackagingHistory->update($validatedData);
            return $productPackagingHistory->refresh();
    }
}
