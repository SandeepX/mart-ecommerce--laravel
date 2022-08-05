<?php


namespace App\Modules\Reporting\Controllers\Web\Admin;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseOrderReportHelper;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Reporting\Exports\Dispatch\DispatchRecordsExport;
use App\Modules\Reporting\Exports\Dispatch\DispatchRecordsOfProductExport;
use App\Modules\Reporting\Exports\Dispatch\DispatchRecordsStoreWise;
use App\Modules\Reporting\Exports\Dispatch\DispatchStatementExport;
use App\Modules\Reporting\Helpers\DispatchReportSyncLogHelper;
use App\Modules\Store\Services\StoreService;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseDispatchReportController extends BaseController
{
    public $title = 'Warehouse Dispatch Report';
    public $base_route = 'admin.wh-dispatch-report';
    public $sub_icon = 'file';
    public $module = 'Reporting::';
    private $view;
    private $storeService;
    private $warehouseService;
    private $productService;
    public function __construct(
        StoreService $storeService,
        WarehouseService $warehouseService,
        ProductService $productService,
        ProductVariantService $productVariantService
    ){
        $this->view = 'admin.wh-reporting.';
        $this->storeService = $storeService;
        $this->warehouseService  =$warehouseService;
        $this->productService  = $productService;
        $this->productVariantService  = $productVariantService;
    }

    public function warehouseDispatchReport(Request $request){
        try{

            $filterParameters = [
                'warehouse_code' => ($request->get('warehouse_code'))??'AW1000',
                'store_code' => $request->get('store_code'),
                'vendor_code' => $request->get('vendor_code'),
                'product_code' => $request->get('product_code'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'perPage' => $request->get('per_page') ?? 25,
                'page' =>(int) $request->get('page') ?? 1,
                'download_excel' => $request->get('download_excel') ?? false
            ];

            $with = ['stores:store_code,store_name'];
            $warehouses = WarehouseHelper::getAllWarehousesWithConnectedStores($with);

            $lastDispatchSyncData = DispatchReportSyncLogHelper::getLastDispatchSyncDateAndStatus();
            if($request->ajax() || $filterParameters['download_excel']){
                $warehouseName = $this->warehouseService->findOrFailWarehouseByCode($filterParameters['warehouse_code'])->warehouse_name;

                $orderProducts = WarehouseOrderReportHelper::getNewWarehouseDispatchOrderReport($filterParameters);
              //  dd($orderProducts);
                $productPackagingFormatter = new ProductPackagingFormatter();

                $orderProducts->getCollection()->transform(function ($product,$key) use ($productPackagingFormatter){
                    $with=['microPackageType','unitPackageType','macroPackageType','superPackageType'];
                    $productUnitPackaging =  ProductUnitPackageDetail::with($with)->where('product_code',$product->product_code)
                                             ->where('product_variant_code',$product->product_variant_code)
                                             ->first();

                    $product->normal_order_packaging_qty = $product->normal_order_micro_quantity;
                    $product->pre_order_packaging_qty = $product->pre_order_micro_quantity;
                    $product->total_packaging_qty =$product->normal_order_micro_quantity + $product->pre_order_micro_quantity;
                    if($productUnitPackaging){
                        $arr =[];
                        if ($productUnitPackaging){
                            if ($productUnitPackaging->micro_unit_code){
                                $arr[1] =$productUnitPackaging->microPackageType->package_name;
                            }
                            if ($productUnitPackaging->unit_code){
                                $arrKey = intval($productUnitPackaging->micro_to_unit_value);
                                $arr[$arrKey] =$productUnitPackaging->unitPackageType->package_name;
                            }
                            if ($productUnitPackaging->macro_unit_code){
                                $arrKey = intval($productUnitPackaging->micro_to_unit_value *
                                    $productUnitPackaging->unit_to_macro_value);
                                $arr[$arrKey] =$productUnitPackaging->macroPackageType->package_name;
                            }
                            if ($productUnitPackaging->super_unit_code){
                                $arrKey = intval($productUnitPackaging->micro_to_unit_value *
                                    $productUnitPackaging->unit_to_macro_value *
                                    $productUnitPackaging->macro_to_super_value);
                                $arr[$arrKey] =$productUnitPackaging->superPackageType->package_name;
                            }
                        }
                        $arr=array_reverse($arr,true);
                        if($product->normal_order_micro_quantity){
                         $product->normal_order_packaging_qty = $productPackagingFormatter->formatPackagingCombination($product->normal_order_micro_quantity,$arr);
                        }
                        if($product->pre_order_micro_quantity){
                            $product->pre_order_packaging_qty = $productPackagingFormatter->formatPackagingCombination($product->pre_order_micro_quantity,$arr);
                        }
                        $product->total_packaging_qty = $productPackagingFormatter->formatPackagingCombination($product->normal_order_micro_quantity+$product->pre_order_micro_quantity,$arr);

                    }
                    return $product;

                });

                if($filterParameters['download_excel']){
                    return Excel::download(new DispatchRecordsExport($orderProducts,$warehouseName), 'warehouseDispatchRecords.xlsx');
                }
                //dd($orderProducts);
                return view( Parent::loadViewData($this->module.$this->view.'reports-partial-table'),compact('orderProducts','filterParameters'))->render();
            }
            return view( Parent::loadViewData($this->module.$this->view.'index'),
                compact('warehouses','filterParameters','lastDispatchSyncData'))->render();

        }catch(Exception $exception){
            if($request->ajax()){
                  return sendErrorResponse($exception->getMessage(),$exception->getCode());
            }
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function warehouseDispatchReportOfProductWithStoreLists(Request $request,$warehouseCode,$productCode){
        try{
            $filterParameters = [
                'warehouse_code' => $warehouseCode,
                'product_code' => $productCode,
                'product_variant_code' => $request->get('product_variant_code'),
                'store_code' => $request->get('store_code'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'perPage' => $request->get('per_page') ?? 25,
                'page' => (int)($request->get('page') ?? 1),
                'download_excel' => $request->get('download_excel') ?? false
            ];


            $stores = $this->storeService->getAllStores();
            $warehouseName = $this->warehouseService->findOrFailWarehouseByCode($filterParameters['warehouse_code'])->warehouse_name;
            $productName = $this->productService->findOrFailProductByCode($filterParameters['product_code'])->product_name;
            $productVariantName = NULL;
            if($filterParameters['product_variant_code']){
             $productVariantName = $this->productVariantService->findOrFailVariantByProductCodeAndVariantCode(
                        $filterParameters['product_code'],
                        $filterParameters['product_variant_code']
                    )->product_variant_name;
            }
            $lastDispatchSyncData = DispatchReportSyncLogHelper::getLastDispatchSyncDateAndStatus();
            $dispatchOrdersReportStoreWise = WarehouseOrderReportHelper::getStoreWiseDispatchReportOfWarehouse($filterParameters);
            $productPackagingFormatter = new ProductPackagingFormatter();
            $dispatchOrdersReportStoreWise->getCollection()->transform(function ($stores,$key) use ($productPackagingFormatter,$filterParameters){
                $with=['microPackageType','unitPackageType','macroPackageType','superPackageType'];
                $productUnitPackaging =  ProductUnitPackageDetail::with($with)->where('product_code',$filterParameters['product_code'])
                    ->where('product_variant_code',$filterParameters['product_variant_code'])
                    ->first();

                $stores->normal_order_packaging_qty = $stores->normal_order_micro_quantity;
                $stores->pre_order_packaging_qty = $stores->pre_order_micro_quantity;
                $stores->total_packaging_qty =$stores->normal_order_micro_quantity + $stores->pre_order_micro_quantity;
                if($productUnitPackaging){
                    $arr =[];
                    if ($productUnitPackaging){
                        if ($productUnitPackaging->micro_unit_code){
                            $arr[1] =$productUnitPackaging->microPackageType->package_name;
                        }
                        if ($productUnitPackaging->unit_code){
                            $arrKey = intval($productUnitPackaging->micro_to_unit_value);
                            $arr[$arrKey] =$productUnitPackaging->unitPackageType->package_name;
                        }
                        if ($productUnitPackaging->macro_unit_code){
                            $arrKey = intval($productUnitPackaging->micro_to_unit_value *
                                $productUnitPackaging->unit_to_macro_value);
                            $arr[$arrKey] =$productUnitPackaging->macroPackageType->package_name;
                        }
                        if ($productUnitPackaging->super_unit_code){
                            $arrKey = intval($productUnitPackaging->micro_to_unit_value *
                                $productUnitPackaging->unit_to_macro_value *
                                $productUnitPackaging->macro_to_super_value);
                            $arr[$arrKey] =$productUnitPackaging->superPackageType->package_name;
                        }
                    }
                    $arr=array_reverse($arr,true);
                    if($stores->normal_order_micro_quantity){
                        $stores->normal_order_packaging_qty = $productPackagingFormatter->formatPackagingCombination($stores->normal_order_micro_quantity,$arr);
                    }
                    if($stores->pre_order_micro_quantity){
                        $stores->pre_order_packaging_qty = $productPackagingFormatter->formatPackagingCombination($stores->pre_order_micro_quantity,$arr);
                    }
                    $stores->total_packaging_qty = $productPackagingFormatter->formatPackagingCombination($stores->normal_order_micro_quantity+$stores->pre_order_micro_quantity,$arr);
                }
                return $stores;
            });

           // dd($dispatchOrdersReportStoreWise);

            if($filterParameters['download_excel']){
                return Excel::download(new DispatchRecordsStoreWise(
                    $dispatchOrdersReportStoreWise,
                    $warehouseName,
                    $productName,
                    $productVariantName
                ), 'warehouse_dispatch_records_store_wise.xlsx');
            }

            return view( Parent::loadViewData($this->module.$this->view.'stores-details'),
                compact('filterParameters','warehouseName','productName','productVariantName','stores','dispatchOrdersReportStoreWise','lastDispatchSyncData'))->render();

        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getDispatchStatementByWarehouseStoreAndProduct(
        Request $request,$warehouseCode,$storeCode,$productCode
    ){

        try{
            $filterParameters = [
                'warehouse_code' => $warehouseCode,
                'product_code' => $productCode,
                'product_variant_code' => $request->get('product_variant_code'),
                'store_code' => $storeCode,
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'order_type' => $request->get('order_type'),
                'perPage' => $request->get('per_page') ?? 25,
                'page' => (int)($request->get('page') ?? 1),
                'download_excel' => $request->get('download_excel') ?? false
            ];
            $orderTypes = ['normal_order','preorder'];
            $lastDispatchSyncData = DispatchReportSyncLogHelper::getLastDispatchSyncDateAndStatus();
            $storeName = $this->storeService->findOrFailStoreByCode($filterParameters['store_code'])->store_name;
            $warehouseName = $this->warehouseService->findOrFailWarehouseByCode($filterParameters['warehouse_code'])->warehouse_name;
            $productName = $this->productService->findOrFailProductByCode($filterParameters['product_code'])->product_name;
            $productVariantName = NULL;
            if($filterParameters['product_variant_code']){
                $productVariantName = $this->productVariantService->findOrFailVariantByProductCodeAndVariantCode(
                    $filterParameters['product_code'],
                    $filterParameters['product_variant_code']
                )->product_variant_name;
            }
            $dispatchStatementOfProduct = WarehouseOrderReportHelper::getDispatchStatementOfProduct($filterParameters);
            $dispatchStatementOfProduct->getCollection()->transform(function ($statement,$key){
                $statement->link = WarehouseOrderReportHelper::generateDispatchStatementReferenceLink($statement->order_type,$statement->order_code);
                return $statement;
            });

            if($filterParameters['download_excel']){
                return Excel::download(new DispatchRecordsOfProductExport(
                    $dispatchStatementOfProduct,
                    $warehouseName,
                    $storeName,
                    $productName,
                    $productVariantName
                ), 'dispatch-records-of-product.xlsx');
            }

            return view( Parent::loadViewData($this->module.$this->view.'product-wise-dispatch-statement'),
                compact('filterParameters','orderTypes','warehouseName','productName','productVariantName','storeName','dispatchStatementOfProduct','lastDispatchSyncData'))->render();

        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function warehouseDispatchStatement(Request $request){
        try{
            $filterParameters = [
                'warehouse_code' => $request->get('warehouse_code') ?? 'AW1000',
                'store_code' => $request->get('store_code'),
                'vendor_code' => $request->get('vendor_code'),
                'product_code' => $request->get('product_code'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'perPage' => $request->get('per_page') ?? 25,
                'page' =>(int)( $request->get('page') ?? 1 ),
                'download_excel' => $request->get('download_excel') ?? false
            ];


            $with = ['stores:store_code,store_name'];
            $warehouses = WarehouseHelper::getAllWarehousesWithConnectedStores($with);
            $warehouseName = $this->warehouseService->findOrFailWarehouseByCode($filterParameters['warehouse_code'])->warehouse_name;
            $lastDispatchSyncData = DispatchReportSyncLogHelper::getLastDispatchSyncDateAndStatus();
            if($request->ajax() || $filterParameters['download_excel']){
                $dispatchStatements = WarehouseOrderReportHelper::getLatestDispatchStatementOfWarehouse($filterParameters);

                $dispatchStatements->getCollection()->transform(function ($statement) {
                    $statement->link = WarehouseOrderReportHelper::generateDispatchStatementReferenceLink($statement->order_type,$statement->order_code);
                    return $statement;
                });

                if($filterParameters['download_excel']){
                    return Excel::download(new DispatchStatementExport($dispatchStatements,$warehouseName), 'dispatch-statement.xlsx');
                }

                return view( Parent::loadViewData($this->module.$this->view.'dispatch-statement.partial-table'),
                    compact('dispatchStatements'))->render();
            }


            return view( Parent::loadViewData($this->module.$this->view.'dispatch-statement.index'),
                compact('warehouses','filterParameters','lastDispatchSyncData'))->render();

        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
