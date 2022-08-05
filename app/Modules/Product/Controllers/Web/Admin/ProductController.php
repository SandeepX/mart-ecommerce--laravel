<?php

namespace App\Modules\Product\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Helpers\ProductFilter;
use App\Modules\Product\Helpers\ProductVariantHelper;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Services\ProductService;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\Request;

use Exception;

class ProductController extends BaseController
{
    public $title = 'Products';
    public $base_route = 'admin.products';
    public $sub_icon = 'file';
    public $module = 'Product::';

    private $view;
    private $productService;
    private $vendorService;
    public function __construct(ProductService $productService, VendorService $vendorService)
    {
        $this->middleware('permission:View Product List', ['only' => ['index']]);
        $this->middleware('permission:Show Product', ['only' => ['show']]);
        $this->middleware('permission:Update Product Status', ['only' => ['toggleStatus']]);


        $this->view = 'admin.product.';
        $this->productService = $productService;
        $this->vendorService = $vendorService;

    }

    public function index(Request $request){


        try{

            $filterParameters = [
                'vendor_code' =>  $request->get('vendor_code'),
                'product_name' =>  $request->get('product_name'),

            ];

            $with=[
                'package.packageType',
                'vendor',
                'brand',
                'category',
                'priceList'

            ];
            $products = ProductFilter::filterPaginatedProducts($filterParameters,25,$with);

            //$products = $this->productService->filterProductByVendor($request->filter_by);
            $vendors = $this->vendorService->getAllVendors();
            return view($this->loadViewData($this->module.$this->view.'index'),compact('products', 'vendors','filterParameters'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }

    }

    public function show($productCode){

        $product = $this->productService->findOrFailProductByCode($productCode);
        return view($this->loadViewData($this->module.$this->view.'show'),compact('product'));

    }

    public function productsByVendor($vendorCode){
        $products = $this->productService->getProductsByVendor($vendorCode);
        $vendors = $this->vendorService->getAllVendors();
        return view($this->loadViewData($this->module.$this->view.'index'),
            compact('products', 'vendors'));
    }

    public function toggleStatus($code){
        try{
            $this->productService->updateActiveStatus($code);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (Exception $exception){
            return redirect()->route('admin.products.index')->with('danger', $exception->getMessage());
        }
    }
}
