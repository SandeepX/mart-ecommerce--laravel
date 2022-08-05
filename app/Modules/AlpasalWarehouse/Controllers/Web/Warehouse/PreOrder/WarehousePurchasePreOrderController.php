<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;


use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderPurchaseHelper;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePrePurchaseOrderRequest;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderPurchaseService;
use App\Modules\AlpasalWarehouse\Exports\Warehouse\WarehousePurchasePreOrderExport;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderHelper;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderDetailHelper;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;

class WarehousePurchasePreOrderController extends BaseController
{
    public $title = 'Alpasal Warehouse Purchase Pre-Order';
    public $base_route = 'warehouse.warehouse-pre-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-preorder-purchase-orders.';

    private $warehousePreOrderPurchaseService;
    private $warehouseService;
    private $warehousePreOrderService;

    public function __construct(
        WarehousePreOrderPurchaseService $warehousePreOrderPurchaseService,
        WarehouseService $warehouseService,
        WarehousePreOrderService  $warehousePreOrderService
    ){
        $this->middleware('permission:View List Of Vendors For Pre Orders', ['only' => 'listVendorsForPreOrders']);
        $this->middleware('permission:Place Order For Pre Order', ['only' => 'getPlaceOrderPage', 'storePreOrderPurchaseOrder', 'exportPreOrderPurchaseOrder']);

        $this->warehousePreOrderPurchaseService = $warehousePreOrderPurchaseService;
        $this->warehouseService = $warehouseService;
        $this->warehousePreOrderService = $warehousePreOrderService;
    }

    public function listVendorsForPreOrders(Request $request,$warehousePreOrderListingCode){
        try{

            $filterParameters=[
                'vendor_name'=>$request->vendor_name
            ];
            $vendors = WarehousePreOrderHelper::getVendorsInvolvedInWarehousePreOrderListingStorePreOrders($warehousePreOrderListingCode,$filterParameters);
            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreOrderListingCode);

            return view($this->loadViewData($this->module.$this->view.'vendors-list'),compact(
                'vendors','filterParameters','warehousePreOrderListingCode','warehousePreOrderListing'));

        }catch (Exception $exception){
            return redirect()->route('warehouse.warehouse-pre-orders.index')->with('danger', $exception->getMessage());
        }
    }

    public function getPlaceOrderPage($warehousePreOrderListingCode,$vendorCode){
        try{

            $authWarehouseCode= getAuthWarehouseCode();
            $hasOrderBeenPlaced = WarehousePreOrderPurchaseHelper::isPreOrderPurchasePlacedToVendor(
                $vendorCode,$authWarehouseCode,$warehousePreOrderListingCode);
            $warehousePurchaseOrdersProductsDetails = collect();
            $warehousePurchaseOrdersDetails = collect();
            $storePreOrderProducts = collect();

            if($hasOrderBeenPlaced){
                $warehousePurchaseOrdersProductsDetails =WarehousePreOrderPurchaseHelper::getVendorWisePurchasedDetailsOfPreOrderofWarehouse($vendorCode,$authWarehouseCode,$warehousePreOrderListingCode);
                $warehousePurchaseOrdersDetails = $this->warehousePreOrderPurchaseService->findWarehousePreOrderPurchaseOfVendor($vendorCode,$authWarehouseCode,$warehousePreOrderListingCode);

            }else{
                $storePreOrderProducts = StorePreOrderDetailHelper::getVendorWisePreOrderableProductsForPurchaseOrder(
                    $vendorCode,$warehousePreOrderListingCode,$authWarehouseCode);
                $storePreOrderProducts = $storePreOrderProducts->map(function ($storePreOrderProduct) {
                    $storePreOrderProduct->sub_total = $storePreOrderProduct->total_ordered_quantity * ($storePreOrderProduct->vendor_price);
                    return $storePreOrderProduct;
                });
            }
            return view($this->loadViewData($this->module.$this->view.'create-purchase-order'),compact(
                'storePreOrderProducts','warehousePreOrderListingCode','vendorCode','hasOrderBeenPlaced','warehousePurchaseOrdersProductsDetails','warehousePurchaseOrdersDetails'));

        }catch (Exception $exception){
            return redirect()->route('warehouse.warehouse-pre-orders.vendors-list',$warehousePreOrderListingCode)->with('danger', $exception->getMessage());
        }

    }

    public function storePreOrderPurchaseOrder(WarehousePrePurchaseOrderRequest $request,$warehousePreOrderListingCode,$vendorCode){
        try{
            $validated = $request->validated();
            $purchaseOrder = $this->warehousePreOrderPurchaseService->saveWarehousePurchaseOrderFromPreOrder($validated,$warehousePreOrderListingCode,$vendorCode);
            return redirect()->back()->with('success', 'Purchase order to vendor placed successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage())->withInput();
        }
    }

    public function exportPreOrderPurchaseOrder($warehousePreOrderListingCode, $vendorCode){
        try {
            $storePreOrderProducts = StorePreOrderDetailHelper::newgetStorePreOrderDetailsByVendorCodeWithFilter($vendorCode, $warehousePreOrderListingCode, getAuthWarehouseCode());
            $storePreOrderProducts = $storePreOrderProducts->sortByDesc('total_ordered_quantity');
            $warehouse = $this->warehouseService->findOrFailWarehouseByCode(getAuthWarehouseCode());
            return (new WarehousePurchasePreOrderExport($storePreOrderProducts, $warehouse->warehouse_name));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
