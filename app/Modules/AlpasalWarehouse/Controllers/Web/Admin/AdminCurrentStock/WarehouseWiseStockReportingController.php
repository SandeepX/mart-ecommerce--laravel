<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Admin\AdminCurrentStock;

use App\Modules\AlpasalWarehouse\Exports\Warehouse\VendorWiseProductStockReportExpert;
use App\Modules\AlpasalWarehouse\Services\AdminCurrentStock\WarehouseWiseStockReportingService;
use App\Modules\AlpasalWarehouse\Services\CurrentStock\VendorWiseStockReportingService;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;
class WarehouseWiseStockReportingController extends  BaseController
{

    public $title = 'Alpasal Warehouse Wise Current Stock Reporting';
    public $base_route = 'admin.warehouse-wise.current-stock.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='admin.wh-current-stock.';

    private $warehouseWiseStockReportingService,$vendorService,$warehouseService;


    public function __construct(
        WarehouseWiseStockReportingService $warehouseWiseStockReportingService,
        VendorService $vendorService,
        WarehouseService $warehouseService
    )
    {
        $this->middleware('permission:View Warehouse Wise Current Stock List',
            ['only' => ['index']]);
        $this->middleware('permission:Show Admin Vendor Wise Current Stock',
            ['only' => ['getWarehouseWiseProduct']]);
        $this->middleware('permission:Show Admin Product Current Stock',
            ['only' => ['getVendorWiseProduct']]);

        $this->warehouseWiseStockReportingService = $warehouseWiseStockReportingService;
        $this->vendorService = $vendorService;
        $this->warehouseService = $warehouseService;

    }

    public function index(Request $request)
    {
        try{
            $filterParameters['warehouse_code'] = $request->get('warehouse_code');
            $warehouses = $this->warehouseService->getAllWarehouses();
            $warehouseWiseCurrentStocks = $this->warehouseWiseStockReportingService->getWarehouseWiseCurrentStock($filterParameters);
            return view($this->loadViewData($this->module.$this->view.'index'),
                compact('warehouseWiseCurrentStocks','warehouses','filterParameters'));
        }
        catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getWarehouseWiseProduct($warehouseCode,Request $request)
    {
        try{
            $filterParameters['vendor_code'] = $request->get('vendor_code');
            $warehouse = $this->warehouseService->findWarehouseByCode($warehouseCode);
            $vendors = $this->vendorService->getAllActiveVendors();
            $vendorWiseCurrentStocks = $this->warehouseWiseStockReportingService->getVendorWiseCurrentStock($warehouseCode,$filterParameters);
            return view($this->loadViewData($this->module.$this->view.'vendor-wise-product'),
                compact('vendorWiseCurrentStocks','vendors','filterParameters','warehouse'));
        }
        catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getVendorWiseProduct($warehouseCode,$vendorCode,Request $request)
    {
        try{
            $filterParameters['product_name'] = $request->get('product_name');
            $vendorWiseProducts = $this->warehouseWiseStockReportingService->getVendorWiseProduct($warehouseCode,$vendorCode,$filterParameters);
            $vendor = $this->vendorService->findVendorByCode($vendorCode);
            $warehouse = $this->warehouseService->findWarehouseByCode($warehouseCode);
            $productPackagingFormatter = new ProductPackagingFormatter();
            $vendorWiseProducts = $vendorWiseProducts->map(function($vendorWiseProduct) use ($productPackagingFormatter){
                $arr =[];
                if ($vendorWiseProduct){
                    if ($vendorWiseProduct->micro_unit_code){
                        $arr[1] =$vendorWiseProduct->micro_unit_name;
                    }
                    if ($vendorWiseProduct->unit_code){
                        $arrKey = intval($vendorWiseProduct->micro_to_unit_value);
                        $arr[$arrKey] =$vendorWiseProduct->unit_name;
                    }
                    if ($vendorWiseProduct->macro_unit_code){
                        $arrKey = intval($vendorWiseProduct->micro_to_unit_value *
                            $vendorWiseProduct->unit_to_macro_value);
                        $arr[$arrKey] =$vendorWiseProduct->macro_unit_name;
                    }
                    if ($vendorWiseProduct->super_unit_code){

                        $arrKey = intval($vendorWiseProduct->micro_to_unit_value *
                            $vendorWiseProduct->unit_to_macro_value *
                            $vendorWiseProduct->macro_to_super_value);

                        $arr[$arrKey] =$vendorWiseProduct->super_unit_name;
                    }
                }
                $arr=array_reverse($arr,true);
                $vendorWiseProduct->product_packaging_detail = $productPackagingFormatter->formatPackagingCombination($vendorWiseProduct->current_stock,$arr);
                // $storePreOrderProduct->product_packaging_detail =$arr;
                return $vendorWiseProduct;
            });

            return view($this->loadViewData($this->module.$this->view.'show'),
                compact('vendorWiseProducts','vendor','warehouse','filterParameters'));
        }
        catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function exportExcellVendorWiseProductStockReport ($warehouseCode,$vendorCode){


        try{
            $filterParameters=[];
            $vendor = $this->vendorService->findVendorByCode($vendorCode);
            $warehouse = $this->warehouseService->findWarehouseByCode($warehouseCode);
            $vendorWiseProducts = $this->warehouseWiseStockReportingService->getVendorWiseProduct($warehouse->warehouse_code,$vendorCode,$filterParameters);
            $productPackagingFormatter = new ProductPackagingFormatter();
            $vendorWiseProducts = $vendorWiseProducts->map(function($vendorWiseProduct) use ($productPackagingFormatter){
                $arr =[];
                if ($vendorWiseProduct){
                    if ($vendorWiseProduct->micro_unit_code){
                        $arr[1] =$vendorWiseProduct->micro_unit_name;
                    }
                    if ($vendorWiseProduct->unit_code){
                        $arrKey = intval($vendorWiseProduct->micro_to_unit_value);
                        $arr[$arrKey] =$vendorWiseProduct->unit_name;
                    }
                    if ($vendorWiseProduct->macro_unit_code){
                        $arrKey = intval($vendorWiseProduct->micro_to_unit_value *
                            $vendorWiseProduct->unit_to_macro_value);
                        $arr[$arrKey] =$vendorWiseProduct->macro_unit_name;
                    }
                    if ($vendorWiseProduct->super_unit_code){

                        $arrKey = intval($vendorWiseProduct->micro_to_unit_value *
                            $vendorWiseProduct->unit_to_macro_value *
                            $vendorWiseProduct->macro_to_super_value);

                        $arr[$arrKey] =$vendorWiseProduct->super_unit_name;
                    }
                }
                $arr=array_reverse($arr,true);
                //dd($arr);
                $vendorWiseProduct->product_packaging_detail = $productPackagingFormatter->formatPackagingCombination($vendorWiseProduct->current_stock,$arr);
                // $storePreOrderProduct->product_packaging_detail =$arr;
                return $vendorWiseProduct;
            });

            return (new VendorWiseProductStockReportExpert($warehouse, $vendorWiseProducts, $vendor, $this->module, $this->view));
        } catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
