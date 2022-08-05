<?php


namespace App\Modules\Reporting\Controllers\Web\Admin;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockStatementHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStock;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductService;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Reporting\Exports\Stock\StockStatementDetailExport;
use App\Modules\Reporting\Exports\Stock\StockStatementExport;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use App\Modules\Vendor\Services\VendorService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseStockReportController extends BaseController
{
    public $title = 'Warehouse Stock Report';
    public $base_route = 'admin.wh-stock-report.';
    public $sub_icon = 'file';
    public $module = 'Reporting::';

    private $view;

    private $warehouseService;
    private $vendorService;
    private $warehouseProductService;
    private $vendorProductPackagingHistoryRepository;

    public function __construct(
        WarehouseService $warehouseService,
        VendorService $vendorService,
        WarehouseProductService $warehouseProductService,
        VendorProductPackagingHistoryRepository  $vendorProductPackagingHistoryRepository
    ){
        $this->warehouseService = $warehouseService;
        $this->vendorService = $vendorService;
        $this->warehouseProductService = $warehouseProductService;
        $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
        $this->view = 'admin.wh-stock-reporting.';
    }

    public function warehouseStockReportIndex(Request $request){
        try{
            $filterParameters = [
                'warehouse_code' => $request->get('warehouse_code') ?? 'AW1000',
                'vendor_code' => $request->get('vendor_code'),
                'product_code' => $request->get('product_code'),
                'stock_action' =>  $request->get('stock_action'),
                'start_date'  => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'report_filter_date' => config('ReportingFilterDate.reporting_date_from'),
                'download_excel' => $request->get('download_excel') ?? false
            ];

            $stockActions = WarehouseProductStock::STOCK_ACTIONS_TYPES;
            $with = [
                'warehouseProductMaster',
                'warehouseProductMaster.product',
                'warehouseProductMaster.productVariant',
                'warehouseProductMaster.vendor'
            ];
            $warehouses = $this->warehouseService->getAllWarehouses();
            $warehouseName = $this->warehouseService->findOrFailWarehouseByCode($filterParameters['warehouse_code'])->warehouse_name;

            if($request->ajax() || $filterParameters['download_excel']) {
                $warehouseProductStatements = WarehouseProductStockStatementHelper::getWarehouseProductStockStatement($filterParameters, $with);
                $productPackagingFormatter = new ProductPackagingFormatter();

                $warehouseProductStatements->transform(function ($warehouseProductStatement, $key) use ($productPackagingFormatter){
                    $latestPackagingDetails = $this->vendorProductPackagingHistoryRepository->getProductPackagingHistoryByProductCodeAndVariantCode(
                        $warehouseProductStatement->product_code,
                        $warehouseProductStatement->product_variant_code
                    );
                    if ($latestPackagingDetails){
                        $arr =[];
                        if ($latestPackagingDetails->micro_unit_code){
                            $arr[1] =$latestPackagingDetails->microPackageType->package_name;
                        }
                        if ($latestPackagingDetails->unit_code){
                            $arrKey = intval($latestPackagingDetails->micro_to_unit_value);
                            $arr[$arrKey] =$latestPackagingDetails->unitPackageType->package_name;
                        }
                        if ($latestPackagingDetails->macro_unit_code){
                            $arrKey = intval($latestPackagingDetails->micro_to_unit_value *
                                $latestPackagingDetails->unit_to_macro_value);
                            $arr[$arrKey] =$latestPackagingDetails->macroPackageType->package_name;
                        }
                        if ($latestPackagingDetails->super_unit_code){
                            $arrKey = intval($latestPackagingDetails->micro_to_unit_value *
                                $latestPackagingDetails->unit_to_macro_value *
                                $latestPackagingDetails->macro_to_super_value);
                            $arr[$arrKey] =$latestPackagingDetails->superPackageType->package_name;
                        }
                        $arr=array_reverse($arr,true);
                        $warehouseProductStatement->current_stock = $productPackagingFormatter->formatPackagingCombination($warehouseProductStatement->current_stock,$arr);
                    }
                    $warehouseProductStatement->link_data = WarehouseProductStockStatementHelper::generateStockStatementReferenceLink(
                        $warehouseProductStatement->action,
                        $warehouseProductStatement->reference_code
                    );
                    return $warehouseProductStatement;
                });

                if($filterParameters['download_excel']){
                    return Excel::download(new StockStatementExport($warehouseProductStatements,$warehouseName), 'stock-statement.xlsx');

                }

                return view( Parent::loadViewData($this->module.$this->view.'stock-report-partial-table'),
                    compact('warehouseProductStatements','filterParameters'))
                    ->render();
            }

            return view( Parent::loadViewData($this->module.$this->view.'index'),
                compact('warehouses','filterParameters','stockActions'));

        }catch(Exception $exception){
            if($request->ajax()){
                return sendErrorResponse($exception->getMessage(),$exception->getCode());
            }
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function getWarehouseStockReportOfWarehouseProductMaster(
        Request $request,
        $warehouseCode,
        $warehouseProductMasterCode
    ){
        try{
            $filterParameters = [
                'stock_action' =>  $request->get('stock_action'),
                'start_date'  => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'report_filter_date' => config('ReportingFilterDate.reporting_date_from'),
                'download_excel' => ($request->get('download_excel') ?? false)
            ];

            $stockActions = WarehouseProductStock::STOCK_ACTIONS_TYPES;
            $with = ['product','productVariant','vendor'];
            $warehouseProductMaster = $this->warehouseProductService->findOrFailWarehouseProductWithCodeAndWarehouseCode($warehouseProductMasterCode,$warehouseCode,$with);
            $warehouseName = $this->warehouseService->findOrFailWarehouseByCode($warehouseProductMaster->warehouse_code)->warehouse_name;

            $warehouseProductStatements = WarehouseProductStockStatementHelper::getStockReportOfWarehouseProductByWarehouseProductMasterCode(
                                                                                $warehouseCode,
                                                                                $warehouseProductMasterCode,
                                                                               $filterParameters
                                                                            );
            $productPackagingFormatter = new ProductPackagingFormatter();
            $warehouseProductStatements->transform(function ($warehouseProductStatement,$key) use ($productPackagingFormatter){

                $latestPackagingDetails = $this->vendorProductPackagingHistoryRepository->getProductPackagingHistoryByProductCodeAndVariantCode(
                                                                                            $warehouseProductStatement->product_code,
                                                                                            $warehouseProductStatement->product_variant_code);
                if ($latestPackagingDetails){
                    $arr =[];
                    if ($latestPackagingDetails->micro_unit_code){
                        $arr[1] =$latestPackagingDetails->microPackageType->package_name;
                    }
                    if ($latestPackagingDetails->unit_code){
                        $arrKey = intval($latestPackagingDetails->micro_to_unit_value);
                        $arr[$arrKey] =$latestPackagingDetails->unitPackageType->package_name;
                    }
                    if ($latestPackagingDetails->macro_unit_code){
                        $arrKey = intval($latestPackagingDetails->micro_to_unit_value *
                            $latestPackagingDetails->unit_to_macro_value);
                        $arr[$arrKey] =$latestPackagingDetails->macroPackageType->package_name;
                    }
                    if ($latestPackagingDetails->super_unit_code){
                        $arrKey = intval($latestPackagingDetails->micro_to_unit_value *
                            $latestPackagingDetails->unit_to_macro_value *
                            $latestPackagingDetails->macro_to_super_value);
                        $arr[$arrKey] =$latestPackagingDetails->superPackageType->package_name;
                    }
                $arr=array_reverse($arr,true);
                $warehouseProductStatement->current_stock = $productPackagingFormatter->formatPackagingCombination($warehouseProductStatement->current_stock_cum,$arr);
                }else{
                    $warehouseProductStatement->current_stock = $warehouseProductStatement->current_stock_cum;
                }
                $warehouseProductStatement->link_data = WarehouseProductStockStatementHelper::generateStockStatementReferenceLink(
                    $warehouseProductStatement->action,
                    $warehouseProductStatement->reference_code
                );
                return $warehouseProductStatement;
            });

            if($filterParameters['download_excel']){
                return Excel::download(new StockStatementDetailExport($warehouseProductStatements,$warehouseProductMaster), 'stock-statement-details.xlsx');
            }
            return view( Parent::loadViewData($this->module.$this->view.'detail'),
                compact('warehouseProductStatements','warehouseProductMaster','filterParameters','stockActions'));
        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
