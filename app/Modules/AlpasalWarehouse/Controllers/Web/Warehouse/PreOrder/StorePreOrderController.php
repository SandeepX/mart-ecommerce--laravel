<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;


use App\Exceptions\Custom\NotEnoughProductStockException;
use App\Modules\AlpasalWarehouse\Exports\StorePreOrderExport;
use App\Modules\AlpasalWarehouse\Exports\StorePreOrderPdfExport;
use App\Modules\AlpasalWarehouse\Requests\StorePreOrder\StorePreOrderDetailUpdateRequest;
use App\Modules\AlpasalWarehouse\Requests\StorePreOrder\StorePreOrderStatusUpdateRequest;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehouseStorePreOrderService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderFilter;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Services\PreOrder\StorePreOrderBillService;
use Illuminate\Http\Request;

use Exception;
use Maatwebsite\Excel\Facades\Excel;

class StorePreOrderController extends BaseController
{

    public $title = 'Store PreOrder';
    public $base_route = 'warehouse.warehouse-pre-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-pre-orders.store-pre-orders.';

    private $warehouseStorePreOrderService,$storePreOrderBillService;
    private $warehousePreOrderService;

    public function __construct(
        WarehouseStorePreOrderService $warehouseStorePreOrderService,
        StorePreOrderBillService $storePreOrderBillService,
        WarehousePreOrderService  $warehousePreOrderService
    ){
        $this->middleware('permission:View List Of Store Pre Orders', ['only' => ['getStorePreOrders']]);
        $this->middleware('permission:View Store Pre Order Details', ['only' => [
            'showPreOrderDetail',
            'generatePreOrderExcelBill',
            'generatePreOrderPdfBill'
        ]]);
        $this->middleware(
            'permission:Update Pre Order Status', ['only' => ['updatePreOrderStatus']]);
        $this->warehouseStorePreOrderService = $warehouseStorePreOrderService;
        $this->storePreOrderBillService = $storePreOrderBillService;
        $this->warehousePreOrderService = $warehousePreOrderService;
    }

