<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse;


use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductPackagingHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Requests\WarehouseProductPackageDisableRequest;
use App\Modules\AlpasalWarehouse\Requests\WarehouseProductsMicroDisableRequest;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductPackagingService;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductService;
use App\Modules\Application\Controllers\BaseController;

use App\Modules\Vendor\Services\VendorService;
use Exception;

class WarehouseProductPackagingController extends BaseController
{

    public $title = 'Alpasal Warehouse Product';
    public $base_route = 'warehouse.warehouse-products.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-products.packaging.';


    private $vendorService,$warehouseProductService,$warehouseProductPackagingService;

    public function __construct(
        VendorService $vendorService,
        WarehouseProductService $warehouseProductService,
        WarehouseProductPackagingService $warehouseProductPackagingService)
    {
        $this->vendorService = $vendorService;
        $this->warehouseProductService= $warehouseProductService;
        $this->warehouseProductPackagingService = $warehouseProductPackagingService;

    }

    public function editWarehouseProductPackagingDisableList($productCode){
        try{
            $warehouseCode = getAuthWarehouseCode();
            $warehouseProductDetail = WarehouseProductPackagingHelper::findWarehouseProductWithPackagingByProductCode($warehouseCode,$productCode);
            //dd($warehouseProductDetail);
            $warehouseProductDetail= $warehouseProductDetail->map(function($warehouseProduct){
                $warehouseProduct->disabled_packages= WarehouseProductPackagingUnitDisableList::where('warehouse_product_master_code',
                    $warehouseProduct->warehouse_product_master_code)->pluck('unit_name')->toArray();
                $packagingInfo=[];
                 if ($warehouseProduct->super_unit_code){
                     $toBePushed = '1 ' . $warehouseProduct->super_unit_name . ' = ' .
                         $warehouseProduct->macro_to_super_value . ' ' .
                         $warehouseProduct->macro_unit_name.'';

                     $toBePushed=$toBePushed.'(1 ' . $warehouseProduct->super_unit_name . ' = ' .
                         $warehouseProduct->unit_to_macro_value *
                         $warehouseProduct->macro_to_super_value . ' ' .
                         $warehouseProduct->unit_name.') ';

                     $toBePushed=$toBePushed.'(1 ' . $warehouseProduct->super_unit_name . ' = ' .
                         $warehouseProduct->micro_to_unit_value *
                         $warehouseProduct->unit_to_macro_value *
                         $warehouseProduct->macro_to_super_value . ' ' .
                         $warehouseProduct->micro_unit_name.')';
                     array_push($packagingInfo,$toBePushed);
                 }

                if ($warehouseProduct->macro_unit_code){
                    $toBePushed = '1 ' . $warehouseProduct->macro_unit_name . ' = ' .
                        $warehouseProduct->unit_to_macro_value . ' ' .
                        $warehouseProduct->unit_name.'';

                    $toBePushed=$toBePushed.'(1 ' . $warehouseProduct->macro_unit_name . ' = ' .
                        $warehouseProduct->micro_to_unit_value *
                        $warehouseProduct->unit_to_macro_value . ' ' .
                        $warehouseProduct->micro_unit_name.')';

                    array_push($packagingInfo,$toBePushed);
                }

                if ($warehouseProduct->unit_code){
                    $toBePushed='1 ' . $warehouseProduct->unit_name . ' = ' .
                        $warehouseProduct->micro_to_unit_value. ' ' .
                        $warehouseProduct->micro_unit_name;
                    array_push($packagingInfo,$toBePushed);
                }

                $warehouseProduct->packaging_info= $packagingInfo;
                return $warehouseProduct;
            });

           // dd($warehouseProductDetail);
            return view(Parent::loadViewData($this->module.$this->view.'mass-packaging-disable-form'),compact('warehouseProductDetail'));
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
          //  return redirect()->route('warehouse.warehouse-products.index')->with('danger', $exception->getMessage());
        }
    }

    public function updateWarehouseProductPackagingDisableList(
        WarehouseProductPackageDisableRequest $request,$productCode){
        try{
            $validatedData = $request->validated();
            $this->warehouseProductPackagingService->disableProductsPackagingForPreOrder(
                $validatedData,$productCode);
            return sendSuccessResponse('Packaging updated for the product.');

        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updateProductsMicroPackaging(
        WarehouseProductsMicroDisableRequest $request){
        try{
            //return sendSuccessResponse('Packaging updated for the product.');
            $validatedData = $request->validated();
            $this->warehouseProductPackagingService->disableMassWarehousePreOrderProductsMicroPackaging(
                getAuthWarehouseCode(),$validatedData);
            return $request->session()->flash('success','Packaging updated for the products.');
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
