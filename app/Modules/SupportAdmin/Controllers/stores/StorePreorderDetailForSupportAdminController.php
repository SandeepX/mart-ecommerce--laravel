<?php


namespace App\Modules\SupportAdmin\Controllers\stores;

use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehouseStorePreOrderService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use Illuminate\Http\Request;
use Exception;

class StorePreorderDetailForSupportAdminController extends BaseController
{

    public $title = 'Store Detail For Admin Support';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';


    private $view = 'stores.store-preorder.';

    public $warehouseStorePreOrderService;

    public function __construct(WarehouseStorePreOrderService $warehouseStorePreOrderService)
    {
        $this->warehouseStorePreOrderService = $warehouseStorePreOrderService;
    }

    public function getStorePreOrderForSupportAdmin($storeCode, Request $request)
    {
        try{
            $response = [];
            $filterParameters=[
                'pre_order_name' => $request->get('pre_order_name'),
                'status' => $request->get('status'),
                'payment_status' =>$request->get('payment_status'),
                'start_time' => $request->get('start_time'),
                'end_time' => $request->get('end_time'),
            ];
            $preOrderStatuses = StorePreOrder::STATUSES;
            $preOrdersListing = StorePreOrderHelper::filterStorePreOrder(
                $storeCode,$filterParameters,20);

            $response['html'] = view($this->module . $this->view . 'pre-order-table',
                compact('preOrdersListing',
                    'preOrderStatuses',
                    'filterParameters',
                    'storeCode'))->render();
            return response()->json($response);
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getStoreOrderDetailForSupportAdmin($storePreOrderCode)
    {
        try {
            $warehouseCode = StorePreOrderHelper::getWarehouseCodeByStorePreOrderCode($storePreOrderCode);

            $storePreOrderDetails = $this->warehouseStorePreOrderService->getStorePreOrderDetailForWarehouseMamata($storePreOrderCode, $warehouseCode);

            $storePreOrder = $storePreOrderDetails['store_pre_order'];
            $storePreOrderStatusLogs = $storePreOrderDetails['store_pre_order']['storePreOrderStatusLogs'];
            $taxableOrderDetails = collect($storePreOrderDetails['taxable_order_details']);
            $taxableOrderProducts = $storePreOrderDetails['taxable_order_products'];
            $nonTaxableOrderDetails = collect($storePreOrderDetails['non_taxable_order_details']);
            $nonTaxableOrderProducts = $storePreOrderDetails['non_taxable_order_products'];

            $response['html'] = view($this->module . $this->view .'detail-preorder-modal',
                compact('storePreOrder', 'taxableOrderDetails', 'storePreOrderStatusLogs',
                'taxableOrderProducts',
                    'nonTaxableOrderDetails',
                    'nonTaxableOrderProducts'))->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}


