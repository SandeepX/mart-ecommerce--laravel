<?php

namespace App\Modules\Store\Controllers\Web\Admin;

use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehouseStorePreOrderService;
use App\Modules\Application\Controllers\BaseController;

use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Services\PreOrder\StorePreOrderService;
use Illuminate\Http\Request;
use Exception;

class StorePreOrderController extends BaseController
{

    public $title = 'Store Pre Order';
    public $base_route = 'admin.warehouse.listings.store.pre-orders';
    public $sub_icon = 'file';
    public $module = 'Store::';
    public $view = 'admin.store-preorder.';

    private $warehousePreOrderService;
    private $warehouseStorePreOrderService;
    private $storePreOrderService;

    public function __construct(
        WarehousePreOrderService $warehousePreOrderService,
        WarehouseStorePreOrderService $warehouseStorePreOrderService,
        StorePreOrderService $storePreOrderService
    ){
        $this->warehousePreOrderService = $warehousePreOrderService;
        $this->warehouseStorePreOrderService = $warehouseStorePreOrderService;
        $this->storePreOrderService = $storePreOrderService;
    }

    public function index(Request $request,$warehousePreOrderListingCode){
        try{
            if($request->payment_status == 'paid'){
                $payment_status =  1;
            }
            elseif($request->payment_status == 'unpaid'){
                $payment_status = 0;
            }
            else{
                $payment_status =  null;
            }
            $filterParameters = [
                'store_preorder_code'=> $request->get('store_preorder_code'),
                'store_name'=> $request->get('store_name'),
                'start_time'=>$request->get('start_time'),
                'end_time'=>$request->get('end_time'),
                'total_amount'=>$request->get('total_amount'),
                'payment_status' => $payment_status,
                'price_condition'=>$request->get('price_condition'),
                'total_price'=>$request->get('total_price'),
                'status'=>$request->get('status'),
            ];
           //dd($filterParameters);
            $storePreOrderStatuses = StorePreOrder::STATUSES;
            $priceConditions = [
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $warehousePreOrder = $this->warehousePreOrderService->findOrFailWarehousePreOrderByCode($warehousePreOrderListingCode);
            $storePreOrders = StorePreOrderHelper::filterPreOrderWiseStorePreOrderforAdmin($warehousePreOrderListingCode,$filterParameters,10);

            return view($this->loadViewData($this->module . $this->view . 'index'),compact('warehousePreOrder','storePreOrders','filterParameters','priceConditions','storePreOrderStatuses'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }

    }

    public function show($storePreOrderCode){
        try {
            $storePreOrderDetails =$this->warehouseStorePreOrderService->getStorePreOrderDetailForAdmin($storePreOrderCode);

            $storePreOrder = $storePreOrderDetails['store_pre_order'];
            $storePreOrderStatusLogs = $storePreOrderDetails['store_pre_order']['storePreOrderStatusLogs'];
            $taxableOrderDetails = collect($storePreOrderDetails['taxable_order_details']);
            $taxableOrderProducts = $storePreOrderDetails['taxable_order_products'];
            $nonTaxableOrderDetails = collect($storePreOrderDetails['non_taxable_order_details']);
            $nonTaxableOrderProducts = $storePreOrderDetails['non_taxable_order_products'];
            $storePreOrderStatus = $this->warehouseStorePreOrderService->changeableStatus($storePreOrderCode);
            $storePreOrderDispatchDetail = $storePreOrderDetails['storePreOrderDispatchDetail'];

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
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
