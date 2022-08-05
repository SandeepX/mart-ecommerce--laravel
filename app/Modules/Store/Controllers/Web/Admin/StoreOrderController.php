<?php

namespace App\Modules\Store\Controllers\Web\Admin;

use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Exports\StoreOrderExport;
use App\Modules\Store\Helpers\StoreOrderFilter;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Requests\StoreOrderStatusRequest;
use App\Modules\Store\Services\Bill\StoreOrderBillService;
use App\Modules\Store\Services\StoreOrderService;
use App\Modules\Store\Services\StoreService;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StoreOrderController extends BaseController
{
    public $title = 'Store Order';
    public $base_route = 'admin.store.orders';
    public $sub_icon = 'file';
    public $module = 'Store::';
    public $view = 'admin.store-order.';

    private $storeOrderService;
    private $storeService,$storeOrderBillService,$warehouseService;

    public function __construct(
        StoreOrderService $storeOrderService,
        StoreService $storeService,
        StoreOrderBillService $storeOrderBillService,
        WarehouseService $warehouseService

    )
    {
        $this->middleware('permission:View Store Order List', ['only' => ['index', 'filter']]);
        $this->middleware('permission:Show Store Order', ['only' => ['show']]);
        $this->middleware('permission:Verify Store Order', ['only' => ['updateStoreOrderDeliveryStatus']]);

        $this->storeOrderService = $storeOrderService;
        $this->storeService = $storeService;
        $this->storeOrderBillService = $storeOrderBillService;
        $this->warehouseService = $warehouseService;
    }

    public function index(Request $request)
    {
        try {
            $filterParameters = [
                'store_order_code' => $request->get('store_order_code'),
                'store_name' => $request->get('store_name'),
                'delivery_status' => $request->get('delivery_status'),
                'warehouse_code' => $request->get('warehouse_code'),
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
            $storeOrders= StoreOrderFilter::filterPaginatedStoreOrdersForAdmin($filterParameters,StoreOrder::ROWS_PER_PAGE,$with);
            $warehouses = $this->warehouseService->getAllWarehouses();
            return view($this->loadViewData($this->module . $this->view . 'index'), compact('storeOrders'
                , 'storeOrderDeliveryStatuses','paymentStatuses', 'filterParameters','priceConditions','warehouses'));
        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage())->withInput();
        }

    }



    public function show($storeOrderCode)
    {
        $storeOrder = $this->storeOrderService->getOrderDetailsByAdmin($storeOrderCode);


        $warehouseCode = $storeOrder->wh_code;

        $storeOrder->load(['storeOrderDispatchDetail'=>function($query){
              $query->select('store_order_dispatch_detail_code','store_order_code','driver_name','contact_number','vehicle_type','vehicle_number','expected_delivery_time','created_by','created_at');
        }]);

       $taxabilityGroupedItems = $storeOrder->details->groupBy('is_taxable_product');
        $taxableOrderDetails = collect();
        $taxableItemsData= [];
        $nonTaxableOrderDetails =collect();
        $nonTaxableItemsTotal = 0;

        if(isset($taxabilityGroupedItems[1])){
            $taxableGroupedItems = $taxabilityGroupedItems[1];
            $taxableOrderDetails = $taxableGroupedItems->map(function ($taxableItem) use ($warehouseCode){

                $taxableItem['sub_total'] = (
                    $taxableItem->quantity * ($taxableItem->unit_rate)
                );

                if ($taxableItem->productPackageType){
                    $packageName =$taxableItem->productPackageType->package_name;
                }
                elseif ($taxableItem->product->package){
                    $packageName=$taxableItem->product->package->packageType->package_name;
                }
                else{
                    $packageName='-';
                }
                $taxableItem['package_name'] = (
                   $packageName
                );


               // $taxableItem['stock'] = (int)$whProductStock->current_stock;
                return $taxableItem;
            });

           // dd($taxableOrderDetails);

            $taxableItemsData['tax_excluded_amount'] = $taxableGroupedItems->sum('sub_total');
            $taxableItemsData['tax_amount'] = (StoreOrder::VAT_PERCENTAGE_VALUE /100)*  $taxableItemsData['tax_excluded_amount'];
            $taxableItemsData['total_amount'] = $taxableItemsData['tax_excluded_amount'] + $taxableItemsData['tax_amount'];
        }

        if(isset($taxabilityGroupedItems[0])) {

            $nonTaxableGroupedItems = $taxabilityGroupedItems[0];
            //dd($nonTaxableGroupedItems);
            $nonTaxableOrderDetails = $nonTaxableGroupedItems->map(function ($nonTaxableItem) use ($warehouseCode) {
                $nonTaxableItem['sub_total'] = (
                    $nonTaxableItem->quantity * ($nonTaxableItem->unit_rate)
                );

                if ($nonTaxableItem->productPackageType){
                    $packageName =$nonTaxableItem->productPackageType->package_name;
                }
                elseif ($nonTaxableItem->product->package){
                    $packageName=$nonTaxableItem->product->package->packageType->package_name;
                }
                else{
                    $packageName='-';
                }
                $taxableItem['package_name'] = (
                $packageName
                );
                return $nonTaxableItem;
            });
            $nonTaxableItemsTotal = roundPrice($nonTaxableGroupedItems->sum('sub_total'));
        }


       /// dd($taxableOrderDetails);

        return view($this->loadViewData($this->module . $this->view . 'show'), compact(
            'storeOrder',
            'taxableOrderDetails',
            'taxableItemsData',
            'nonTaxableItemsTotal',
            'nonTaxableOrderDetails'
        ));

    }

    public function generateStoreOrderPDF(Request $request,$storeOrderCode){

        try{
            $requestAction = $request->action;
            $storeOrder = $this->storeOrderService->findOrFailStoreOrderByCodeWith($storeOrderCode,['details','offlinePayments']);
            return $this->storeOrderBillService
                ->generateStoreOrderPdf($storeOrder,
                    $this->module . $this->view . 'store_order_pdf',$requestAction
                );
        }catch (Exception $exception){
            return redirect()->route('admin.store.orders.show',$storeOrderCode)->with('danger', $exception->getMessage());
        }
    }

    public function updateStoreOrderDeliveryStatus(StoreOrderStatusRequest $storeOrderStatusRequest, $storeOrderCode)
    {
//        $validatedStoreOrderStatus = $storeOrderStatusRequest->validated();
//        DB::beginTransaction();
//        try {
//            $this->storeOrderService->updateStoreOrderDeliveryStatus($validatedStoreOrderStatus, $storeOrderCode);
//            DB::commit();
//            return redirect()->back()->with('success', ' Status Updated for the Store Order Successfully');
//        } catch (Exception $exception) {
//            DB::rollback();
//            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
//        }
    }

    public function exportExcelStoreOrder(){
        try{
            $with = [
                'offlinePayments'
            ];
            $storeOrders= StoreOrderFilter::getStoreOrdersForExcell($with);

            return Excel::download(new StoreOrderExport($storeOrders), 'store_order_bill.xlsx');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
