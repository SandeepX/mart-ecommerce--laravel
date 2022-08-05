<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Product\Services\ProductImageService;
use App\Modules\Vendor\Services\VendorProductService;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductImageController extends Controller{
    private $productImageService;
    private $vendorProductService;
    public function __construct(
        ProductImageService $productImageService,
        VendorProductService $vendorProductService
    )
    {
        $this->productImageService = $productImageService;
        $this->vendorProductService = $vendorProductService;
    }
    public function destroy($productCode,$productImageCode){

        try{
            $product = $this->vendorProductService->getProductOfVendor($productCode,getAuthVendorCode());
            $productImages = $this->productImageService->getProductFeaturedImagesByProductCode($productCode);
            if(!(count($productImages) > 1)){
               throw new Exception('Product featured images should be at least 1. Try uploading another image and deleting again');
            }
            DB::beginTransaction();
            $this->productImageService->forceDeleteProductImageBycode($productImageCode);
            DB::commit();
            return sendSuccessResponse('Product Image Deleted Successfully!');

        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
