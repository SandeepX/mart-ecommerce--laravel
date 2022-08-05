<?php

namespace App\Modules\Product\Services;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Product\Helpers\ProductVariantHelper;
use App\Modules\Product\Models\ProductVariantGroup;
use App\Modules\Product\Repositories\ProductVariantGroup\ProductVariantGroupRepository;
use App\Modules\Product\Repositories\ProductVariantGroup\PVGroupBulkImageRepository;
use App\Modules\Product\Repositories\ProductVariantRepository;
use App\Modules\Variants\Repositories\VariantRepository;
use App\Modules\Variants\Repositories\VariantValueRepository;
use App\Modules\Vendor\Repositories\ProductPriceRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductVariantService
{
    use ImageService;
    private $productVariantRepository;
    private $productPriceRepository;
    private $variantValueRepository;
    private $productVariantGroupRepository;
    private $pvGroupBulkImageRepository;
    public function __construct(
        ProductVariantRepository $productVariantRepository,
        ProductPriceRepository $productPriceRepository,
        VariantValueRepository $variantValueRepository,
        ProductVariantGroupRepository $productVariantGroupRepository,
        PVGroupBulkImageRepository $pvGroupBulkImageRepository
    )
    {
        $this->productVariantRepository = $productVariantRepository;
        $this->productPriceRepository = $productPriceRepository;
        $this->variantValueRepository = $variantValueRepository;
        $this->productVariantGroupRepository = $productVariantGroupRepository;
        $this->pvGroupBulkImageRepository = $pvGroupBulkImageRepository;
    }


    public function storeProductVariant($product, $validatedProductVariant)
    {
        try {
            $variantTotal = [];
            foreach ($validatedProductVariant['combinations'] as $combination) {
                $combinationnName = '';
                $variantData = [];
                $variantValues = [];
                foreach ($combination['combination_values'] as $combinationValue) {
                    $variantValue = $this->variantValueRepository->findOrFailVariantValueByCode($combinationValue['variant_value_code']);
                    $combinationnName = $combinationnName . '-' . (trim(strtolower($variantValue['variant_value_name'])));
                    array_push($variantValues, $combinationValue['variant_value_code']);
                }
                $variantData['combination_name'] = ltrim($combinationnName, '-');
                // $variantData['price'] = $combination['price'];
                // $variantData['remarks'] = $combination['remarks'];
                if(isset($combination['images'])){
                    $variantData['images'] = $combination['images'];
                }
                $variantData['values'] = $variantValues;
                array_push($variantTotal, $variantData);
            }
            $this->productVariantRepository->createProductVariant($product, $variantTotal);
        } catch (Exception $exception) {
            throw ($exception);
        }
    }

    public function newStoreProductVariant($product, $validatedProductVariant){
        try {

            $filesToBeDeleted = [];
            $selectedVariantsAttribute = $validatedProductVariant['selected_attribute'];
            $filesInsertedInBulImages = [];
            $filesInsertedInProductVariantImages = [];

            foreach($validatedProductVariant['variant_groups'] as $valueGroups){
                $groupsData = [];
                $groupsDataBulkImages = [];
                $groupsData['product_code'] =  $product->product_code;
                $groupsData['group_name'] = $valueGroups['group_name'];
                $groupsData['group_variant_value_code'] = $valueGroups['group_vv_code'];
                $variantTotal =  $this->generateVariantCombinations($valueGroups['combinations'],$selectedVariantsAttribute);
                $productVariantGroup = $this->productVariantGroupRepository->createProductVariantGroup($groupsData);

                if(isset($valueGroups['bulk_images'])){
                    $filesInsertedInBulImages =   $this->pvGroupBulkImageRepository->saveGroupBulkImages(
                                $valueGroups['bulk_images'],
                                $productVariantGroup->product_variant_group_code
                            );
                }
                $filesInsertedInProductVariantImages =  $this->productVariantRepository->newCreateProductVariant(
                    $product,
                    $variantTotal,
                    $productVariantGroup->product_variant_group_code
                );
            }

        } catch (Exception $exception) {

            if($exception){
                $filesToBeDeleted =  array_merge($filesInsertedInBulImages,$filesInsertedInProductVariantImages);
                if($filesToBeDeleted){
                    foreach($filesToBeDeleted as $file){
                        $this->deleteImageFromServer($file['path'],$file['image']);
                    }
                }
            }

            throw ($exception);
        }
    }

    public function generateVariantCombinations($variantsGroupsCombinations, $selectedVariantsAttribute){

        $variantTotal = [];
        foreach ($variantsGroupsCombinations as $combination) {
            if(count($combination['combination_values']) != count($selectedVariantsAttribute)){
                throw new Exception('you are trying to corrupt the data');
            }
            $combinationnName = '';
            $productVVCode = '';
            $variantData = [];
            $variantValues = [];
            foreach ($combination['combination_values'] as $key => $combinationValue) {
                if(!ProductVariantHelper::checkVariantValueOfSameVariants($combinationValue,$key,$selectedVariantsAttribute)){
                    throw new Exception('you are trying to corrupt the data');
                }

                $variantValue = $this->variantValueRepository->findOrFailVariantValueByCode(
                    $combinationValue['variant_value_code']
                );
                $combinationnName = $combinationnName . '-' . (trim(strtolower($variantValue['variant_value_name'])));
                $productVVCode = $productVVCode . '-' . $variantValue['variant_value_code'];
                array_push($variantValues, $combinationValue['variant_value_code']);
            }
            $variantData['combination_name'] = ltrim($combinationnName, '-');
            $variantData['product_vv_code'] = ltrim($productVVCode,'-');
            if(isset($combination['images'])){
                $variantData['images'] = $combination['images'];
            }
            $variantData['values'] = $variantValues;
            array_push($variantTotal, $variantData);
        }
        return $variantTotal;
    }



    public function updateProductVariant($product, $validatedProductVariant)
    {
        if( (int)request()->proceed_with_variants && ( !$product->hasVariants())){
            $checkDelete = $product->canDelete(
                'unitPackagingDetails',
                'productCollections',
                'carts',
                'storeOrderDetails',
                'warehouseProducts',
                'warehousePreOrderProducts',
                'warehousePurchaseOrderDetails'
            );
            if(!$checkDelete['can']){
                throw new Exception('Sorry Cannot perform the action since this product  is in active '. $checkDelete['relation']);
            }
            $this->productPriceRepository->forceDeleteProductPriceList($product);
        }


        if (isset($validatedProductVariant['edit_combinations'])) {
            try {
                $variantTotal = [];


                foreach ($validatedProductVariant['edit_combinations'] as $combination) {
                    $combinationnName = '';
                    $variantData = [];
                    $variantValues = [];
                    foreach ($combination['combination_values'] as $combinationValue) {
                        $variantValue = $this->variantValueRepository->findOrFailVariantValueByCode($combinationValue['variant_value_code']);
                        $combinationnName = $combinationnName . '-' . (trim(strtolower($variantValue['variant_value_name'])));

                        array_push($variantValues, $combinationValue['variant_value_code']);
                    }
                    $variantData['combination_name'] = ltrim($combinationnName, '-');

                    $variantData['values'] = $variantValues;
                    if(isset($combination['images'])){
                        $variantData['images'] = $combination['images'];
                    }

                    array_push($variantTotal, $variantData);
                }
                //dd($variantTotal);
                $this->productVariantRepository->updateProductVariant($product, $variantTotal);
            } catch (Exception $exception) {
                throw ($exception);
            }
        }
    }

    public function newUpdateProductVariant($product, $validatedProductVariant)
    {

        if( (int)$validatedProductVariant['proceed_with_variants'] && ( !$product->hasVariants())){
            $checkDelete = $product->canDelete(
                'unitPackagingDetails',
                'productCollections',
                'carts',
                'storeOrderDetails',
                'warehouseProducts',
                'warehousePreOrderProducts',
                'warehousePurchaseOrderDetails'
            );
            if(!$checkDelete['can']){
                throw new Exception('Sorry Cannot perform the action since this product  is in active '. $checkDelete['relation']);
            }
            $this->productPriceRepository->forceDeleteProductPriceList($product);
        }


        $filesToBeDeleted = [];
        $filesInsertedInBulImages = [];
        $filesInsertedInProductVariantImages = [];
        //dd($validatedProductVariant['variant_groups']);

        if (isset($validatedProductVariant['variant_groups'])) {
            try {
                $selectedVariantsAttribute = $validatedProductVariant['selected_attribute'];

               // dd($selectedVariantsAttribute);
                foreach($validatedProductVariant['variant_groups'] as $valueGroups) {
                    $productVariantGroupCode = '';

                    if($valueGroups['group_status']==='old_data'){
                        if(isset($valueGroups['product_variant_group_code'])){
                            $productVariantGroupCode = $valueGroups['product_variant_group_code'];
                            $productVariantGroup = $this->productVariantGroupRepository->findOrFailByProductVariantGroupCode($productVariantGroupCode);
                        }else{
                            throw new Exception('Product Variant Group Code Not Found For Old Group');
                        }
                    }

                    if($valueGroups['group_status'] ==='new_data'){
                        $groupsData['product_code'] =  $product->product_code;
                        $groupsData['group_name'] = $valueGroups['group_name'];
                        $groupsData['group_variant_value_code'] = $valueGroups['group_vv_code'];
                        $productVariantGroup = $this->productVariantGroupRepository->createOrUpdateProductVariantGroup($groupsData);
                        $productVariantGroupCode = $productVariantGroup->product_variant_group_code;
                    }

                    $variantTotal =  $this->updateGenerateVariantCombination($valueGroups['combinations'],$selectedVariantsAttribute);


                    if(isset($valueGroups['bulk_images']) && count($valueGroups['bulk_images'])>0){
                        $filesInsertedInBulImages =  $this->pvGroupBulkImageRepository->saveGroupBulkImages(
                            $valueGroups['bulk_images'],
                            $productVariantGroupCode
                        );
                    }

                    if($variantTotal){
                        $filesInsertedInProductVariantImages = $this->productVariantRepository
                            ->newUpdateProductVariant(
                                  $product,
                                  $variantTotal,
                                  $productVariantGroupCode
                            );
                    }
                }
            } catch (Exception $exception) {

                if($exception){
                    $filesToBeDeleted =  array_merge($filesInsertedInBulImages,$filesInsertedInProductVariantImages);
                      if($filesToBeDeleted){
                          foreach($filesToBeDeleted as $file){
                             $this->deleteImageFromServer($file['path'],$file['image']);
                          }
                      }
                }
                throw ($exception);
            }
        }
    }



    public function updateGenerateVariantCombination($variantsGroupsCombinations,$selectedVariantsAttribute){
        $variantTotal = [];
      //  dd($variantsGroupsCombinations,$selectedVariantsAttribute);
        foreach ($variantsGroupsCombinations as $combination) {


            if(count($combination['combination_values']) != count($selectedVariantsAttribute)){
                throw new Exception('you are trying to corrupt the data');
            }

                $combinationnName = '';
                $productVVCode = '';
                $variantData = [];
                $variantValues = [];
                foreach ($combination['combination_values'] as $key => $combinationValue) {
                    if(!ProductVariantHelper::checkVariantValueOfSameVariants($combinationValue,$key,$selectedVariantsAttribute)){
                        throw new Exception('you are trying to corrupt the data');
                    }
                    $variantValue = $this->variantValueRepository->findOrFailVariantValueByCode($combinationValue['variant_value_code']);
                    $combinationnName = $combinationnName . '-' . (trim(strtolower($variantValue['variant_value_name'])));
                    $productVVCode = $productVVCode . '-' . $variantValue['variant_value_code'];

                    array_push($variantValues, $combinationValue['variant_value_code']);
                }
                $variantData['combination_name'] = ltrim($combinationnName, '-');
                $variantData['product_vv_code'] = ltrim($productVVCode, '-');
                $variantData['values'] = $variantValues;
                $variantData['product_variant_code'] = (isset($combination['product_variant_code'])) ? $combination['product_variant_code'] : '';
                if (isset($combination['images'])) {
                    $variantData['images'] = $combination['images'];
                }

                array_push($variantTotal, $variantData);
        }
        return $variantTotal;
    }



    public function findOrFailVariantByProductCodeAndVariantCode($productCode,$variantCode){

        return $this->productVariantRepository->findOrFailByProductCodeAndVariantCode($productCode,$variantCode);
    }

    public function findOrFailVariantByProductCodeAndName($productCode,$variantName){

        return $this->productVariantRepository->findOrFailByProductCodeAndName($productCode,$variantName);
    }

    public function deleteProductVariantsByProduct($product)
    {
        $this->productVariantRepository->deleteProductVariantsByProduct($product);
    }

    public function forceDeleteProductVariantsByProduct($product)
    {
        $this->productVariantRepository->forceDeleteProductVariantsByProduct($product);
    }

    public function deleteProductVariantByVariantCode($variantCode)
    {
        $variant = $this->productVariantRepository->findProductVariantByVariantCode($variantCode);
        $this->productVariantRepository->deleteProductVariantByVariant($variant);
    }

    public function forceDeleteProductVariantByVariantCode($variantCode)
    {
        $variant = $this->productVariantRepository->findProductVariantByVariantCode($variantCode);
        $this->productVariantRepository->forceDeleteProductVariantByVariant($variant);
    }

    public function forceDeleteProductVariantByProductAndVariantCode($productCode,$variantCode)
    {
        $variant = $this->productVariantRepository->findProductVariantByVariantCode($variantCode);
        if(!$variant){
            throw new Exception('Variant does not belongs to this product');
        }
        $this->productVariantRepository->forceDeleteProductVariantByVariant($variant);
    }

    public function deleteProductVariantsByVariantValueCode($productCode, $variantCode){
        $this->productVariantRepository->deleteProductVariantsByVariantValueCode($productCode, $variantCode);
    }

    public function forceDeleteProductVariantsByVariantValueCode($productCode, $variantCode){
        $this->productVariantRepository->forceDeleteProductVariantsByVariantValueCode($productCode, $variantCode);
    }

    public function deleteProductVariantImageBycode($imageCode){
        $this->productVariantRepository->deleteProductVariantImageBycode($imageCode);
    }

    public function forceDeleteProductVariantImageBycode($productCode,$productVariantCode,$imageCode){
        $this->productVariantRepository->forceDeleteProductVariantImageBycode(
            $productCode,
            $productVariantCode,
            $imageCode
        );
    }

    public function getProductVariantByProductCode($productCode,$select)
    {
        return $this->productVariantRepository->getProductVariantByProductCode($productCode,$select);
    }
}
