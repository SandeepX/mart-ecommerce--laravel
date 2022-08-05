<?php

namespace App\Modules\Vendor\Controllers\Api\Frontend;

use App\Exceptions\Custom\PermissionDeniedException;
use App\Http\Controllers\Controller;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Vendor\Requests\ProductPriceRequest;
use App\Modules\Vendor\Resources\ProductPrice\ProductPriceListResource;
use App\Modules\Vendor\Services\VendorProductPriceService;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductPriceController extends Controller
{
    private $productService;
    private $productVariantService;
    private $vendorProductPriceService;
    public function __construct(ProductService $productService,
                                ProductVariantService $productVariantService,
                                VendorProductPriceService $vendorProductPriceService

    )
    {
        $this->vendorProductPriceService = $vendorProductPriceService;
        $this->productService = $productService;
        $this->productVariantService = $productVariantService;
    }

    public function getProductPrice($productCode)
    {
        $product = $this->productService->findOrFailProductByCode($productCode);
        if($product->vendor_code !== auth()->user()->vendor->vendor_code){
            throw new PermissionDeniedException();
        }
        $priceLists = $this->vendorProductPriceService->getProductPrice($product);


      //  dd($productPackagingDetails);
        $priceLists = $priceLists->map(function($priceList){
            $with=['microPackageType', 'unitPackageType','macroPackageType','superPackageType'];
            $productPackagingDetail = ProductUnitPackageDetail::with($with)->where('product_code',$priceList->product_code)
                ->where('product_variant_code',$priceList->product_variant_code)->first();
            if (!$productPackagingDetail){
                throw new Exception('Product packaging details not found for product '. $priceList->product_code);
            }
            if ($productPackagingDetail->macro_to_super_value){
                $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                $priceList['mrp'] = $priceList['mrp'] *$microValue;
                $priceList['packaging_description'] = '1 ' . $productPackagingDetail->superPackageType->package_name . ' consists ' .
                    $productPackagingDetail->micro_to_unit_value *
                    $productPackagingDetail->unit_to_macro_value *
                    $productPackagingDetail->macro_to_super_value . ' ' .
                    $productPackagingDetail->microPackageType->package_name;

            }elseif ($productPackagingDetail->unit_to_macro_value){
                $priceList['mrp'] = $priceList['mrp'] *($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
                $priceList['packaging_description'] = '1 ' . $productPackagingDetail->macroPackageType->package_name . ' consists ' .
                    $productPackagingDetail->micro_to_unit_value *
                    $productPackagingDetail->unit_to_macro_value . ' ' .
                    $productPackagingDetail->microPackageType->package_name;
            }
            elseif ($productPackagingDetail->micro_to_unit_value){
                $priceList['mrp'] = $priceList['mrp'] *$productPackagingDetail ->micro_to_unit_value;
                $priceList['packaging_description'] = '1 ' . $productPackagingDetail->unitPackageType->package_name . ' consists ' .
                    $productPackagingDetail->micro_to_unit_value . ' ' .
                    $productPackagingDetail->microPackageType->package_name;
            }else{
                $priceList['mrp'] = $priceList['mrp'];
                $priceList['packaging_description'] = '';
            }
            return $priceList;
        });
       // dd($priceLists);
        $priceLists = ProductPriceListResource::collection($priceLists);
//        $response = [
//            'error'   => false,
//            'message' => $message,
//            'message' => $message,
//            'code'    => 200
//        ];
//        dd($priceLists);
        return sendSuccessResponse('Data Found!', $priceLists);
    }

    public function storeProductPrice(ProductPriceRequest $productPriceRequest, $productCode)
    {

//        dd('111');
        $validatedProductPrice = $productPriceRequest->validated();
        DB::beginTransaction();
        try{
           // dd($validatedProductPrice);
            //get packaging detail of product

            //dd(array_filter($validatedProductPrice['mrp']));

            foreach(array_filter($validatedProductPrice['mrp']) as $key => $mrp){

               $productVariantCode= isset($validatedProductPrice['product_variant_code'][$key])?$validatedProductPrice['product_variant_code'][$key] : null;
               $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                   ->where('product_variant_code',$productVariantCode)->first();
               if (!$productPackagingDetail){
                   throw new Exception('Product packaging detail not found for product '. $productCode);
               }
                //// checking for negative value
                $marginValues = [];
                $marginValues = [
                    'admin_margin_type' => $validatedProductPrice['admin_margin_type'][$key],
                    'admin_margin_value' => $validatedProductPrice['admin_margin_value'][$key] ,
                    'wholesale_margin_type' => $validatedProductPrice['wholesale_margin_type'][$key],
                    'wholesale_margin_value' => $validatedProductPrice['wholesale_margin_value'][$key],
                    'retail_store_margin_type' => $validatedProductPrice['retail_store_margin_type'][$key],
                    'retail_store_margin_value' => $validatedProductPrice['retail_store_margin_value'][$key]
                ];

                $productName =  $this->productService->findOrFailProductByCode($productCode)
                    ->product_name;
                if($validatedProductPrice['product_variant_code'][$key]){
                    $variantName = $this->productVariantService->findOrFailVariantByProductCodeAndVariantCode(
                       $productCode, $validatedProductPrice['product_variant_code'][$key]
                    )->product_variant_name;
                }

                if(!ProductPriceHelper::checkNegativeProductPrice($mrp,$marginValues)){
                    throw new Exception('Margin Value For Product: '.$productName.'
                    '.( isset($variantName) ? $variantName : '') .' exceeds than MRP. Cannot add Negative Price');
                }

                // ends here

               if ($productPackagingDetail->macro_to_super_value){
                   $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                   $validatedProductPrice['mrp'][$key] = $validatedProductPrice['mrp'][$key] /$microValue;
               }elseif ($productPackagingDetail->unit_to_macro_value){
                   $validatedProductPrice['mrp'][$key] = $validatedProductPrice['mrp'][$key] /($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
               }
               elseif ($productPackagingDetail->micro_to_unit_value){
                   $validatedProductPrice['mrp'][$key] = $validatedProductPrice['mrp'][$key] /$productPackagingDetail ->micro_to_unit_value;
               }else{
                   $validatedProductPrice['mrp'][$key] = $validatedProductPrice['mrp'][$key];
               }
            }

            $this->vendorProductPriceService->storeProductPrice($validatedProductPrice, $productCode);
            DB::commit();
            return sendSuccessResponse('Price Added To Products Successfully');
        }catch(Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
