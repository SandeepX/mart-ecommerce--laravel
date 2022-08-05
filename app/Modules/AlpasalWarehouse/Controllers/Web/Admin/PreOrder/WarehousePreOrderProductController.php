<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Admin\PreOrder;

use App\Modules\AlpasalWarehouse\Exports\Admin\PreOrder\WarehousePreOrderProductExport;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderFilter;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderHelper;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\ClonePreOrderProductsRequest;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderProductService;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderDetailHelper;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Vendor\Services\VendorService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderProductController extends BaseController
{
    public $title = 'Alpasal Warehouse PreOrder';
    public $base_route = 'admin.warehouse-pre-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view = 'admin.warehouse-pre-orders.';

    private $warehousePreOrderService;
    private $warehousePreOrderProductService;
    private $vendorService;

    public function __construct(WarehousePreOrderService $warehousePreOrderService,
                                VendorService $vendorService,
                                WarehousePreOrderProductService $warehousePreOrderProductService
    )
    {

        $this->middleware('permission:View Warehouse Having Pre Orders',
        ['only' => ['getWarehousesHavingPreOrder']]);
        $this->middleware('permission:View List Of Pre Orders',
            ['only' => ['getPreOrdersInWarehouse']]);
        $this->middleware('permission:View Vendor Lists Pre Order Of Warehouse Having Pre Orders',
            ['only' => ['listVendorsInPreOrders']]);
        $this->middleware('permission:Clone Admin Warehouse Products',
            ['only' => ['cloneProductsFromSourceToDestinationListingCode']]);

        $this->warehousePreOrderService = $warehousePreOrderService;
        $this->warehousePreOrderProductService = $warehousePreOrderProductService;
        $this->vendorService = $vendorService;
    }

    public function getWarehousesHavingPreOrder(Request $request)
    {

        try {
            $filterParameters = [
                'warehouse_name' => $request->get('warehouse_name'),
            ];
            $warehouses = $this->warehousePreOrderService->getWarehousesHavingPreOrder($filterParameters, 10);
            return view($this->loadViewData($this->module . $this->view . 'index'), compact('warehouses', 'filterParameters'));
        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function getPreOrdersInWarehouse($warehouseCode)
    {
        try {
            $warehouse = $this->warehousePreOrderService->getWarehouseByCode($warehouseCode);
            $preOrders = $this->warehousePreOrderService->getPreOrdersInWarehouse($warehouseCode, 10);
            return view($this->loadViewData($this->module . $this->view . 'show'), compact('preOrders', 'warehouse'));
        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function getProductsInPreOrder($vendorCode, $preOrderListingCode, Request $request)
    {
        try {
            $filterParameters = [
                'vendor_code' => ($request->get('vendor_code')) ? $request->get('vendor_code') : $vendorCode,
                'product_code' => $request->get('product_code'),
                'product_variant_code' => $request->get('product_variant_code'),
                'status' => $request->get('status'),
            ];
            $vendors = $this->vendorService->findVendorByCode($vendorCode);
            $preOrder = $this->warehousePreOrderService->getPreOrderByPreOrderListingCode($preOrderListingCode);
            $warehouse = $this->warehousePreOrderService->getWarehouseByCode($preOrder->warehouse_code);
            $productsInPreOrder = $this->warehousePreOrderService->getProductsInPreOrder($filterParameters, $preOrderListingCode, 10);

            $total_amount = $productsInPreOrder['total_amount'];
            $productsInPreOrder = $productsInPreOrder['productsInPreOrder'];

            $warehouseCode = $warehouse->warehouse_code;

            //dd($vendors,$preOrder,$warehouse,$productsInPreOrder,$warehouse,$warehouseCode);
            return view($this->loadViewData($this->module . $this->view . 'pre-order-detail'), compact('productsInPreOrder', 'warehouseCode', 'filterParameters', 'preOrderListingCode', 'warehouse', 'preOrder', 'vendors', 'total_amount'));
        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

//    for ajax request
    public function getProductsOfVendor(Request $request)
    {

        try {
            $vendor_code = $request->post('vendor_code');
            $productsInVendor = WarehousePreOrderFilter::getProductsInVendor($vendor_code);

            return $productsInVendor;
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getVariantsOfProduct(Request $request)
    {
        try {
            $product_code = $request->post('product_code');
            $varientsInProduct = WarehousePreOrderFilter::getVariantsOfProduct($product_code);

            return $varientsInProduct;
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function listVendorsInPreOrders(Request $request, $warehousePreOrderListingCode)
    {

        try {
            $filterParameters = [
                'vendor_name' => $request->vendor_name
            ];


            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreOrderListingCode);

            $warehouseCode = $warehousePreOrderListing->warehouse_code;

            $vendors = WarehousePreOrderHelper::getVendorsInvolvedInWarehousePreOrdersForAdmin($warehousePreOrderListingCode, $filterParameters);
            return view($this->loadViewData($this->module . $this->view . 'vendors-list'), compact(
                'vendors', 'filterParameters', 'warehousePreOrderListingCode', 'warehousePreOrderListing', 'warehouseCode'));

        } catch (Exception $exception) {
            return redirect()->route('admin.warehouse-pre-orders.index')->with('danger', $exception->getMessage());
        }
    }

    public function getStorePreOrderProductsByVendor(Request $request, $vendorCode,$warehousePreOrderListingCode){
        try{

           // dd($request->all());
            $filterParameters = [
                'vendor_code' => ($request->get('vendor_code')) ?$request->get('vendor_code'):$vendorCode,
                'product_code' => $request->get('product_code'),
                'product_variant_code' => $request->get('product_variant_code'),
            ];


            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreOrderListingCode);
            $vendors = $this->vendorService->findVendorByCode($vendorCode);

            $storePreOrderProducts = StorePreOrderDetailHelper::newgetStorePreOrderAllStatusDetailsByVendorCodeWithFilter($vendorCode,$warehousePreOrderListingCode,$warehousePreOrderListing->warehouse_code,$filterParameters);
           // dd($storePreOrderProducts);

            $productPackagingFormatter = new ProductPackagingFormatter();
            $storePreOrderProducts = $storePreOrderProducts->map(function ($storePreOrderProduct) use ($productPackagingFormatter) {
                $storePreOrderProduct->sub_total = $storePreOrderProduct->total_micro_ordered_quantity * ($storePreOrderProduct->vendor_price);
                $storePreOrderProduct->pre_order_rate = $storePreOrderProduct->total_micro_ordered_quantity * ($storePreOrderProduct->unit_price);
                $with=['microPackageType','unitPackageType','macroPackageType','superPackageType'];
                $productPackagingHistory = ProductPackagingHistory::with($with)->where('product_packaging_history_code',$storePreOrderProduct->product_packaging_history_code)->first();

                $arr =[];
                if ($productPackagingHistory){
                    if ($productPackagingHistory->micro_unit_code){
                        $arr[1] =$productPackagingHistory->microPackageType->package_name;
                    }
                    if ($productPackagingHistory->unit_code){
                        $arrKey = intval($productPackagingHistory->micro_to_unit_value);
                        $arr[$arrKey] =$productPackagingHistory->unitPackageType->package_name;
                    }
                    if ($productPackagingHistory->macro_unit_code){
                        $arrKey = intval($productPackagingHistory->micro_to_unit_value *
                            $productPackagingHistory->unit_to_macro_value);
                        $arr[$arrKey] =$productPackagingHistory->macroPackageType->package_name;
                    }
                    if ($productPackagingHistory->super_unit_code){

                        $arrKey = intval($productPackagingHistory->micro_to_unit_value *
                            $productPackagingHistory->unit_to_macro_value *
                            $productPackagingHistory->macro_to_super_value);

                        $arr[$arrKey] =$productPackagingHistory->superPackageType->package_name;
                    }
                }
                $arr=array_reverse($arr,true);
                $storePreOrderProduct->product_packaging_detail = $productPackagingFormatter->formatPackagingCombination($storePreOrderProduct->total_micro_ordered_quantity,$arr);
               // $storePreOrderProduct->product_packaging_detail =$arr;
                return $storePreOrderProduct;
            });
          // dd($storePreOrderProducts);
            $total_amount = $storePreOrderProducts->sum('sub_total');
            $total_pre_order_rate = $storePreOrderProducts->sum('pre_order_rate');


            return view($this->loadViewData($this->module.$this->view.'all-status-store-pre-order-detail'),compact(
                'storePreOrderProducts','vendors','filterParameters','warehousePreOrderListingCode','vendorCode','warehousePreOrderListing','total_amount','total_pre_order_rate'));

        }catch (Exception $exception){
            return redirect()->route('admin.warehouse-pre-orders.vendors-list',$warehousePreOrderListingCode)->with('danger', $exception->getMessage());
        }

    }

    public function getFinalizedStorePreOrderProductsByVendor(Request $request, $vendorCode,$warehousePreOrderListingCode){

        try{

            $filterParameters = [
                'vendor_code' => ($request->get('vendor_code')) ?$request->get('vendor_code'):$vendorCode,
                'product_code' => $request->get('product_code'),
                'product_variant_code' => $request->get('product_variant_code'),
            ];


            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreOrderListingCode);
            $vendors = $this->vendorService->findVendorByCode($vendorCode);

            $storePreOrderProducts = StorePreOrderDetailHelper::newgetStorePreOrderDetailsByVendorCodeWithFilter($vendorCode,$warehousePreOrderListingCode,$warehousePreOrderListing->warehouse_code,$filterParameters);

            $storePreOrderProducts = $storePreOrderProducts->map(function ($storePreOrderProduct) {
                $storePreOrderProduct->sub_total = $storePreOrderProduct->total_ordered_quantity * ($storePreOrderProduct->vendor_price);
                $storePreOrderProduct->pre_order_rate = $storePreOrderProduct->total_ordered_quantity * ($storePreOrderProduct->unit_price);
                return $storePreOrderProduct;
            });

            $total_amount = $storePreOrderProducts->sum('sub_total');
            $total_pre_order_rate = $storePreOrderProducts->sum('pre_order_rate');


            return view($this->loadViewData($this->module.$this->view.'store-pre-order-detail'),compact(
                'storePreOrderProducts','vendors','filterParameters','warehousePreOrderListingCode','vendorCode','warehousePreOrderListing','total_amount','total_pre_order_rate'));

        }catch (Exception $exception){
            return redirect()->route('admin.warehouse-pre-orders.vendors-list',$warehousePreOrderListingCode)->with('danger', $exception->getMessage());
        }
    }


    public function exportStorePreOrderProductsByVendor(Request $request, $vendorCode, $warehousePreOrderListingCode)
    {

        try {
            $filterParameters = [
                'vendor_code' => ($request->get('vendor_code')) ? $request->get('vendor_code') : $vendorCode,
                'product_code' => $request->get('product_code'),
                'product_variant_code' => $request->get('product_variant_code'),
            ];

            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreOrderListingCode);
            $vendors = $this->vendorService->findVendorByCode($vendorCode);


            $storePreOrderProducts = StorePreOrderDetailHelper::newgetStorePreOrderDetailsByVendorCodeWithFilter($vendorCode, $warehousePreOrderListingCode,$warehousePreOrderListing->warehouse_code,$filterParameters);
            $storePreOrderProducts = $storePreOrderProducts->map(function ($storePreOrderProduct) {
                $storePreOrderProduct->sub_total = $storePreOrderProduct->total_ordered_quantity * ($storePreOrderProduct->vendor_price);
                return $storePreOrderProduct;
            });
            return (new WarehousePreOrderProductExport($warehousePreOrderListing, $storePreOrderProducts, $vendors, $this->module, $this->view));
        } catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function exportallStatusStorePreOrderProductsByVendor(Request $request, $vendorCode, $warehousePreOrderListingCode){


        try {
            $filterParameters = [
                'vendor_code' => ($request->get('vendor_code')) ? $request->get('vendor_code') : $vendorCode,
                'product_code' => $request->get('product_code'),
                'product_variant_code' => $request->get('product_variant_code'),
            ];

            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreOrderListingCode);
            $vendors = $this->vendorService->findVendorByCode($vendorCode);


            $storePreOrderProducts = StorePreOrderDetailHelper::newgetStorePreOrderAllStatusDetailsByVendorCodeWithFilter($vendorCode, $warehousePreOrderListingCode,$warehousePreOrderListing->warehouse_code,$filterParameters);
            //dd($storePreOrderProducts);
            $productPackagingFormatter = new ProductPackagingFormatter();

            $storePreOrderProducts = $storePreOrderProducts->map(function ($storePreOrderProduct) use ($productPackagingFormatter){
                $storePreOrderProduct->sub_total = $storePreOrderProduct->total_ordered_quantity * ($storePreOrderProduct->vendor_price);

                $with=['microPackageType','unitPackageType','macroPackageType','superPackageType'];
                $productPackagingHistory = ProductPackagingHistory::with($with)->where('product_packaging_history_code',$storePreOrderProduct->product_packaging_history_code)->first();

                $arr =[];
                if ($productPackagingHistory){
                    if ($productPackagingHistory->micro_unit_code){
                        $arr[1] =$productPackagingHistory->microPackageType->package_name;
                    }
                    if ($productPackagingHistory->unit_code){
                        $arrKey = intval($productPackagingHistory->micro_to_unit_value);
                        $arr[$arrKey] =$productPackagingHistory->unitPackageType->package_name;
                    }
                    if ($productPackagingHistory->macro_unit_code){
                        $arrKey = intval($productPackagingHistory->micro_to_unit_value *
                            $productPackagingHistory->unit_to_macro_value);
                        $arr[$arrKey] =$productPackagingHistory->macroPackageType->package_name;
                    }
                    if ($productPackagingHistory->super_unit_code){

                        $arrKey = intval($productPackagingHistory->micro_to_unit_value *
                            $productPackagingHistory->unit_to_macro_value *
                            $productPackagingHistory->macro_to_super_value);

                        $arr[$arrKey] =$productPackagingHistory->superPackageType->package_name;
                    }
                }
                $arr=array_reverse($arr,true);
                 $arr=$productPackagingFormatter->getProductPackagingsWithPrice($storePreOrderProduct->total_micro_ordered_quantity,$arr);
                $arr = array_map(function ($singleArray) use($storePreOrderProduct){
                    $unitPrice=$singleArray['micro_quantity'] *$storePreOrderProduct->unit_price;
                    $singleArray['package_unit_rate'] = $unitPrice;
                    $singleArray['product_package_price'] = $unitPrice* $singleArray['package_quantity'];
                    return $singleArray;
                },$arr);
                $storePreOrderProduct->product_packagings_price=$arr;

                return $storePreOrderProduct;
            });
          // dd($storePreOrderProducts);
            return (new WarehousePreOrderProductExport($warehousePreOrderListing, $storePreOrderProducts, $vendors, $this->module, $this->view));
        } catch (Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function cloneProductsFromSourceToDestinationListingCode(ClonePreOrderProductsRequest $request)
    {

        try {
            $validatedData = $request->validated();
            $validatedData['created_by'] = getAuthUserCode();
            DB::beginTransaction();

            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($validatedData['destination_listing_code']);
            if ($warehousePreOrder->isPastEndTime()) {
                throw new Exception('End Time Completed of Destination Pre Order.Cannot add products after End time');
            }
            $clonedProducts = $this->warehousePreOrderProductService->cloneProductsFromSourceToDestinationListingCode($validatedData);
            DB::commit();
            return redirect()->back()->with('success', 'Warehouse Products Cloned into Pre Order successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

//    done by Govinda

    public function getOrderQtyByStore(Request $request, $vendorCode,$warehousePreorderListingCode,$productCode){
        try{
           $productVariantCode= $request->productVariantCode ?? null;
            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreorderListingCode);
            $storePreOrderqty = StorePreOrderDetailHelper::getStoreOrderQty($vendorCode,$warehousePreorderListingCode,$warehousePreOrderListing->warehouse_code,$productCode,$productVariantCode);
            $productPackagingFormatter = new ProductPackagingFormatter();
            $storePreOrderqty = $storePreOrderqty->map(function ($storePreOrderProduct) use ($productPackagingFormatter) {
                $storePreOrderProduct->sub_total = $storePreOrderProduct->total_ordered_quantity * ($storePreOrderProduct->vendor_price);
                $storePreOrderProduct->pre_order_rate = $storePreOrderProduct->total_ordered_quantity * ($storePreOrderProduct->unit_price);
                $with=['microPackageType','unitPackageType','macroPackageType','superPackageType'];
                $productPackagingHistory = ProductPackagingHistory::with($with)->where('product_packaging_history_code',$storePreOrderProduct->product_packaging_history_code)->first();

                $arr =[];
                if ($productPackagingHistory){
                    if ($productPackagingHistory->micro_unit_code){
                        $arr[1] =$productPackagingHistory->microPackageType->package_name;
                    }
                    if ($productPackagingHistory->unit_code){
                        $arrKey = intval($productPackagingHistory->micro_to_unit_value);
                        $arr[$arrKey] =$productPackagingHistory->unitPackageType->package_name;
                    }
                    if ($productPackagingHistory->macro_unit_code){
                        $arrKey = intval($productPackagingHistory->micro_to_unit_value *
                            $productPackagingHistory->unit_to_macro_value);
                        $arr[$arrKey] =$productPackagingHistory->macroPackageType->package_name;
                    }
                    if ($productPackagingHistory->super_unit_code){

                        $arrKey = intval($productPackagingHistory->micro_to_unit_value *
                            $productPackagingHistory->unit_to_macro_value *
                            $productPackagingHistory->macro_to_super_value);

                        $arr[$arrKey] =$productPackagingHistory->superPackageType->package_name;
                    }

                }
                $arr=array_reverse($arr,true);
                $storePreOrderProduct->product_packaging_detail = $productPackagingFormatter->formatPackagingCombination($storePreOrderProduct->total_micro_ordered_quantity,$arr);
                // $storePreOrderProduct->product_packaging_detail =$arr;
                return $storePreOrderProduct;
            });

            //dd($storePreOrderqty);//product_packaging_detail
            if ($request->ajax()) {
                return view('AlpasalWarehouse::admin.warehouse-pre-orders.common.store-order-qty-table',
                    compact('storePreOrderqty'))->render();
            }
            return $storePreOrderqty;

        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }
    public function getFinalizedOrderQtyByStore(Request $request, $vendorCode,$warehousePreorderListingCode,$productCode){


        try{

            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreorderListingCode);
            $storePreOrderqty = StorePreOrderDetailHelper::getFinalizedStoreOrderQty($vendorCode,$warehousePreorderListingCode,$productCode,$warehousePreOrderListing->warehouse_code);

            if ($request->ajax()) {
                return view('AlpasalWarehouse::admin.warehouse-pre-orders.common.store-order-qty-finalized-table',
                    compact('storePreOrderqty'))->render();
            }
            return $storePreOrderqty;

        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

}
