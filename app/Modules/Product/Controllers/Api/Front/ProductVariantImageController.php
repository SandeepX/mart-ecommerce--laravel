<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Vendor\Services\VendorProductService;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductVariantImageController extends Controller{
    private $productVariantService;
    private $vendorProductService;
    public function __construct(
        ProductVariantService $productVariantService,
        VendorProductService $vendorProductService
    )
    {
        $this->productVariantService = $productVariantService;
        $this->vendorProductService = $vendorProductService;
    }
    public function destroy($productCode,$productVariantCode,$productImageCode){
        DB::beginTransaction();
        try{
            $product = $this->vendorProductService->getProductOfVendor($productCode,getAuthVendorCode());
            $this->productVariantService->forceDeleteProductVariantImageBycode(
                $product->product_code,
                $productVariantCode,
                $productImageCode
            );
            DB::commit();
            return sendSuccessResponse('Product Variant Image Deleted Successfully!');

        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
