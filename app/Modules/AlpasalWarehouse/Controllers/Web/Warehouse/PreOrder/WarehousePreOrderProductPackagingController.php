<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;


use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderHelper;
use App\Modules\AlpasalWarehouse\Models\PreOrder\PreOrderPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderProductPackageDisableRequest;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderProductsMicroDisableRequest;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderProductPackagingService;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderProductService;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Brand\Services\BrandService;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Services\ProductService;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\Request;

use Exception;

class WarehousePreOrderProductPackagingController extends BaseController
{
    public $title = 'Alpasal Warehouse PreOrder';
    public $base_route = 'warehouse.warehouse-pre-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-pre-orders.';

    private $warehousePreOrderService,$vendorService,$categoryService,$brandService;
    private $productService,$warehousePreOrderProductService,$warehousePreOrderProductPackagingService;

    public function __construct(WarehousePreOrderService $warehousePreOrderService,
                                VendorService $vendorService,
                                CategoryService $categoryService,
                                BrandService $brandService,
                                ProductService $productService,
                                WarehousePreOrderProductService $warehousePreOrderProductService,
                                WarehousePreOrderProductPackagingService $warehousePreOrderProductPackagingService)
    {
        $this->warehousePreOrderService = $warehousePreOrderService;
        $this->vendorService = $vendorService;
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
        $this->productService = $productService;
        $this->warehousePreOrderProductService= $warehousePreOrderProductService;
        $this->warehousePreOrderProductPackagingService= $warehousePreOrderProductPackagingService;
    }


    public function editProductPackagingForPreOrder(Request $request,$warehousePreOrderCode,$productCode){
        try{
            $with='warehousePreOrderProducts';

            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByWarehouseCode($warehousePreOrderCode,getAuthWarehouseCode(),$with);
            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot edit product after finalization time.');
            }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot edit product: pre-order was cancelled.');
            }
            $product= $this->productService->findOrFailProductByCodeWith($productCode,['productVariants']);

            $warehousePreOrderProducts= WarehousePreOrderHelper::getPreOrderProductsWithPackaging($warehousePreOrderCode,$productCode);

            $warehousePreOrderProducts= collect($warehousePreOrderProducts)->map(function($warehousePreOrderProduct){
                $warehousePreOrderProduct->disabled_packages= PreOrderPackagingUnitDisableList::where('warehouse_preorder_product_code',
                    $warehousePreOrderProduct->warehouse_preorder_product_code)->pluck('unit_name')->toArray();

                return $warehousePreOrderProduct;
            });

            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.packaging-unit-update-form',
                    compact('warehousePreOrderProducts','warehousePreOrder','product'))->render();
            }
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updateProductPackagingForPreOrder(
        WarehousePreOrderProductPackageDisableRequest $request,
        $warehousePreOrderCode,
        $productCode){

        try{
            $validatedData = $request->validated();
            $this->warehousePreOrderProductPackagingService->disableProductsPackagingForPreOrder(
                $validatedData,$warehousePreOrderCode,$productCode);
            return sendSuccessResponse('Packaging updated for the product.');

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updatePreOrderProductsMicroPackaging(
        WarehousePreOrderProductsMicroDisableRequest $request,$warehousePreOrderListingCode){
        try{
            //return sendSuccessResponse('Packaging updated for the product.');
            $validatedData = $request->validated();
            $this->warehousePreOrderProductPackagingService->disableMassWarehousePreOrderProductsMicroPackaging(
                $validatedData,$warehousePreOrderListingCode);
            return $request->session()->flash('success','Packaging updated for the product.');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
