<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/6/2020
 * Time: 1:48 PM
 */

namespace App\Modules\Product\Controllers\Api\Admin;


use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Services\ProductService;
use App\Modules\Vendor\Helpers\VendorWiseProductFilter;
use Illuminate\Http\Request;
use Exception;

class ProductFilterControllerApi
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function filterProductsOfVendor(Request $request)
    {
        try{
            ///dd($request->all());
            if (!$request->vendor_code){
                throw new Exception('Vendor is required',400);
            }
            $filterParameters =[
                'vendor_code' =>  $request->vendor_code,
                //'category_codes' => convertToArray(!$request->filled('category_codes') ? [] : array_filter($request->category_codes)),
                //'brand_codes'=> convertToArray(!$request->filled('brand_codes') ? [] : array_filter($request->brand_codes)),
                'category_codes' => array_filter(convertToArray($request->category_codes)),
                'brand_codes' => array_filter(convertToArray($request->brand_code)),
                'product_name'=> $request->product_name
            ];

            $products = VendorWiseProductFilter::filterPaginatedVendorQualifiedProducts($filterParameters,10);

            /*if($request->expectsJson()){
                return response()->json($products);
            }*/

            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-purchase-orders.form_partials.products-tbl',
                    compact('products'))->render();
            }
            return $products;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function getProductVariantsOfProduct($productCode)
    {
        try{
            $productVariants = [];
            $product = $this->productService->findOrFailProductByCodeWith($productCode,['productVariants']);
            $productCode = $product->product_code;
            $productPriceHelper = new ProductPriceHelper();
            if($product->hasVariants()){
                $productPrice =null;
                $productPackagingTypes=null;
                $productVariants = $product->productVariants()->select('product_variant_code','product_variant_name')->get();
                $productVariants = $productVariants->map(function ($productVariant) use ($productPriceHelper,$productCode){
                    return [
                        'product_variant_code'=>$productVariant['product_variant_code'],
                        'product_variant_name'=>$productVariant['product_variant_name'],
                        'price'=>$productPriceHelper->getProductWarehousePrice($productCode,$productVariant['product_variant_code']),
                       /* 'product_packaging_types' =>ProductUnitPackagingHelper::getAvailableProductPackagingTypes(
                            $productCode,$productVariant['product_variant_code'])*/
                    ];
                });
            }
            else{
                $productPrice= $productPriceHelper->getProductWarehousePrice($productCode);
                $productPackagingTypes = ProductUnitPackagingHelper::getAvailableProductPackagingTypes(
                    $productCode);
            }
            return response()->json([
                'product_name' => $product->product_name,
                'product_code' => $product->product_code,
                'has_variants' => $product->hasVariants(),
                'product_variants' => $productVariants,
                'price' => round($productPrice,2),
                'product_packaging_types' => $productPackagingTypes

            ]);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function getAvailableProductPackagingTypes($productCode,$productVariantCode){

        try{
            return ProductUnitPackagingHelper::getAvailableProductPackagingTypes(
                $productCode,$productVariantCode);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }
}
