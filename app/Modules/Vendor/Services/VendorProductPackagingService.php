<?php


namespace App\Modules\Vendor\Services;

use App\Modules\AlpasalWarehouse\Helpers\WarehousePurchaseOrderHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use App\Modules\Vendor\Repositories\VendorProductPackagingRepository;
use App\Modules\Vendor\Repositories\VendorProductRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class VendorProductPackagingService
{
    private $vendorProductRepository,$vendorProductPackagingRepository;
    private $vendorProductPackagingHistoryRepository;

    public function __construct(
                            VendorProductRepository $vendorProductRepository,
                            VendorProductPackagingRepository $vendorProductPackagingRepository,
                            VendorProductPackagingHistoryRepository $vendorProductPackagingHistoryRepository
    ){
        $this->vendorProductRepository = $vendorProductRepository;
        $this->vendorProductPackagingRepository = $vendorProductPackagingRepository;
        $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
    }

    public function getProductPackagingDetail($productCode){
        try{
            //P1016 ->with variant
            //P1078 ->with no variant
            $authVendorCode = getAuthVendorCode();
            $product = $this->vendorProductRepository->findOrFailProductOfVendor(
                $productCode,$authVendorCode);

            $productVariantsPackagingDetails = ProductUnitPackagingHelper::getProductPackagingDetail($productCode);

            return $productVariantsPackagingDetails;

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveProductPackagingDetail(
        $validatedData,$productCode){
        try{

           // dd($validatedData);
            $hasVariants=false;
            $authVendorCode = getAuthVendorCode();
            $with =['productVariants'];
            $product = $this->vendorProductRepository->findOrFailProductOfVendor(
                $productCode,$authVendorCode,$with);
            if ($product->hasVariants()){
                $hasVariants=true;
            }
            DB::beginTransaction();
            foreach($validatedData['micro_unit_code'] as $key => $microUnitCode){
                $productVariantCode=null;
                if ($hasVariants){
                    $productVariantCode=$validatedData['product_variant_code'][$key];
                    $productVariant = $product->productVariants()
                        ->where('product_variant_code',$productVariantCode)
                        ->first();

                    if (!$productVariant) {
                        throw new Exception('No Such Variant Found ! of code : '.$productVariantCode);
                    }
                }
                $productVariantName = isset($productVariant) ? '('.$productVariant->product_variant_name.')' : null;

                $warehouseProductVariantExists = WarehousePurchaseOrderHelper::checkProductVariantExistsInWarehousePurchase(
                                                                                    $productCode,$productVariantCode
                                                                                );

                $productLatestPackagingHistory = $this->vendorProductPackagingRepository
                                                               ->getLatestPackagingHistory(
                                                                   $productCode,
                                                                   $productVariantCode
                                                               );

                $updateConditionFields = [
                    'product_code' => $productCode,
                    'product_variant_code' => $productVariantCode,
                ];
                $insertAbleFields =  [
                    'micro_unit_code' => $validatedData['micro_unit_code'][$key],
                    'unit_code' => $validatedData['unit_code'][$key],
                    'macro_unit_code' => isset($validatedData['macro_unit_code'][$key]) ? $validatedData['macro_unit_code'][$key] : null,
                    'super_unit_code' => isset($validatedData['super_unit_code'][$key]) ?  $validatedData['super_unit_code'][$key] : null,
                    'micro_to_unit_value' => intval($validatedData['micro_to_unit_value'][$key]),
                    'unit_to_macro_value' => isset($validatedData['unit_to_macro_value'][$key]) ? intval($validatedData['unit_to_macro_value'][$key]) : null,
                    'macro_to_super_value' => isset($validatedData['macro_to_super_value'][$key]) ?  intval($validatedData['macro_to_super_value'][$key]) : null
                ];

                $packagingHistoryData = $updateConditionFields + $insertAbleFields;

                $latestPPHData = $productLatestPackagingHistory->only('micro_unit_code','unit_code',
                    'macro_unit_code','super_unit_code','micro_to_unit_value','unit_to_macro_value','macro_to_super_value');
                //dd($latestPPHData);

                if(!$warehouseProductVariantExists){
                    if(!$productLatestPackagingHistory){
                        $this->vendorProductPackagingRepository->updateOrCreateProductPackaging(
                                                                                        $updateConditionFields,
                                                                                        $insertAbleFields
                                                                                       );

                        $this->vendorProductPackagingHistoryRepository->saveProductPackagingHistory($packagingHistoryData);
                    }else{
                         //if has already packing configuration
                        // check its latest pph
                      $changeInPackagingConfiguration = array_diff_assoc($insertAbleFields , $latestPPHData);
                      if(count($changeInPackagingConfiguration) > 0){

                          $this->vendorProductPackagingRepository->updateOrCreateProductPackaging(
                                                                                    $updateConditionFields,
                                                                                    $insertAbleFields
                                                                                   );

                          $this->vendorProductPackagingHistoryRepository->updateEndOfProductPackagingHistory(
                                                                              $productLatestPackagingHistory
                                                                          );
                          $this->vendorProductPackagingHistoryRepository->saveProductPackagingHistory($packagingHistoryData);
                      }
                    }
                }else{
                    if(isset($latestPPHData['micro_unit_code']) && $insertAbleFields['micro_unit_code'] !== $latestPPHData['micro_unit_code']){
                        throw new Exception('Cannot change package of '.$product->product_name.' '.$productVariantName.' once its it is ordered by warehouse');
                    }
                    if(isset($latestPPHData['unit_code']) && $insertAbleFields['unit_code'] !== $latestPPHData['unit_code']){
                        throw new Exception('Cannot change package of '.$product->product_name.' '.$productVariantName.' once its it is ordered by warehouse');
                    }
                    if(isset($latestPPHData['macro_unit_code']) && ($insertAbleFields['macro_unit_code'] !== $latestPPHData['macro_unit_code'])){
                        throw new Exception('Cannot change package of '.$product->product_name.' '.$productVariantName.' once its it is ordered by warehouse');
                    }
                    if(isset($latestPPHData['super_unit_code']) && ($insertAbleFields['super_unit_code'] !== $latestPPHData['super_unit_code'])){
                        throw new Exception('Cannot change package of '.$product->product_name.' '.$productVariantName.' once its it is ordered by warehouse');
                    }
                    if(isset($latestPPHData['micro_to_unit_value']) && ($insertAbleFields['micro_to_unit_value'] !== $latestPPHData['micro_to_unit_value'])){
                        throw new Exception('Cannot change package of '.$product->product_name.' '.$productVariantName.' once its it is ordered by warehouse');
                    }
                    if(isset($latestPPHData['unit_to_macro_value']) && ($insertAbleFields['unit_to_macro_value'] !== $latestPPHData['unit_to_macro_value'])){
                        throw new Exception('Cannot change package of '.$product->product_name.' '.$productVariantName.' once its it is ordered by warehouse');
                    }
                    if(isset($latestPPHData['macro_to_super_value']) && ($insertAbleFields['macro_to_super_value'] !== $latestPPHData['macro_to_super_value'])){
                        throw new Exception('Cannot change package of '.$product->product_name.' '.$productVariantName.' once its it is ordered by warehouse');
                    }

                    $changeInPackagingConfiguration = array_diff_assoc($insertAbleFields , $latestPPHData);
                    if(count($changeInPackagingConfiguration) > 0) {
                         $this->vendorProductPackagingRepository->updateOrCreateProductPackaging(
                                                                                      $updateConditionFields,
                                                                                      $insertAbleFields
                                                                                   );

                        $this->vendorProductPackagingHistoryRepository->updateEndOfProductPackagingHistory(
                                                                            $productLatestPackagingHistory
                                                                        );
                        $this->vendorProductPackagingHistoryRepository->saveProductPackagingHistory($packagingHistoryData);
                    }
                }
            }

          DB::commit();

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function updateEndDateToProductPackagingHistory(ProductPackagingHistory $productLatestPackagingHistory,$updatedBy){
                $productLatestPackagingHistoryData = [];

                return $this->vendorProductPackagingHistoryRepository->updateEndOfProductPackagingHistory(
                                                                            $productLatestPackagingHistory
                                                                        );
    }



    public function saveProductPackagingDetailOld(
        $validatedData,$productCode){
        try{
            $hasVariants=false;
            $authVendorCode = getAuthVendorCode();
            $with =['productVariants'];
            $product = $this->vendorProductRepository->findOrFailProductOfVendor(
                $productCode,$authVendorCode,$with);
            if ($product->hasVariants()){
                $hasVariants=true;
            }
            DB::beginTransaction();
            foreach($validatedData['micro_unit_code'] as $key => $microUnitCode){
                $productVariantCode=null;
                if ($hasVariants){
                    $productVariantCode=$validatedData['product_variant_code'][$key];
                    $productVariant = $product->productVariants()
                        ->where('product_variant_code',$productVariantCode)
                        ->first();

                    if (!$productVariant) {
                        throw new Exception('No Such Variant Found ! of code : '.$productVariantCode);
                    }
                }
                $toBeStoredData =[
                    'product_code' =>$productCode,
                    'product_variant_code'=>$productVariantCode,
                    'micro_unit_code' => $validatedData['micro_unit_code'][$key],
                    'unit_code' => $validatedData['unit_code'][$key],
                    'macro_unit_code' => isset($validatedData['macro_unit_code'][$key]) ? $validatedData['macro_unit_code'][$key]: null,
                    'super_unit_code' => isset($validatedData['super_unit_code'][$key]) ? $validatedData['super_unit_code'][$key]: null,
                    'micro_to_unit_value' => isset($validatedData['micro_to_unit_value'][$key]) ? $validatedData['micro_to_unit_value'][$key]: null,
                    'unit_to_macro_value' => isset($validatedData['unit_to_macro_value'][$key]) ? $validatedData['unit_to_macro_value'][$key]: null,
                    'macro_to_super_value' => isset($validatedData['macro_to_super_value'][$key]) ? $validatedData['macro_to_super_value'][$key]: null,
                    /*'super_unit_code' => $validatedData['super_unit_code'][$key],
                    'micro_to_unit_value' => $validatedData['micro_to_unit_value'][$key],
                    'unit_to_macro_value' => $validatedData['unit_to_macro_value'][$key],
                    'macro_to_super_value' => $validatedData['macro_to_super_value'][$key],*/
                ];

                $this->vendorProductPackagingRepository->updateOrCreateProductPackagingOld($toBeStoredData);
            }
            DB::commit();

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteProductPackagingDetail($productPackagingDetailCode){
        try{
            $productPackagingDetail = $this->vendorProductPackagingRepository->findOrFailByCode($productPackagingDetailCode);
            $authVendorCode = getAuthVendorCode();
            $product = $this->vendorProductRepository->findOrFailProductOfVendor(
                $productPackagingDetail->product_code,$authVendorCode);

            $this->vendorProductPackagingRepository->deleteProductPackagingDetail($productPackagingDetail);
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function getProductPackagingDetailByProductAndProductVariantCode($productCode,$productVariantCode=null)
    {
        try{
            return $this->vendorProductPackagingRepository
                ->getProductPackagingDetailByProductAndProductVariantCode($productCode,$productVariantCode);
        }catch (Exception $exception){
            throw $exception;
        }
    }

}
