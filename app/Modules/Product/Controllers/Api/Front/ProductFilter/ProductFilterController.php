<?php

namespace App\Modules\Product\Controllers\Api\Front\ProductFilter;

use App\Http\Controllers\Controller;
use App\Modules\Product\Helpers\ProductFilter;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Services\ProductService;
use App\Modules\Vendor\Helpers\VendorWiseProductFilter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductFilterController extends Controller
{

    private $productService;
    public  function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function filterProductsOfVendor(Request $request,$vendorCode)
    {
        $filterParameters =[
            'vendor_code' =>  $vendorCode,
            'category_codes' => convertToArray(!$request->filled('category_codes') ? [] : array_filter($request->category_codes)),
            'brand_codes'=> convertToArray(!$request->filled('brand_codes') ? [] : array_filter($request->brand_codes)),
            'global_search_keyword'=>$request->search
        ];

        $products = VendorWiseProductFilter::filterPaginatedVendorProducts($filterParameters,10);


        if($request->expectsJson()){
            return response()->json($products);
        }
        return $products;
    }


    public function getProductVariantsOfProduct($productCode)
    {
       
        $productVariants = [];
        $product = $this->productService->findOrFailProductByCode($productCode);
        if($product->hasVariants()){
            $productVariants = $product->productVariants()->select('product_variant_code','product_variant_name')->get();
        }
        return response()->json([
            'variant_tag' => $product->hasVariants(),
            'product_variants' => $productVariants

        ]);
    }
}
