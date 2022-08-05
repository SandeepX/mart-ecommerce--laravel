<?php


namespace App\Modules\Vendor\Repositories;


use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use Carbon\Carbon;

class VendorProductPackagingRepository
{
    public function findOrFailByCode($productPackagingDetailCode, $with = [])
    {
        return ProductUnitPackageDetail::where('product_packaging_detail_code', $productPackagingDetailCode)
            ->firstOrFail();
    }

    public function findProductPackagingDetailByPackageCode(
        $packageCode, $productCode, $productVariantCode = null)
    {

        $productPackagingDetail = ProductUnitPackageDetail::where('product_code', $productCode)
            ->where('product_variant_code', $productVariantCode)
            ->where(function ($query) use ($packageCode) {
                $query->where('micro_unit_code', $packageCode)
                    ->orWhere('unit_code', $packageCode)
                    ->orWhere('macro_unit_code', $packageCode)
                    ->orWhere('super_unit_code', $packageCode);
            })->first();

        return $productPackagingDetail;
    }


    public function updateOrCreateProductPackaging($updateConditionFields, $insertAbleFields){
                           return ProductUnitPackageDetail::updateOrCreate(
                                $updateConditionFields,
                                $insertAbleFields
                            );
    }

    public function updateOrCreateProductPackagingOld($validatedData)
    {
       // dd($validatedData);
        $updateConditionFields = [
            'product_code' => $validatedData['product_code'],
            'product_variant_code' => $validatedData['product_variant_code'],
        ];
        $insertAbleFields =  [
            'micro_unit_code' => $validatedData['micro_unit_code'],
            'unit_code' => $validatedData['unit_code'],
            'macro_unit_code' => $validatedData['macro_unit_code'],
            'super_unit_code' => $validatedData['super_unit_code'],
           // 'micro_to_unit_value' => $validatedData['micro_to_unit_value'],
            'micro_to_unit_value' => number_format((float)$validatedData['micro_to_unit_value'], 2, '.', ''),
            'unit_to_macro_value' =>  number_format((float)$validatedData['unit_to_macro_value'], 2, '.', ''),
            'macro_to_super_value' => number_format((float)$validatedData['macro_to_super_value'], 2, '.', '')
        ];

        $productPackagingDetail = ProductUnitPackageDetail::updateOrCreate(
            $updateConditionFields,
            $insertAbleFields
        );

        $productPackagingHistory = ProductPackagingHistory::where('product_code', $validatedData['product_code'])
            ->where('product_variant_code', $validatedData['product_variant_code'])
            ->latest('id')->first();
        $isNewDataDifferentFromExisting = false;
        $isProductPackagedFirstTime = true;

        if ($productPackagingHistory) {
            $updatableFields = $updateConditionFields + $insertAbleFields; // to be difference keys
            $productPackagingHistoryArray = $productPackagingHistory->toArray();
            $productPackagingHistoryArray = array_intersect_key(
                $productPackagingHistoryArray, $updatableFields); //getting only desired(updateablefields) keys values
            //$requiredCols = array_column($allCols,'product_code');
           // dd($updatableFields,$productPackagingHistoryArray);
             $isNewDataDifferentFromExisting=count(
                 array_diff_assoc($updatableFields,$productPackagingHistoryArray)
             ) > 0 ? true:false;

             $isProductPackagedFirstTime=false;

        }

        if ($isProductPackagedFirstTime){
            $validatedData['from_date'] = Carbon::now();
            $validatedData['created_by'] = $productPackagingDetail->updated_by;

            ProductPackagingHistory::create($validatedData);
        }
        if ($isNewDataDifferentFromExisting){
            $productPackagingHistory->to_date = Carbon::now();
            $productPackagingHistory->updated_by = getAuthUserCode();
            $productPackagingHistory->save();

            $validatedData['from_date'] = Carbon::now();
            $validatedData['created_by'] = $productPackagingDetail->updated_by;

            ProductPackagingHistory::create($validatedData);
        }


        return $productPackagingDetail;

    }

    public function deleteProductPackagingDetail(ProductUnitPackageDetail $productUnitPackageDetail)
    {
        $productUnitPackageDetail->delete();
        return $productUnitPackageDetail;
    }

    public function getProductPackagingDetailByProductAndProductVariantCode(
        $productCode, $productVariantCode = null)
    {

        $productPackagingDetail = ProductUnitPackageDetail::where('product_code', $productCode)
            ->where('product_variant_code', $productVariantCode)
            ->get();
        return $productPackagingDetail;
    }

    public function getLatestPackagingHistory($productCode,$productVariantCode = null){
        $productPackagingHistory = ProductPackagingHistory::where('product_code', $productCode)
            ->where('product_variant_code', $productVariantCode)
            ->latest('id')
             ->first();

        return $productPackagingHistory;
    }
}
