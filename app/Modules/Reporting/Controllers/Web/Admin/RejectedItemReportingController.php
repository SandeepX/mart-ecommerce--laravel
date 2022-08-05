<?php

namespace App\Modules\Reporting\Controllers\Web\Admin;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseHelper;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Services\ProductVariantService;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Reporting\Helpers\RejectedItemSyncLogHelper;
use App\Modules\Reporting\Helpers\WarehouseRejectedItemReportingHelper;
use App\Modules\Store\Services\StoreService;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use Exception;
use Illuminate\Http\Request;

class RejectedItemReportingController extends BaseController
{
    public $title = 'Rejected Item Reporting';
    public $base_route = 'admin.wh-rejected-item-reporting.';
    public $sub_icon = 'file';
    public $module = 'Reporting::';

    private $view = 'admin.wh-rejected-item-reporting.';

    public $warehouseService;
    public $vendorProductPackagingHistoryRepository;

    public function __construct(
        StoreService $storeService,
        WarehouseService $warehouseService,
        ProductService $productService,
        ProductVariantService $productVariantService,
        VendorProductPackagingHistoryRepository  $vendorProductPackagingHistoryRepository
    )
    {
        $this->storeService = $storeService;
        $this->warehouseService  =$warehouseService;
        $this->productService  = $productService;
        $this->productVariantService  = $productVariantService;
        $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
    }
    public function warehouseRejectedItemReporting(Request $request)
    {
        try {
            $filterParameters = [
                'warehouse_code' => ($request->get('warehouse_code')) ? $request->get('warehouse_code'):'AW1000',
                'vendor_code' => $request->get('vendor_code'),
                'product_code' => $request->get('product_code'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'perPage' => $request->get('per_page')?? 25,
                'page' =>(int) $request->get('page') ?? 1
            ];

            $with = ['stores:store_code,store_name'];
            $warehouse = WarehouseHelper::getAllWarehousesWithConnectedStores($with);
            $lastRejectedItemSyncData = RejectedItemSyncLogHelper::getLastRejectedItemSyncDateAndStatus();

            if($request->ajax()) {
                $warehouseRejectedItemData = WarehouseRejectedItemReportingHelper::getWarehouseRejectedItemReport($filterParameters);
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
                return view( Parent::loadViewData($this->module.$this->view.'reports-partial-table'),
                    compact('warehouseRejectedItemData','filterParameters'))->render();
            }
            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact(

                    'warehouse',
                    'filterParameters',
                    'lastRejectedItemSyncData'

                )
            );
        } catch (Exception $exception) {
            if($request->ajax()){
                return sendErrorResponse($exception->getMessage(),$exception->getCode());
            }
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }

    }

    public function warehouseRejectedItemReportWithStoreLists(Request $request,$warehouseCode,$productCode)
    {
        try{
            $filterParameters = [
                'warehouse_code' => $warehouseCode,
                'product_code' => $productCode,
                'product_variant_code' => $request->get('product_variant_code'),
                'store_code' => $request->get('store_code'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'perPage' => $request->get('per_page') ?? 25,
                'page' => (int)($request->get('page') ?? 1)
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
            $lastDispatchSyncData = RejectedItemSyncLogHelper::getLastRejectedItemSyncDateAndStatus();
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


            return view(Parent::loadViewData($this->module . $this->view . 'rejected-item-detail'),
                compact('lastDispatchSyncData',
                    'filterParameters','rejectedItemReportStoreWise','warehouseName','stores','productName','productVariantName'));
        }catch(\Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function warehouseRejectedItemDetailReport(Request $request,$warehouseCode,$storeCode,$productCode)
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
                'perPage' => $request->get('per_page') ?? 25,
                'page' => (int)($request->get('page') ?? 1)
            ];

            $orderTypes = ['normal_order','preorder'];
            $lastRejectedSyncData = RejectedItemSyncLogHelper::getLastRejectedItemSyncDateAndStatus();
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
            $rejectedItemStatementOfProduct = WarehouseRejectedItemReportingHelper::getRejectionDetailStatementOfProduct($filterParameters);
            $rejectedItemStatementOfProduct->getCollection()->transform(function ($statement,$key){
                $statement->link = WarehouseRejectedItemReportingHelper::generateRejectedItemStatementReferenceLink($statement->order_type,$statement->order_code);
                return $statement;
            });

            return view( Parent::loadViewData($this->module.$this->view. 'product-wise-rejected_item-statement'),
            compact('filterParameters','orderTypes',
                'warehouseName','productName',
                'productVariantName',
                'storeName',
                'rejectedItemStatementOfProduct',
                'lastRejectedSyncData'))->render();

        }catch(Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function warehouseRejectedItemStatement(Request $request)
    {
        try {
            $filterParameters = [
                'warehouse_code' => $request->get('warehouse_code'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'order_type' => $request->get('order_type'),
                'vendor_code' => $request->get('vendor_code'),
                'store_code' => $request->get('store_code'),
                'product_variant_code' => $request->get('product_variant_code'),
                'product_name' => $request->get('product_name'),
                'product_code' => $request->get('product_code'),
                'product_variant_name' => $request->get('product_variant_name'),
                'perPage' => $request->get('per_page') ? $request->get('per_page') : 25,
                'page' =>(int) $request->get('page') ? $request->get('page') : 1
            ];
            $with = ['stores:store_code,store_name'];
            $warehouse = WarehouseHelper::getAllWarehousesWithConnectedStores($with);
            $orderTypes = ['normal_order','preorder'];

            $rejectedItemStatement = WarehouseRejectedItemReportingHelper::getWarehouseRejectedItemReportForsatement($filterParameters);
            $lastRejectedItemSyncData = RejectedItemSyncLogHelper::getLastRejectedItemSyncDateAndStatus();
            $rejectedItemStatement->getCollection()->transform(function ($statement,$key){
                $statement->link = WarehouseRejectedItemReportingHelper::generateRejectedItemStatementReferenceLink($statement->order_type,$statement->order_code);
                return $statement;
            });

            return view(Parent::loadViewData($this->module . $this->view . 'rejected-item-statement.index'),
                compact(
                    'rejectedItemStatement',
                    'warehouse',
                    'filterParameters',
                    'lastRejectedItemSyncData',
                    'orderTypes'

                )
            );
        } catch (Exception $exception) {

            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }


}

