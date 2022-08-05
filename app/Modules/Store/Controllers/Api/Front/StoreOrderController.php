<?php

namespace App\Modules\Store\Controllers\Api\Front;

use App\Exceptions\Custom\InactiveProductException;
use App\Exceptions\Custom\PermissionDeniedException;
use App\Exceptions\Custom\ProductNotEligibleToOrderException;
use App\Exceptions\Custom\StoreOrderPlacementException;
use App\Http\Controllers\Controller;
use App\Modules\Cart\Services\CartService;
use App\Modules\Store\Helpers\StoreOrderFilter;
use App\Modules\Store\Requests\StoreOrderRequest;
use App\Modules\Store\Resources\StoreOrder\SingleStoreOrderResource;
use App\Modules\Store\Resources\StoreOrder\StoreOrderListCollection;
use App\Modules\Store\Resources\StoreOrder\StoreOrderListResource;
use App\Modules\Store\Services\Bill\StoreOrderBillService;
use App\Modules\Store\Services\StoreOrderService;
use App\Modules\Store\Transformers\SingleStoreOrderTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreOrderController extends Controller
{
    private $storeOrderService,$storeOrderBillService;

    public $module = 'Store::';
    public $view = 'admin.store-order.';

    public function __construct(StoreOrderService $storeOrderService,StoreOrderBillService $storeOrderBillService)
    {
        $this->storeOrderService = $storeOrderService;
        $this->storeOrderBillService = $storeOrderBillService;
    }

    public function index(Request $request)
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
                'store_code' => getAuthStoreCode(),
                'store_order_code' => $orderCode,
                'delivery_status' => array_filter(convertToArray($deliveryStatus)),
                'payment_status' => array_filter(convertToArray($paymentStatus)),
                'order_date_from' => $orderDateFrom,
                'order_date_to' => $orderDateTo,
                'price_condition' => $priceCondition,
                'total_price' => $totalPrice,
                'records_per_page'=>$recordsPerPage,
                'global_search_keyword'=>$globalSearchKeyword,
                'payable_price_from' => $payablePriceFrom,
                'payable_price_to' => $payablePriceTo,
            ];


           // return $filterParameters;


            $storeOrders = StoreOrderFilter::filterPaginatedStoreOrders($filterParameters, 10);


            //$storeOrders = $this->storeOrderService->getAllStoreOrdersByStore($request->filter_by, auth()->user()->store);
            return new StoreOrderListCollection($storeOrders);
           // return sendSuccessResponse('Data Found!', $storeOrders);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public function show($storeOrderCode)
    {
        try {
            $with=[
                'details.productPackageType:package_code,package_name',
                'details.product.package.packageType:package_name,package_code',
                'details',
            ];
            $storeOrder = $this->storeOrderService->findStoreOrderByCode($storeOrderCode,$with);
             //dd($storeOrder->storeOrderDispatchDetail);
            if ($storeOrder->store_code !== getAuthStoreCode()) {
                throw new PermissionDeniedException('Forbidden : Cannot See Order Details', 403);
            }
           $storeOrder = new SingleStoreOrderResource($storeOrder);
            //$storeOrder = (new SingleStoreOrderTransformer($storeOrder))->transform();
            return sendSuccessResponse('Data Found!', $storeOrder);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());

        }
    }

    public function store(StoreOrderRequest $storeOrderRequest)
    {
        try {
            $validatedStoreOrder = $storeOrderRequest->validated();
            $storeOrder = $this->storeOrderService->newCreateStoreOrderWithNotification($validatedStoreOrder);
            return sendSuccessResponse('Order Placed Successfully', $storeOrder);
            } catch (Exception $exception) {
                if ($exception instanceof StoreOrderPlacementException) {
                    return sendErrorResponse($exception->getMessage(), 403, $exception->getData());
                }
                return sendErrorResponse($exception->getMessage(), $exception->getCode());
            }
    }

    public function generateStoreOrderBill($storeOrderCode){

        try {
            $storeCode = getAuthStoreCode();
            $storeOrder = $this->storeOrderService->findOrFailStoreOrderByStoreCodeWith($storeOrderCode,
                $storeCode,['details','offlinePayments']);
            $bill= $this->storeOrderBillService->generateStoreOrderPdf($storeOrder,
                $this->module . $this->view . 'store_order_pdf','api');
            return $bill;
        } catch (Exception $exception) {

            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
