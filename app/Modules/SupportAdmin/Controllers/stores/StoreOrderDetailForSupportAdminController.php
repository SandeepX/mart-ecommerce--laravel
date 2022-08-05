<?php


namespace App\Modules\SupportAdmin\Controllers\stores;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Helpers\StoreOrderFilter;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Services\StoreOrderService;
use Exception;
use Illuminate\Http\Request;

class StoreOrderDetailForSupportAdminController extends BaseController
{

    public $title = 'Store Detail For Admin Support';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    public $storeModule = 'Store::';
    public $storeView = 'admin.store-complete-details.';

    private $view = 'stores.';

    private $storeOrderService;

    public function __construct(StoreOrderService $storeOrderService)
    {
        $this->storeOrderService = $storeOrderService;
    }

    public function getStoreOrderForSupportAdmin($storeCode, Request $request)
    {
        try {
            $orderCode = $request->get('store_order_code');
            $deliveryStatus = $request->get('delivery_status');
            $paymentStatus = $request->get('payment_status');
            $orderDateFrom = $request->get('order_date_from');
            $orderDateTo = $request->get('order_date_to');
            $priceCondition = $request->get('price_condition');
            $totalPrice = $request->get('total_price');
            $recordsPerPage = $request->get('records_per_page');
            $globalSearchKeyword = $request->get('search');
            $payablePriceFrom = $request->get('payable_price_from');
            $payablePriceTo = $request->get('payable_price_to');

            $filterParameters = [
                'store_code' => $storeCode,
                'store_order_code' => $orderCode,
                'delivery_status' => array_filter(convertToArray($deliveryStatus)),
                'payment_status' => array_filter(convertToArray($paymentStatus)),
                'order_date_from' => $orderDateFrom,
                'order_date_to' => $orderDateTo,
                'price_condition' => $priceCondition,
                'total_price' => $totalPrice,
                'records_per_page' => $recordsPerPage,
                'global_search_keyword' => $globalSearchKeyword,
                'payable_price_from' => $payablePriceFrom,
                'payable_price_to' => $payablePriceTo,
            ];
            $with = [
                'offlinePayments'
            ];
            $storeOrderDeliveryStatuses = StoreOrder::DELIVERY_STATUSES;
            $paymentStatuses = ['unpaid', 'pending', 'verified', 'rejected'];
            $priceConditions = [
                'Greater Than >' => '>',
                'Less Than <' => '<',
                'Greater Than & Equal To >=' => '>=',
                'Less Than & Equal To <=' => '<=',
                'Equal To =' => '=',
            ];
            //dd($filterParameters);
            $storeOrders = StoreOrderFilter::filterPaginatedStoreOrders($filterParameters, 10, $with);

            $response = [];
            $response['html'] = view($this->module . $this->view . 'store-order.store-order-table',
                compact('storeOrders',
                    'storeOrderDeliveryStatuses',
                    'paymentStatuses',
                    'filterParameters',
                    'priceConditions',
                    'storeCode')
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

