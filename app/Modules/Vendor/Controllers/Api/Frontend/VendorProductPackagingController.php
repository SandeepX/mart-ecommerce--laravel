<?php


namespace App\Modules\Vendor\Controllers\Api\Frontend;


use App\Http\Controllers\Controller;
use App\Modules\Package\Resources\PackageTypeResource;
use App\Modules\Package\Services\PackageTypeService;
use App\Modules\Product\Models\ProductMaster;

use App\Modules\Product\Resources\ProductVariantPackagingResource;
use App\Modules\Vendor\Requests\VendorProductPackagingRequest;
use App\Modules\Vendor\Services\VendorProductPackagingService;
use Exception;

class VendorProductPackagingController extends Controller
{
    private $vendorProductPackagingService,$packageTypeService;

    public function __construct(VendorProductPackagingService $vendorProductPackagingService,
                                PackageTypeService $packageTypeService){
        $this->vendorProductPackagingService = $vendorProductPackagingService;
        $this->packageTypeService = $packageTypeService;
    }

    public function getProductPackagingConfiguration($productCode){
        try{
           $packagingDetails = $this->vendorProductPackagingService->getProductPackagingDetail($productCode);
           $packageTypes = $this->packageTypeService->getAllPackageTypes();
            $packagingDetails = ProductVariantPackagingResource::collection($packagingDetails);
            $packageTypes = PackageTypeResource::collection($packageTypes);

            $data =[
              'packaging_details' =>$packagingDetails,
              'package_types' =>$packageTypes,
            ];
            return sendSuccessResponse('Data Found!', $data);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function configureProductPackaging(
        VendorProductPackagingRequest $request,$productCode){
        try{
            $validated= $request->validated();
            $this->vendorProductPackagingService->saveProductPackagingDetail($validated,$productCode);
            return sendSuccessResponse('Product packaging configured successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function deleteProductPackaging($productPackagingDetailCode){
        try{
            $this->vendorProductPackagingService->deleteProductPackagingDetail($productPackagingDetailCode);
            return sendSuccessResponse('Product packaging deleted successfully');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
