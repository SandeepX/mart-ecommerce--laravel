<?php


namespace App\Modules\Vendor\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Helpers\ProductFilter;
use App\Modules\Product\Services\ProductService;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\Request;

class VendorCompleteDetailController extends BaseController
{
    public $title = 'Vendor Details';
    public $base_route = 'admin.vendors';
    public $sub_icon = 'file';
    public $module = 'Vendor::';
    public $view ='admin.vendor-complete-detail.';

    public $vendorService;
    public $productService;

    public function __construct(VendorService $vendorService,ProductService $productService)
    {
        $this->vendorService = $vendorService;
        $this->productService = $productService;
    }

    public function getVendorCompleteDetail($vendorCode)
    {
        $vendor = $this->vendorService->findOrFailVendorByCode($vendorCode);
        return view(Parent::loadViewData($this->module . $this->view . 'complete-detail'),
            compact('vendorCode',
                'vendor'
            )
        );
    }

    public function getVendorGeneralDetail($vendorCode)
    {
        try {
            $response = [];
            $vendor = $this->vendorService->findOrFailVendorByCode($vendorCode);
            $vendorDocument = $vendor->documents()->get();
            $response['html'] = view($this->module . $this->view . 'layout.partials.general-detail.show',
                compact('vendor','vendorDocument'
                )
            )->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getAllVendorProducts(Request $request,$vendorCode)
    {
        try{
            $filterParameters = [
                'vendor_code' =>  $vendorCode,
                'product_name' =>  $request->get('product_name'),
            ];
            $with=[
                'package.packageType',
                'vendor',
                'brand',
                'category',
                'priceList'

            ];
            $response = [];
            $this->vendorService->findOrFailVendorByCode($vendorCode);
            $products = ProductFilter::filterPaginatedProducts($filterParameters,20,$with);
            $response['html'] = view($this->module . $this->view . 'layout.partials.vendor-product.show',
                compact('products','filterParameters','vendorCode')
            )->render();
            return response()->json($response);
        }catch (\Exception $exception){
           return $exception->getMessage();
        }
    }

    public function toggleVendorProductStatus($productCode)
    {
        try{
           $vendorProductStatus = $this->productService->updateActiveStatus($productCode);
           return response()->json($vendorProductStatus,200);
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(),400);
        }
    }

}
