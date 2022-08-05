<?php

namespace App\Modules\Vendor\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Modules\ActivityLog\Helpers\LogActivity;
use App\Modules\Product\Requests\Product\ProductImageRequest;
use App\Modules\Product\Requests\Product\ProductImageUpdateRequest;
use App\Modules\Product\Requests\Product\ProductPackageRequest;
use App\Modules\Product\Requests\Product\ProductRequest;
use App\Modules\Product\Requests\Product\ProductVariantRequest;
use App\Modules\Product\Requests\Product\ProductVariantUpdateRequest;
use App\Modules\Product\Requests\ProductWarranty\ProductWarrantyDetailRequest;
use App\Modules\Product\Resources\MinimalProductResource;
use App\Modules\Product\Resources\MinimalProductWithVariantResource;
use App\Modules\Product\Resources\ProductListCollection;
use App\Modules\Product\Resources\ProductVariantResource;
use App\Modules\Product\Resources\SingleProductEditResource;
use App\Modules\Product\Resources\SingleProductResource;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductImageService;
use App\Modules\Product\Services\ProductPackageService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Product\Services\ProductWarranty\ProductWarrantyDetailService;
use App\Modules\Vendor\Helpers\VendorWiseProductFilter;
use App\Modules\Vendor\Services\VendorProductService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorProductController extends Controller
{

    private $productService;
    private $productPackageService;
    private $productImageService;
    private $productVariantService;
    private $productCategoryService;
    private $productWarrantyDetailService;
    private $vendorProductService;

    public function __construct(
        ProductService $productService,
        ProductPackageService $productPackageService,
        ProductImageService $productImageService,
        ProductVariantService $productVariantService,
        ProductCategoryService $productCategoryService,
        ProductWarrantyDetailService $productWarrantyDetailService,
        VendorProductService $vendorProductService
    )
    {
        $this->productService = $productService;
        $this->productPackageService = $productPackageService;
        $this->productImageService = $productImageService;
        $this->productVariantService = $productVariantService;
        $this->productCategoryService = $productCategoryService;
        $this->productWarrantyDetailService = $productWarrantyDetailService;
        $this->vendorProductService = $vendorProductService;
    }

    public function index(Request $request)
    {

        try {
            $productName = $request->get('product_name');
            $categoryName = $request->get('category_name');
            $brandName = $request->get('brand_name');
            $packageType = $request->get('package_type');
            $recordPerPage = $request->get('records_per_page');
            $hasPrice = $request->get('has_price');
            $isTaxable = $request->get('is_taxable');
            $isActive = $request->get('is_active');

            $globalSearchKeyword = $request->get('search');

            $filterParameters = [
                'vendor_code' => getAuthVendorCode(),
                'product_name' => $productName,
                'category_name' => $categoryName,
                'brand_name' => $brandName,
                'package_type' => $packageType,
                'global_search_keyword' => $globalSearchKeyword,
                'records_per_page' => $recordPerPage,
                'has_price' => $hasPrice,
                'is_taxable' => $isTaxable,
                'is_active' => $isActive
            ];


            //$vendorProducts =  VendorWiseProductFilter::apply($request,10);
            $vendorProducts = VendorWiseProductFilter::filterPaginatedVendorProducts($filterParameters, 10);
            return new ProductListCollection($vendorProducts);
            //  $products = $this->productService->getProductsByVendor(getAuthVendorCode());
            //return ProductListResource::collection($products);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }

    }

    public function oldStore(
        // Request $request,
        ProductRequest $productRequest,
       // ProductPackageRequest $packageRequest,
        ProductVariantRequest $productVariantRequest,
        ProductImageRequest $imageRequest,
        ProductWarrantyDetailRequest $productWarrantyDetailRequest
    )
    {

        $validatedProduct = $productRequest->validated();
        //$validatedPackage = $packageRequest->validated();
        $validatedProductVariant = $productVariantRequest->validated();
//        dd($validatedProductVariant);
        $validatedImage = $imageRequest->validated();
        $validatedProductWarrantyDetail = $productWarrantyDetailRequest->validated();
        DB::beginTransaction();
        try {
            $product = $this->productService->storeProduct($validatedProduct);
            $this->productWarrantyDetailService->storeProductWarrantyDetail($product, $validatedProductWarrantyDetail);
            $this->productCategoryService->syncProductCategories($product, $validatedProduct['category_code']);
          //  $this->productPackageService->storeProductPackageDetail($product, $validatedPackage);
            $this->productImageService->storeProductImages($product, $validatedImage);

            if (isset($validatedProductVariant['combinations'])) {
                $this->productVariantService->storeProductVariant($product, $validatedProductVariant);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
        return sendSuccessResponse('Product Created', $product);
    }

    public function Store(Request $request,
        ProductRequest $productRequest,
        ProductVariantRequest $productVariantRequest,
        ProductImageRequest $imageRequest,
        ProductWarrantyDetailRequest $productWarrantyDetailRequest
    )
    {
       // dd($request->all());
        //dd($productVariantRequest->validated(),$imageRequest->validated());
        $validatedProduct = $productRequest->validated();
        $validatedProductVariant = $productVariantRequest->validated();
        $validatedImage = $imageRequest->validated();
        $validatedProductWarrantyDetail = $productWarrantyDetailRequest->validated();

       // dd($validatedProduct,$validatedProductVariant,$validatedImage,$validatedProductWarrantyDetail);
        DB::beginTransaction();
        try {
            $product = $this->productService->storeProduct($validatedProduct);
            $this->productWarrantyDetailService->storeProductWarrantyDetail($product, $validatedProductWarrantyDetail);
            $this->productCategoryService->syncProductCategories($product, $validatedProduct['category_code']);
            $this->productImageService->storeProductImages($product, $validatedImage);
             //dd($validatedProductVariant['variant_groups']);

           // dd($validatedProductVariant['variant_groups']);
            if (isset($validatedProductVariant['variant_groups'])) {
                $this->productVariantService->newStoreProductVariant($product, $validatedProductVariant);
            }

            DB::commit();
            $productResource = new MinimalProductWithVariantResource($product);
            LogActivity::addToLog(auth()->user()->name." Add New Products",[]);
            return sendSuccessResponse('Product Created', $productResource);
        } catch (Exception $exception) {
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }

    }

    public function show($productCode)
    {
        try {
            $with = [
                'variantGroupDetails:product_variant_group_code,product_code,group_name,group_variant_value_code',
                'variantGroupDetails.variantGroupBulkImages:pv_group_bulk_image_code,product_variant_group_code,image',
                'variantGroupDetails.groupProductVariants:product_variant_code,product_variant_group_code,product_variant_name',
                'variantGroupDetails.groupProductVariants.details:product_variant_detail_code,product_variant_code,variant_value_code',
                'variantGroupDetails.groupProductVariants.details.variantValue:variant_value_name,variant_value_code,variant_code,slug',
                'variantGroupDetails.groupProductVariants.details.variantValue.variant:variant_name,variant_code,slug',
            ];
            $vendorProduct = $this->vendorProductService->getProductOfVendor($productCode, getAuthVendorCode(),$with);


          //  dd($vendorProduct);
            $product = new SingleProductEditResource($vendorProduct);
            return sendSuccessResponse('Data Found', $product);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function oldUpdate(
        ProductRequest $productRequest,
        //ProductPackageRequest $packageRequest,
        ProductVariantUpdateRequest $productVariantRequest,
        ProductImageUpdateRequest $imageRequest,
        ProductWarrantyDetailRequest $productWarrantyDetailRequest,
        $productCode
    )
    {

        DB::beginTransaction();
        $validatedProduct = $productRequest->validated();
        //$validatedPackage = $packageRequest->validated();
        $validatedProductVariant = $productVariantRequest->validated();
        $validatedImage = $imageRequest->validated();
        $validatedProductWarrantyDetail = $productWarrantyDetailRequest->validated();

        try {

            $product = $this->vendorProductService->getProductOfVendor($productCode, getAuthVendorCode());
            $product = $this->productService->updateProduct($product, $validatedProduct);
            $this->productWarrantyDetailService->storeProductWarrantyDetail($product, $validatedProductWarrantyDetail);
            $this->productCategoryService->syncProductCategories($product, $validatedProduct['category_code']);
           // $this->productPackageService->updateProductPackageDetail($product, $validatedPackage);
            $this->productImageService->updateProductImages($product, $validatedImage);
            if (isset($validatedProductVariant['edit_combinations'])) {
                $this->productVariantService->updateProductVariant($product, $validatedProductVariant);
            }
            DB::commit();
            return sendSuccessResponse('Product Updated Successfully', $product);
        } catch (Exception $exception) {
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function update(
        ProductRequest $productRequest,
        //ProductPackageRequest $packageRequest,
        ProductVariantUpdateRequest $productVariantRequest,
        ProductImageUpdateRequest $imageRequest,
        ProductWarrantyDetailRequest $productWarrantyDetailRequest,
        $productCode
    ){

       // dd(112);

        DB::beginTransaction();
        $validatedProduct = $productRequest->validated();

        $validatedProductVariant = $productVariantRequest->validated();
        $validatedProductVariant['proceed_with_variants'] = $validatedProduct['proceed_with_variants'];
        //dd($validatedProductVariant);
        $validatedImage = $imageRequest->validated();
        $validatedProductWarrantyDetail = $productWarrantyDetailRequest->validated();

        try {

         //   dd($validatedImage);
            $product = $this->vendorProductService->getProductOfVendor($productCode, getAuthVendorCode());
            $product = $this->productService->updateProduct($product, $validatedProduct);
            $this->productWarrantyDetailService->storeProductWarrantyDetail($product, $validatedProductWarrantyDetail);
            $this->productCategoryService->syncProductCategories($product, $validatedProduct['category_code']);
            // $this->productPackageService->updateProductPackageDetail($product, $validatedPackage);
           // dd(222);
            $this->productImageService->updateProductImages($product, $validatedImage);

           // dd('hgds');
            if (isset($validatedProductVariant['variant_groups'])) {
                $this->productVariantService->newUpdateProductVariant($product, $validatedProductVariant);
            }

            DB::commit();
            return sendSuccessResponse('Product Updated Successfully', $product);
        } catch (Exception $exception) {
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function destroy($productCode)
    {
        DB::beginTransaction();
        try {
            $product = $this->vendorProductService->getProductOfVendor($productCode, getAuthVendorCode());
            //throw new Exception('Product Deletion Halted For Now !');
            $product = $this->productService->deleteProduct($product);
            DB::commit();
            return sendSuccessResponse('Product Deleted Successfully', $product);
        } catch (Exception $exception) {
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


    public function toggleProductTaxability($productCode)
    {
        DB::beginTransaction();
        try {
            $product = $this->vendorProductService->changeVendorProductTaxability($productCode, getAuthVendorCode());
            DB::commit();
            return sendSuccessResponse('Taxability of product changed successfully', $product);
        } catch (Exception $exception) {
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function toggleProductActivation($productCode)
    {
        DB::beginTransaction();
        try {
            $product = $this->vendorProductService->changeVendorProductActivation($productCode, getAuthVendorCode());
            DB::commit();
            return sendSuccessResponse('Activation of product changed successfully', $product);
        } catch (Exception $exception) {
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}
