<?php


namespace App\Modules\Reporting\Controllers\Web\Admin;


use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Reporting\Exports\RejectedItem\StoreWiseRejectedItemExcelExport;
use App\Modules\Reporting\Exports\RejectedItem\WarehouseProductWiseItemExcelExport;
use App\Modules\Reporting\Exports\RejectedItem\WarehouseRejectedItemDaybookExcelExport;
use App\Modules\Reporting\Exports\RejectedItem\WarehouseRejectedItemExcelExport;
use App\Modules\Reporting\Helpers\WarehouseRejectedItemReportingHelper;
use App\Modules\Store\Services\StoreService;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RejectedItemReportExcelExportController Extends BaseController
{

    public $module = 'Reporting::';
    private $view = 'admin.wh-rejected-item-reporting.rejected-item-export';

    public $storeService,$warehouseService,$productService,$productVariantService,$vendorProductPackagingHistoryRepository;

    public function __construct(
        StoreService $storeService,
        WarehouseService $warehouseService,
        ProductService $productService,
        ProductVariantService $productVariantService,
        VendorProductPackagingHistoryRepository  $vendorProductPackagingHistoryRepository
    )
    {   $this->storeService = $storeService;
        $this->warehouseService  =$warehouseService;
        $this->productService  = $productService;
        $this->productVariantService  = $productVariantService;
        $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
    }

    public function excelExportRejectedItemByWarehouse(Request $request)
    {
        try{
            $filterParameters = [
                'warehouse_code' => $request->get('warehouseCode'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'vendor_code' => $request->get('vendor'),
                'product_code' => $request->get('product_code'),
                'perPage' =>  1000,
                'page' => 1,
            ];

            $warehouseRejectedItemData = WarehouseRejectedItemReportingHelper::getWarehouseRejectedItemReport($filterParameters);
            $warehouseName = $this->warehouseService->findOrFailWarehouseByCode($filterParameters['warehouse_code'])->warehouse_name;

            $productPackagingFormatter = new ProductPackagingFormatter();
            $warehouseRejectedItemData->getCollection()->transform(function ($product, $key) use ($productPackagingFormatter) {
                $with = ['microPackageType', 'unitPackageType', 'macroPackageType', 'superPackageType'];
                $productUnitPackaging = ProductUnitPackageDetail::with($with)->where('product_code', $product->product_code)
                    ->where('product_variant_code', $product->product_variant_code)
                    ->first();

                $product->total_normal_packaging_rejected_qty = $product->total_normal_rejected_qty;
                $product->total_preorder_packaging_rejected_qty = $product->total_preorder_rejected_qty;
                $product->total_rejected_packaging_qty = $product->total_normal_rejected_qty + $product->total_preorder_rejected_qty;
                if ($productUnitPackaging) {
                    $arr = [];
                    if ($productUnitPackaging) {
                        if ($productUnitPackaging->micro_unit_code) {
                            $arr[1] = $productUnitPackaging->microPackageType->package_name;
                        }
                        if ($productUnitPackaging->unit_code) {
                            $arrKey = intval($productUnitPackaging->micro_to_unit_value);
                            $arr[$arrKey] = $productUnitPackaging->unitPackageType->package_name;
                        }
                        if ($productUnitPackaging->macro_unit_code) {
                            $arrKey = intval($productUnitPackaging->micro_to_unit_value *
                                $productUnitPackaging->unit_to_macro_value);
                            $arr[$arrKey] = $productUnitPackaging->macroPackageType->package_name;
                        }
                        if ($productUnitPackaging->super_unit_code) {
                            $arrKey = intval($productUnitPackaging->micro_to_unit_value *
                                $productUnitPackaging->unit_to_macro_value *
                                $productUnitPackaging->macro_to_super_value);
                            $arr[$arrKey] = $productUnitPackaging->superPackageType->package_name;
                        }
                    }
                    $arr = array_reverse($arr, true);
                    $product->total_normal_packaging_rejected_qty = $productPackagingFormatter->formatPackagingCombination($product->total_normal_rejected_qty, $arr);
                    $product->total_preorder_packaging_rejected_qty = $productPackagingFormatter->formatPackagingCombination($product->total_preorder_rejected_qty, $arr);
                    $product->total_rejected_packaging_qty = $productPackagingFormatter->formatPackagingCombination($product->total_normal_rejected_qty + $product->total_preorder_rejected_qty, $arr);

                }
                return $product;
            });

              return Excel::download(new WarehouseRejectedItemExcelExport($warehouseRejectedItemData,$warehouseName,$this->module, $this->view),
                'rejected-item-daybook.xlsx');

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function excelExportRejectedItemDayBook(Request $request)
    {
        try {
            $filterParameters = [
                'warehouse_code' => $request->get('warehouse_code'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'order_type' => $request->get('order_type'),
                'store_code' => $request->get('store_code'),
                'product_name' => $request->get('product_name'),
                'product_variant_name' => $request->get('product_variant_name'),
                'perPage' => 1000,
                'page' => 1
            ];
            $rejectedItemStatement = WarehouseRejectedItemReportingHelper::getWarehouseRejectedItemReportForsatement($filterParameters);
            return Excel::download(new WarehouseRejectedItemDaybookExcelExport($rejectedItemStatement,$this->module, $this->view),
                'warehousewise-rejected-item-daybook-report.xlsx');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function excelExportRejectedItemStoreWise(Request $request,$warehouseCode,$productCode)
    {
        try{
            $filterParameters = [
                'warehouse_code' => $warehouseCode,
                'product_code' => $productCode,
                'product_variant_code' => $request->get('product_variant_code'),
                'store_code' => $request->get('store_code'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'perPage' => 1000,
                'page' => 1
            ];
            $warehouseName = $this->warehouseService->findOrFailWarehouseByCode($filterParameters['warehouse_code'])->warehouse_name;
            $productName = $this->productService->findOrFailProductByCode($filterParameters['product_code'])->product_name;
            $productVariantName = NULL;
            if($filterParameters['product_variant_code']){
                $productVariantName = $this->productVariantService->findOrFailVariantByProductCodeAndVariantCode(
                    $filterParameters['product_code'],
                    $filterParameters['product_variant_code']
                )->product_variant_name;
            }

            $rejectedItemReportStoreWise = WarehouseRejectedItemReportingHelper::getStoreWiseRejectedItemReportOfWarehouse($filterParameters);
            $productPackagingFormatter = new ProductPackagingFormatter();
            $rejectedItemReportStoreWise->getCollection()->transform(function ($stores,$key) use ($productPackagingFormatter,$filterParameters){
                $with=['microPackageType','unitPackageType','macroPackageType','superPackageType'];

                $productUnitPackaging =  ProductUnitPackageDetail::with($with)->where('product_code',$filterParameters['product_code'])
                    ->where('product_variant_code',$filterParameters['product_variant_code'])
                    ->first();

                $stores->total_normal_rejected_packaging_qty = $stores->total_normal_rejected_qty;
                $stores->total_preorder_rejected_packaging_qty = $stores->total_preorder_rejected_qty;
                $stores->total_packaging_qty = $stores->total_preorder_rejected_packaging_qty + $stores->total_normal_rejected_packaging_qty;
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
                    $stores->total_normal_rejected_packaging_qty = $productPackagingFormatter->formatPackagingCombination($stores->total_normal_rejected_qty,$arr);
                    $stores->total_preorder_rejected_packaging_qty = $productPackagingFormatter->formatPackagingCombination($stores->total_preorder_rejected_qty,$arr);
                    $stores->total_packaging_qty = $productPackagingFormatter->formatPackagingCombination($stores->total_normal_rejected_qty+$stores->total_preorder_rejected_qty,$arr);
                }
                return $stores;
            });

            return Excel::download(new StoreWiseRejectedItemExcelExport(
                $rejectedItemReportStoreWise,
                $warehouseName,
                $productName,
                $productVariantName,
                $this->module,
                $this->view ),'store-wise-rejected-item-report.xlsx');


        }catch(\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function excelExportRejectedItemProductWise(Request $request,$warehouseCode,$storeCode,$productCode)
    {
        try{
            $filterParameters = [
                'warehouse_code' => $warehouseCode,
                'product_code' => $productCode,
                'product_variant_code' => $request->get('product_variant_code'),
                'store_code' => $storeCode,
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'order_type' => $request->get('order_type'),
                'perPage' => 1000,
                'page' => 1
            ];

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
            $rejectedItemReportProductWise = WarehouseRejectedItemReportingHelper::getRejectionDetailStatementOfProduct($filterParameters);

            return Excel::download(new WarehouseProductWiseItemExcelExport(
                    $rejectedItemReportProductWise,
                    $warehouseName,
                    $storeName,
                    $productName,
                    $productVariantName,
                    $this->module,
                    $this->view
            ),'product-wise-rejected-item-report.xlsx');

        }catch(Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }




}
