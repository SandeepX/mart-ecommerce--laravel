<?php

namespace App\Modules\Product\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Vendor\Services\VendorProductService;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller{
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

    public function destroy($productCode,$variantCode){
        DB::beginTransaction();
        try{
             $product =  $this->vendorProductService->getProductOfVendor($productCode,getAuthVendorCode());
            $this->productVariantService->forceDeleteProductVariantByProductAndVariantCode($productCode,$variantCode);
            DB::commit();
            return sendSuccessResponse('Product Variant Deleted Successfully!');

        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function destroyProductVariants($productCode){
        DB::beginTransaction();
        try{

            $product = $this->vendorProductService->getProductOfVendor($productCode,getAuthVendorCode());
            $this->productVariantService->forceDeleteProductVariantsByProduct($product);
            DB::commit();
            return sendSuccessResponse('Product Variant Deleted Successfully!');

        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function destroyProductVariantsByVariantValue($productCode, $variantValueCode){
        DB::beginTransaction();
        try{
            $product = $this->vendorProductService->getProductOfVendor($productCode,getAuthVendorCode());
            $this->productVariantService->forceDeleteProductVariantsByVariantValueCode($productCode, $variantValueCode);
            DB::commit();
            return sendSuccessResponse('Poduct Variants Deleted Successfully!');

        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}
