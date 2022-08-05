<?php


namespace App\Modules\SupportAdmin\Controllers\stores;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Helpers\StoreOrderFilter;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Services\StoreOrderService;
use App\Modules\SupportAdmin\Helpers\StoreTotalOrdersCompleteDetailHelper;
use Exception;
use Illuminate\Http\Request;

class StoreOrderCompleteDetailForAdminSupportController extends BaseController
{

    public $title = 'Store Detail For Admin Support';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    public $storeModule = 'Store::';
    public $storeView = 'admin.store-orders-details.';

    private $view = 'stores.';

    private $storeOrderService;

    public function __construct(StoreOrderService $storeOrderService)
    {
        $this->storeOrderService = $storeOrderService;
    }

    public function getStoreOrderAndPreorderForSupportAdmin($storeCode, Request $request)
    {
        try {
            $filterParameters = [
                'store_code' => $storeCode,
                'order_code' => $request->get('order_code '),
                'order_status' => $request->get('order_status'),
                'order_type' => $request->get('order_type'),
                'payment_status' => $request->get('payment_status'),
                'order_date_from' => $request->get('order_date_from'),
                'price_condition' => $request->get('price_condition'),
                'order_date_to' => $request->get('order_date_to'),
                'total_price' => $request->get('total_price'),
                'payable_price_from' => $request->get('payable_price_from'),
                'payable_price_to' => $request->get('payable_price_to'),
                'perPage' => $request->get('per_page') ?? 25,
                'page' =>(int) $request->get('page') ? $request->get('page'): 1
            ];

            $normalStatus = [];
            $preOrderStatus = [];

            if($filterParameters['order_status']){
                foreach($filterParameters['order_status'] as $key => $value){
                    str_starts_with($value, 'normal') ?
                        array_push($normalStatus,substr($value,7)) : array_push($preOrderStatus,substr($value,9));
                }
            }

            $paymentStatuses = ['unpaid', 'pending', 'verified', 'rejected'];
            $storeOrderDeliveryStatuses = [
                            'normal_pending' =>'Pending Normal Order',
                            'normal_accepted' => 'Accepted Normal Order',
                            'normal_cancelled' => 'Cancelled Normal Order',
                            'normal_ready_to_dispatch' => 'Ready To Dispatch Normal Order',
                            'normal_dispatched' => 'Dispatched Normal Order',
                            'normal_processing' => 'Processing Normal Order',
                            'normal_partially-accepted' => 'Partially Accepted Normal Order',
                            'normal_under-verification' => 'Under Verification Normal Order',
            ];

            $preorderStatus =
                [
                    'preorder_pending' =>'Pending PreOrder',
                    'preorder_cancelled' => 'Cancelled PreOrder',
                    'preorder_ready_to_dispatch' => 'Ready To Dispatch PreOrder',
                    'preorder_dispatched' => 'Dispatched PreOrder',
                    'preorder_processing' => 'Processing PreOrder',
                    'preorder_finalized' => 'Finalized PreOrder'
                ];
            $priceConditions = [
                'Greater Than >' => '>',
                'Less Than <' => '<',
                'Greater Than & Equal To >=' => '>=',
                'Less Than & Equal To <=' => '<=',
                'Equal To =' => '=',
            ];
            $storeOrders = StoreTotalOrdersCompleteDetailHelper::getStoreOrderAndPreorder($filterParameters,$normalStatus,$preOrderStatus);
           // dd($storeOrders);
            $response = [];
            $response['html'] = view($this->module . $this->view . 'store-order.store-all-orders-table',
                compact('storeOrders',
                    'storeOrderDeliveryStatuses',
                    'paymentStatuses',
                    'filterParameters',
                    'priceConditions',
                    'storeCode',
                'preorderStatus'
                )
            )->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getStoreOrderDetailForSupportAdmin($storeOrderCode)
    {
        try {

            $storeOrder = $this->storeOrderService->getOrderDetailsByAdmin($storeOrderCode);

            $warehouseCode = $storeOrder->wh_code;

            $storeOrder->load(['storeOrderDispatchDetail' => function ($query) {
                $query->select('store_order_dispatch_detail_code', 'store_order_code', 'vehicle_name', 'contact_number', 'vehicle_type', 'vehicle_number', 'expected_delivery_time', 'created_by', 'created_at');
            }]);

            $taxabilityGroupedItems = $storeOrder->details->groupBy('is_taxable_product');

            $taxableOrderDetails = collect();
            $taxableItemsData = [];
            $nonTaxableOrderDetails = collect();
            $nonTaxableItemsTotal = 0;

            if (isset($taxabilityGroupedItems[1])) {
                $taxableGroupedItems = $taxabilityGroupedItems[1];
                $taxableOrderDetails = $taxableGroupedItems->map(function ($taxableItem) use ($warehouseCode) {

                    $taxableItem['sub_total'] = (
                        $taxableItem->quantity * ($taxableItem->unit_rate)
                    );

                    return $taxableItem;
                });

                $taxableItemsData['tax_excluded_amount'] = roundPrice($taxableGroupedItems->sum('sub_total'));
                $taxableItemsData['tax_amount'] = roundPrice((StoreOrder::VAT_PERCENTAGE_VALUE / 100) * $taxableItemsData['tax_excluded_amount']);
                $taxableItemsData['total_amount'] = $taxableItemsData['tax_excluded_amount'] + $taxableItemsData['tax_amount'];
            }

            if (isset($taxabilityGroupedItems[0])) {

                $nonTaxableGroupedItems = $taxabilityGroupedItems[0];
                $nonTaxableOrderDetails = $nonTaxableGroupedItems->map(function ($nonTaxableItem) use ($warehouseCode) {
                    $nonTaxableItem['sub_total'] = (
                        $nonTaxableItem->quantity * ($nonTaxableItem->unit_rate)
                    );
                    return $nonTaxableItem;
                });
                $nonTaxableItemsTotal = roundPrice($nonTaxableGroupedItems->sum('sub_total'));
            }

            $response = [];
            $response['html'] = view($this->storeModule . $this->storeView . 'layout.partials.order.detail-modal', compact(
                'storeOrder',
                'taxableOrderDetails',
                'taxableItemsData',
                'nonTaxableItemsTotal',
                'nonTaxableOrderDetails'))->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }


    }

}


