<?php

namespace App\Modules\Product\Services\ProductVariantGroup;



use App\Modules\Product\Models\PVGroupBulkImage;
use App\Modules\Product\Repositories\ProductVariantGroup\ProductVariantGroupRepository;
use App\Modules\Product\Repositories\ProductVariantGroup\PVGroupBulkImageRepository;
use App\Modules\Product\Services\ProductVariantService;
use Exception;

class ProductVariantGroupService
{
    private $productVariantGroupRepository;
    private $pvGroupBulkImageRepository;
    private $productVariantService;

    public function __construct(
        ProductVariantGroupRepository $productVariantGroupRepository,
        PVGroupBulkImageRepository $pvGroupBulkImageRepository,
        ProductVariantService $productVariantService
        )
    {
      $this->productVariantGroupRepository =$productVariantGroupRepository;
      $this->pvGroupBulkImageRepository = $pvGroupBulkImageRepository;
      $this->productVariantService = $productVariantService;
    }

    public function getGroupByProductandGroupCode($productCode,$productVariantGroupCode){
        $productVariantGroup = $this->productVariantGroupRepository->getGroupByProductandGroupCode($productCode,$productVariantGroupCode);
        if(!$productVariantGroup){
            throw new Exception('This Group is not associated with given Product');
        }
        return $productVariantGroup;
    }



    public function deleteProductVariantGroup($productCode,$productVariantGroupCode){

       try{
           $productVariantGroup = $this->productVariantGroupRepository->getGroupByProductandGroupCode(
                                           $productCode,
                                           $productVariantGroupCode
                                   );

           if(!$productVariantGroup){
               throw new Exception('Group does not belongs to this Product');
           }

            // variants of that group
           $productVariantofGroup = $this->productVariantGroupRepository->getProductVariantsByGroupCode($productVariantGroupCode);
           if($productVariantofGroup){
               foreach ($productVariantofGroup as $productVariant){
                  $this->productVariantService->forceDeleteProductVariantByVariantCode($productVariant->product_variant_code);
               }
           }
           // bulk images of group
           $groupBulkImages = $this->pvGroupBulkImageRepository->getGroupBulkImagesByGroupCode($productVariantGroupCode);

           if($groupBulkImages){
               foreach ($groupBulkImages as $groupBulkImage){
                   $this->destoyGroupBulkImage($groupBulkImage);
               }
           }
           //destroy Product Variant Group
          return  $this->productVariantGroupRepository->forceDestroy($productVariantGroup);

       }catch (Exception $exception){
           throw ($exception);
       }
    }

    public function destoyGroupBulkImage($groupBulkImage){
        $this->pvGroupBulkImageRepository->destroy($groupBulkImage);
    }

    public function findGroupBulkImageByImageCode($groupBulkImageCode){
      return  $this->pvGroupBulkImageRepository->findGroupBulkImageByImageCode($groupBulkImageCode);
    }



}
