<?php


namespace App\Modules\Product\Controllers\Api\Warehouse;


use App\Http\Controllers\Controller;
use App\Modules\Vendor\Helpers\VendorWiseProductFilter;
use Illuminate\Http\Request;

use Exception;

class ProductFilterControllerApi extends Controller
{
    public function filterProductsOfVendor(Request $request)
    {
        try{
          /*  if (!$request->vendor){
                throw new Exception('Vendor is required',400);
            }*/
            $filterParameters =[
                'vendor_code' =>  $request->vendor,
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
                return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.products-list-tbl',
                    compact('products'))->render();
            }
            return $products;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }
}
