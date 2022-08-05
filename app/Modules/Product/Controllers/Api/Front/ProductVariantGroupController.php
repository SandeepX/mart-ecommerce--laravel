<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Product\Services\ProductVariantGroup\ProductVariantGroupService;
use App\Modules\Vendor\Services\VendorProductService;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductVariantGroupController extends Controller
{

    private $productVariantGroupService;
    private $vendorProductService;

    public function __construct(
        ProductVariantGroupService $productVariantGroupService,
        VendorProductService $vendorProductService
    )
    {
        $this->productVariantGroupService = $productVariantGroupService;
        $this->vendorProductService = $vendorProductService;
    }


    public function deleteProductVariantGroup($productCode,$productVariantGroupCode){
        try{
            DB::beginTransaction();
            $product = $this->vendorProductService->getProductOfVendor($productCode,getAuthVendorCode());
            $this->productVariantGroupService->deleteProductVariantGroup($productCode,$productVariantGroupCode);
            //$this->productVariantGroupService->destoyGroupBulkImage($groupBulkImage);
            DB::commit();
          return  sendSuccessResponse('Product Variant Group Deleted Sucessfully');
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage().' try deleting individual variants.', 400);
        }

    }

    public function deleteProductVariantGroupBulkImage($productCode,$productVariantGroupCode,$groupBulkImageCode){

        try{
            DB::beginTransaction();
              $this->vendorProductService->getProductOfVendor($productCode,getAuthVendorCode());
              $this->productVariantGroupService->getGroupByProductandGroupCode($productCode,$productVariantGroupCode);
              $groupBulkImage = $this->productVariantGroupService->findGroupBulkImageByImageCode($groupBulkImageCode);
              $this->productVariantGroupService->destoyGroupBulkImage($groupBulkImage);
            DB::commit();
            return sendSuccessResponse('Bulk Image Deleted Successfully from this group!');
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(),400);
        }

    }

}
