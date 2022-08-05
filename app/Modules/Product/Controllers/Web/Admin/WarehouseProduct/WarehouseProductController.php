<?php


namespace App\Modules\Product\Controllers\Web\Admin\WarehouseProduct;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Helpers\AdminWarehouseProductFilter;
use App\Modules\Product\Services\ProductService;
use Exception;

class WarehouseProductController extends BaseController
{

    public $title = 'Product';
    public $base_route = 'admin.warehouse-products.';
    public $sub_icon = 'file';
    public $module = 'Product::';

    private $view='admin.warehouse-products.';

    private $productService;

    public function __construct(ProductService $productService){
        $this->productService = $productService;
    }

    public function showWarehousesProductStocksDetail($productCode){

        try{

            $filterParameters = [
                'product_code' =>  $productCode,
            ];

            $with=[
                'product',
                'productVariant',
                'warehouse'
            ];
            $productWith=[
                'vendor',
                'brand',
                'category'
            ];
            $productDetail = $this->productService->findOrFailProductByCodeWith($productCode,$productWith);
          //  $warehouseProducts = AdminWarehouseProductFilter::filterPaginatedWarehouseProduct($filterParameters,25,$with);
           $warehouseProducts = AdminWarehouseProductFilter::filterPaginatedWarehouseProduct($filterParameters,25,$with);
          // return $warehouseProducts;
            return view($this->loadViewData($this->module.$this->view.'warehouses-stocks'),
                compact('productDetail','warehouseProducts'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }
}
