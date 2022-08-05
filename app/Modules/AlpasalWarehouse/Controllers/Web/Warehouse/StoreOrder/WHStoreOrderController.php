<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\StoreOrder;

use App\Exceptions\Custom\StockUnavailibilityException;
use App\Modules\AlpasalWarehouse\Helpers\Store\StoreWarehouseHelper;
use App\Modules\AlpasalWarehouse\Helpers\Store\WHStoreOrderFilter;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockHelper;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Requests\StoreOrder\WHStoreOrderStatusUpdateRequest;

use App\Modules\AlpasalWarehouse\Services\StoreOrder\WHStoreOrderService;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Services\Bill\StoreOrderBillService;
use App\Modules\Store\Services\StoreService;
use App\Modules\User\Models\User;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WHStoreOrderController extends BaseController
{
    public $title = 'WH Store Order';
    public $base_route = 'warehouse.store.orders';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';
    public $view = 'warehouse.store-order.';

    private $whStoreOrderService,$warehouseService;
    private $storeService,$storeOrderBillService;


    const ROWS_PER_PAGE = 10;

    public function __construct(
        WHStoreOrderService $whStoreOrderService,
        WarehouseService  $warehouseService,
        StoreService $storeService,
        StoreOrderBillService $storeOrderBillService
    )
    {
        $this->middleware('permission:View WH Store Order List', ['only' => ['index', 'getOrdersByStore']]);
        $this->middleware('permission:Show WH Store Order', ['only' => ['show']]);
        $this->middleware('permission:Change Status Of WH Store Order', ['only' => ['updateStoreOrderDeliveryStatus']]);

        $this->whStoreOrderService = $whStoreOrderService;
        $this->warehouseService = $warehouseService;
        $this->storeService = $storeService;
        $this->storeOrderBillService = $storeOrderBillService;

    }
    public function index(Request $request)
    {
        try {

            $filterParameters = [
                'store_name_code' => $request->get('store_name_code'),
                'delivery_status' => $request->get('delivery_status'),
                'order_date_from' => $request->get('order_date_from'),
                'order_date_to' => $request->get('order_date_to'),
            ];
            $storeOrderDeliveryStatuses = StoreOrder::DELIVERY_STATUSES;

            $storeOrders = WHStoreOrderFilter::filterWHStoreOrdersByStore($filterParameters, self::ROWS_PER_PAGE);

            return view($this->loadViewData($this->module.$this->view.'.index'),
                compact('storeOrders', 'storeOrderDeliveryStatuses', 'filterParameters'));
        } catch (Exception $exception) {
            return redirect()->route('warehouse.dashboard')->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function getOrdersByStore(Request $request, $storeCode)
    {
        try {

            $filterParameters = [
                'store_order_code' => $request->get('store_order_code'),
                'delivery_status' => $request->get('delivery_status'),
                'payment_status' => $request->get('payment_status'),
                'order_date_from' => $request->get('order_date_from'),
                'order_date_to' => $request->get('order_date_to'),
                'price_condition' => $request->get('price_condition'),
                'total_price' => $request->get('total_price'),
            ];
            $with = [
                'offlinePayments'
            ];
            $storeOrderDeliveryStatuses = StoreOrder::DELIVERY_STATUSES;
            $paymentStatuses=['unpaid','pending', 'verified','rejected'];
            $priceConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $storeName = $this->storeService->findOrFailStoreByCodeWith($storeCode, [],'store_name');
            $storeOrders= WHStoreOrderFilter::filterWHStoreOrdersByStoreCode($filterParameters, $storeCode, self::ROWS_PER_PAGE,$with);
//            dd($storeOrders);
            return view($this->loadViewData($this->module.$this->view.'store_orders'), compact('storeOrders','storeName',
                'storeCode', 'storeOrderDeliveryStatuses','paymentStatuses', 'filterParameters','priceConditions'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }

    public function show($storeOrderCode)
    {
        $store_order = $this->whStoreOrderService->getWarehouseStoreOrderDetailsByAdmin($storeOrderCode);
        $storeOrder = $store_order['store_order'];
        $storeOrderStatus = $store_order['store_order_statuses'];
        $taxableOrderDetails = collect($store_order['taxable_order_details']);
        $taxableItemsData = $store_order['taxable_items_data'];
        $nonTaxableItemsTotal = $store_order['non_taxable_items_total'];
        $nonTaxableOrderDetails = collect($store_order['non_taxable_order_details']);
        return view($this->loadViewData($this->module . $this->view . 'show'),
            compact('storeOrder',
                'storeOrderStatus',
                'taxableOrderDetails',
                'taxableItemsData',
                'nonTaxableItemsTotal',
                'nonTaxableOrderDetails'
            ));

    }

    public function generateStoreOrderPDF(Request $request,$storeOrderCode){

        try{
            $requestAction = $request->action;
            $storeOrder = $this->whStoreOrderService->findOrFailStoreOrderByCodeForAuthWH(
                $storeOrderCode,['details.productPackageType','details','offlinePayments']);

            return $this->storeOrderBillService
                ->generateStoreOrderPdf($storeOrder,
                    $this->module . $this->view . 'store_order_pdf',$requestAction
                );
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage().$exception->getFile().$exception->getLine());
        }
    }

    public function updateStoreOrderDeliveryStatus(WHStoreOrderStatusUpdateRequest $storeOrderStatusRequest,$storeOrderCode)
    {
        $storeOrder = $this->whStoreOrderService->findOrFailStoreOrderByCodeForAuthWH($storeOrderCode);

        $validatedStoreOrderStatus = $storeOrderStatusRequest->validated();
        try {
            if($storeOrder->has_merged){
                throw new Exception('Cannot Update Status After Bill Merged');
            }
            DB::beginTransaction();

            $warehouseUpdateDetail = $this->whStoreOrderService->updateStoreOrderDeliveryStatusWithNotifications($validatedStoreOrderStatus, $storeOrderCode);

            DB::commit();
            return redirect()->back()->with('success', 'Status Updated for the Store Order Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            if($exception instanceof StockUnavailibilityException){
                return redirect()->back()
                    ->with('danger', $exception->getMessage())
                    ->with('stock_unavailable_items',$exception->getData())
                    ->withInput();
            }
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }
}