    public function getStorePreOrders(Request $request,$warehousePreOrderCode){
        try {

            $filterParameters = [
                'warehouse_preorder_listing_code' => $warehousePreOrderCode,
                'store_preorder_code' => $request->get('store_preorder_code'),
                'store_name' => $request->get('store_name'),
                'store_code' => $request->get('store_code'),
                'warehouse_code'=>getAuthWarehouseCode(),
                'status' => $request->get('status'),
                'payment_status' => $request->get('payment_status'),
                'order_date_from' => $request->get('order_date_from'),
                'order_date_to' => $request->get('order_date_to'),
            ];


            // dd($filterParameters);
            $with = [
                'store','storePreOrderEarlyCancellation'
            ];
            $storePreOrderStatuses = StorePreOrder::STATUSES;
            $storePreOrders= StorePreOrderFilter::newfilterPaginatedStorePreOrders($filterParameters,10,$with);
            $warehousePreOrderListing = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreOrderCode);
            return view($this->loadViewData($this->module . $this->view . 'index'), compact('storePreOrders'
                , 'storePreOrderStatuses', 'filterParameters','warehousePreOrderListing'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    //show store pre order detail
    public function showPreOrderDetail($storePreOrderCode){
        try {
            $storePreOrderDetails =$this->warehouseStorePreOrderService->getStorePreOrderDetailForWarehouse($storePreOrderCode);
            $storePreOrder = $storePreOrderDetails['store_pre_order'];
            $storePreOrderStatusLogs = $storePreOrderDetails['store_pre_order']['storePreOrderStatusLogs'];
            $taxableOrderDetails = collect($storePreOrderDetails['taxable_order_details']);
            $taxableOrderProducts = $storePreOrderDetails['taxable_order_products'];
            $nonTaxableOrderDetails = collect($storePreOrderDetails['non_taxable_order_details']);
            $nonTaxableOrderProducts = $storePreOrderDetails['non_taxable_order_products'];
            $storePreOrderStatus = $this->warehouseStorePreOrderService->changeableStatus($storePreOrderCode);
            $storePreOrderDispatchDetail = $storePreOrderDetails['storePreOrderDispatchDetail'];

           // dd($storePreOrderDispatchDetail);
            return view($this->loadViewData($this->module.$this->view.'show'),
                compact(
                    'storePreOrder',
                    'taxableOrderDetails',
                    'storePreOrderStatusLogs',
                    'taxableOrderProducts',
                    'nonTaxableOrderDetails',
                    'nonTaxableOrderProducts',
                    'storePreOrderStatus',
                    'storePreOrderDispatchDetail'
                ));
        } catch (Exception $exception) {
            return redirect()->route('warehouse.warehouse-pre-orders.index')->with('danger', $exception->getMessage());
        }
    }

    public function updatePreOrderDetail(StorePreOrderDetailUpdateRequest $request,
                                         $storePreOrderCode,$storePreOrderDetailCode){
        try{
            $validated= $request->validated();
            $this->warehouseStorePreOrderService->updateStorePreOrderDetailByWarehouse($validated,$storePreOrderCode,$storePreOrderDetailCode);
            return redirect()->back()->with('success', $this->title .' updated successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    //dispatch or cancel
    public function updatePreOrderStatus(StorePreOrderStatusUpdateRequest $request,$storePreOrderCode)
    {
        try{
            $validated= $request->validated();
            $this->warehouseStorePreOrderService->updateStorePreOrderstatusByWarehouseWithNotification($validated,$storePreOrderCode);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (Exception $exception){
            if ($exception instanceof NotEnoughProductStockException) {
                return redirect()->back()
                    ->with('danger', $exception->getMessage())
                    ->with('stock_unavailable_items',$exception->getData())
                    ->withInput();
            }
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function generatePreOrderExcelBill($storePreOrderCode){
        try{
            if(!StorePreOrderHelper::isStorePreOrderFinalizableByReason
            ($storePreOrderCode,'non_deleted_preorder_details')){
                throw new Exception(
                    'Cannot generate pdf : because the pre order contains all the deleted pre ordered items');

            }elseif(!StorePreOrderHelper::isStorePreOrderFinalizableByReason
            ($storePreOrderCode,'active_preorder_products')){
                throw new Exception(
                    'Cannot generate pdf : because the pre order contains all the inactive pre ordered items');
            }
            $storePreOrderDetails =$this->storePreOrderBillService->getStorePreOrderDetailForExcel($storePreOrderCode,getAuthWarehouseCode());
            $taxableOrderProducts = $storePreOrderDetails['taxable_order_products'];
            $nonTaxableOrderProducts = $storePreOrderDetails['non_taxable_order_products'];
            $storeOrderProducts= $taxableOrderProducts->merge($nonTaxableOrderProducts);

            return Excel::download(new StorePreOrderExport($storeOrderProducts), 'store_preorder_bill.xlsx');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function generatePreOrderPdfBill($storePreOrderCode){
        try{

            if(!StorePreOrderHelper::isStorePreOrderFinalizableByReason
            ($storePreOrderCode,'non_deleted_preorder_details')){
                throw new Exception(
                    'Cannot generate pdf : because the pre order contains all the deleted pre ordered items');

            }elseif(!StorePreOrderHelper::isStorePreOrderFinalizableByReason
            ($storePreOrderCode,'active_preorder_products')){
                throw new Exception(
                    'Cannot generate pdf : because the pre order contains all the inactive pre ordered items');
            }


            $storePreOrderDetails =$this->storePreOrderBillService->getStorePreOrderDetailForPdf($storePreOrderCode,getAuthWarehouseCode());
            $orderInfo = $storePreOrderDetails['order_info'];
            $storePreOrderDetailsWithChunk= $storePreOrderDetails['store_preorder_details'];
            $pdfExport = new StorePreOrderPdfExport($orderInfo,$storePreOrderDetailsWithChunk);

            return $pdfExport->export('download');


        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
