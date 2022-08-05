<?php

namespace App\Modules\Vendor\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Resources\ProductListResource;
use App\Modules\Product\Services\ProductService;
use Exception;
use Illuminate\Http\Request;

class VendorProductController extends BaseController
{
    private $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request, $vendorCode){
        try{
          $products = $this->productService->getProductsByVendor($vendorCode);

          if($request->expectsJson()){
            return ProductListResource::collection($products);
          }
        }catch(Exception $exception){
          return sendErrorResponse($exception->getMessage(), 400);
        }    
    }
}
