<?php

namespace App\Modules\Store\Controllers\Web\Admin;

use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehouseStorePreOrderService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Helpers\StoreMiscPaymentHelper;
use App\Modules\Store\Helpers\StoreOrderFilter;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Services\BalanceManagement\BalancewithdrawService;
use App\Modules\Store\Services\Kyc\IndividualKycService;
use App\Modules\Store\Services\Payment\StorePaymentService;
use App\Modules\Store\Services\StoreBalanceReconciliation\StoreBalanceReconciliationService;
use App\Modules\Store\Services\StoreOrderService;
use App\Modules\Store\Services\StoreService;
use App\Modules\Store\Transformers\FirmKycDetailTransformer;
use App\Modules\Store\Transformers\IndividualKycDetailTransformer;
use App\Modules\Store\Transformers\StoreMiscellaneousPaymentTransformer;
use App\Modules\Wallet\Helpers\WalletHelper;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Services\StoreWalletTransactionControlService;
use App\Modules\Wallet\Services\WalletService;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use Exception;
use Illuminate\Http\Request;

class StoreCompleteDetailController extends BaseController
{
    public $title = 'Store Details';
    public $base_route = 'admin.stores';
    public $sub_icon = 'file';
    public $module = 'Store::';
    public $view ='admin.store-complete-details.';

    private $paymentService,$storeService,$balanceReconciliationService;
    private $storeOrderService;
    private $individualKycService, $withdrawService;
    private $warehouseStorePreOrderService;
    private $walletService;
    private $storeWalletTransactionControlService;
    private $storeBalance;
    private $walletTransactionPurposeService;


    public function __construct(StorePaymentService $paymentService,
                                StoreService $storeService,
                                StoreBalanceReconciliationService $balanceReconciliationService,
                                StoreOrderService $storeOrderService,
                                IndividualKycService $individualKycService,
                                BalancewithdrawService $withdrawService,
                                WarehouseStorePreOrderService $warehouseStorePreOrderService,
                                WalletService $walletService,
                                StoreWalletTransactionControlService $storeWalletTransactionControlService,
                                StoreBalance $storeBalance,
                                WalletTransactionPurposeService $walletTransactionPurposeService

                             )
    {
        $this->middleware('permission:Show Store', ['only' => ['getStoreGeneralDetail']]);
        $this->middleware('permission:View Store Order List', ['only' => ['getStoreOrder']]);
        $this->middleware('permission:Show Store Order', ['only' => ['getStoreOrderDetails']]);
        $this->middleware('permission:View Store Miscellaneous Payment List',['only'=>['getStoreMiscellaneousPayment']]);
        $this->middleware('permission:Show Store Miscellaneous Payment', ['only' => ['getStoreMiscellaneousPaymentDetails']]);
        $this->middleware('permission:Show Store Individual Kyc', ['only' => ['getStoreKYC']]);
        $this->middleware('permission:View Store Miscellaneous Payment List',['only'=>['getStoreMiscellaneousPayment']]);
        $this->middleware('permission:Show Store Miscellaneous Payment', ['only' => ['getStoreMiscellaneousPaymentDetails']]);
        $this->middleware('permission:View Store Balance Withdraw List',['only'=>['getStoreWithdrawRequest','getwithdrawdetailById']]);

        $this->paymentService = $paymentService;
        $this->storeService = $storeService;
        $this->balanceReconciliationService = $balanceReconciliationService;
        $this->storeOrderService = $storeOrderService;
        $this->individualKycService = $individualKycService;
        $this->withdrawService = $withdrawService;
        $this->storeService = $storeService;
        $this->warehouseStorePreOrderService = $warehouseStorePreOrderService;
        $this->walletService = $walletService;
        $this->storeWalletTransactionControlService = $storeWalletTransactionControlService;
        $this->storeBalance = $storeBalance;
        $this->walletTransactionPurposeService = $walletTransactionPurposeService;

    }


    public function getStoreCompleteDetail($storeCode)
    {
        $store = $this->storeService->findOrFailStoreByCode($storeCode);
        return view(Parent::loadViewData($this->module . $this->view . 'complete-details'), compact('storeCode','store'));
    }

    public function getStoreGeneralDetail($storeCode){
        try {
            $response = [];
            $store = $this->storeService->findOrFailStoreByCodeWith($storeCode, ['location.municipality.district.province']);
            $warehouse = StoreWarehouseHelper::getFirstConnectedWarehouse($storeCode);
            $response['html'] = view($this->module . $this->view . 'layout.partials.general-detail.show',compact('store','warehouse'))->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getStoreOrder($storeCode, Request $request)
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

            $storeOrders = StoreOrderFilter::filterPaginatedStoreOrders($filterParameters, 10, $with);

            $response = [];
            $response['html'] = view($this->module . $this->view . 'layout.partials.order.index', compact('storeOrders'
                , 'storeOrderDeliveryStatuses', 'paymentStatuses', 'filterParameters', 'priceConditions', 'storeCode'))->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }


    }

    public function showStoreOrderDetail($storeOrderCode){
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
            $response['html'] = view($this->module . $this->view . 'layout.partials.order.detail-modal', compact(
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

    public function getStoreMiscellaneousPayment($storeCode,Request  $request){
        try {
            $paymentFor = 'load_balance';
            $filterParameters = [
                'payment_code' => $request->payment_code,
                'payment_type' => $request->payment_type,
                'amount_condition' => $request->get('amount_condition'),
                'amount' => $request->amount,
                'payment_date_from' => $request->payment_date_from,
                'payment_date_to' => $request->payment_date_to,
                'payment_status' => $request->payment_status,
            ];
            $amountConditions = [
                'Greater Than >' => '>',
                'Less Than <' => '<',
                'Greater Than & Equal To >=' => '>=',
                'Less Than & Equal To <=' => '<=',
                'Equal To =' => '=',
            ];
            $paymentsTypes = StoreMiscellaneousPayment::PAYMENT_TYPE;
            $paymentStatus = StoreMiscellaneousPayment::VERIFICATION_STATUSES;
            $with = ['submittedBy', 'respondedBy', 'paymentDocuments', 'paymentMetaData'];

            $store = $this->storeService->findOrFailStoreByCode($storeCode);
            $storePayments = StoreMiscPaymentHelper::filterAllMiscPaymentsByStoreCodeAndPaymentType($filterParameters, $store->store_code, $paymentFor, 10, $with);

            $response['html'] = view($this->module . $this->view . 'layout.partials.miscellaneous-payment.index', compact('storePayments', 'store', 'filterParameters', 'paymentFor', 'paymentsTypes', 'amountConditions', 'paymentStatus', 'storeCode'))->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function showStoreMiscellaneousPaymentDetail($miscPaymentCode)
    {
        try{
            $storePayment = $this->paymentService->findOrFailStoreMiscellaneousPaymentByCodeWithEager($miscPaymentCode);
            $balanceReconciliation = $this->balanceReconciliationService->getBalanceReconciliationForVerificationForLoadbalance($storePayment);

            $currentBalance = StoreTransactionHelper::getStoreCurrentBalance($storePayment->store_code);
            $storePayment = (new StoreMiscellaneousPaymentTransformer($storePayment))->transform();

            $balanceReconciliationUsage = $this->balanceReconciliationService->getBalanceReconciliationUsage($miscPaymentCode);
            $response['html'] = view($this->module . $this->view . 'layout.partials.miscellaneous-payment.detail-modal', compact(
                'storePayment',
                'balanceReconciliation',
                'currentBalance',
                'balanceReconciliationUsage'
            ))->render();

            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getStoreKYC($storeCode)
    {
        try {
            $store = $this->storeService->findOrFailStoreByCodeWith($storeCode, ['individualKyc', 'firmKyc']);

            $sanchalak = $store->individualKyc->where('kyc_for', 'sanchalak')->first();
            $firm = $store->firmKyc;
            if ($firm) {
                $firmKyc = (new FirmKycDetailTransformer($firm))->transform();
                $firmKyc['updated_at'] = $firm->updated_at;
                $firmKyc['verification_status'] = $firm->verification_status;
            } else {
                $firmKyc = '';
            }
            if ($sanchalak) {
                $individualKyc = (new IndividualKycDetailTransformer($sanchalak))->transform();
                $individualKyc['updated_at'] = $sanchalak->updated_at;
                $individualKyc['verification_status'] = $sanchalak->verification_status;
            } else {
                $individualKyc = '';
            }
            $response['html'] = view($this->module . $this->view . 'layout.partials.kyc.index', compact('store', 'individualKyc', 'firmKyc'))->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }   }

    public function getStoreBalance($storeCode){
        try {
            $response = [];
            $response['html'] = view($this->module . $this->view . 'layout.partials.balance.index')->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getStoreWithdrawRequest($storeCode){
        try {
            $storeWithdrawRequests = $this->withdrawService->getWithdrawRequestMadeByStore($storeCode, 10);
            $response['html'] = view($this->module . $this->view . 'layout.partials.balance.withdraw', compact('storeWithdrawRequests'))->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function showStoreWithdrawDetail($withdraw_request_code)
    {
        try{
            $withdrawrequestdetail = $this->withdrawService->getAllwithdrawRequest($withdraw_request_code);
            $response['html'] = view($this->module.$this->view.'layout.partials.balance.withdraw-view',compact('withdrawrequestdetail'))->render();
            return response()->json($response);
        }catch(\Exception $exception){
            return $exception->getMessage();
        }
    }
    public function getStorePreorder($storeCode,Request $request)
    {
        try{
            $response = [];
            $filterParameters=[
                'pre_order_name' => $request->pre_order_name,
                'statuses' =>$request->status,
                'payment_status' =>$request->payment_status,
                'start_time' => $request->get('start_time'),
                'end_time' => $request->get('end_time'),
            ];

            $preOrderStatuses =['pending','finalized','dispatched','cancelled'];
            $preOrdersListing = StorePreOrderHelper::filterStorePreOrder(
                $storeCode,$filterParameters,10);
            $response['html'] = view($this->module . $this->view . 'layout.partials.pre-order.index', compact('preOrdersListing','preOrderStatuses','filterParameters','storeCode'))->render();
            return response()->json($response);
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function showStorePreorderDetail($storePreOrderCode){

        try {
            $warehouseCode = StorePreOrderHelper::getWarehouseCodeByStorePreOrderCode($storePreOrderCode);

            $storePreOrderDetails =$this->warehouseStorePreOrderService->getStorePreOrderDetailForWarehouseMamata($storePreOrderCode,$warehouseCode);

            $storePreOrder = $storePreOrderDetails['store_pre_order'];
            $storePreOrderStatusLogs = $storePreOrderDetails['store_pre_order']['storePreOrderStatusLogs'];
            $taxableOrderDetails = collect($storePreOrderDetails['taxable_order_details']);
            $taxableOrderProducts = $storePreOrderDetails['taxable_order_products'];
            $nonTaxableOrderDetails = collect($storePreOrderDetails['non_taxable_order_details']);
            $nonTaxableOrderProducts = $storePreOrderDetails['non_taxable_order_products'];

            $response['html'] = view($this->module . $this->view . 'layout.partials.pre-order.detail-modal',  compact('storePreOrder','taxableOrderDetails','storePreOrderStatusLogs',
                'taxableOrderProducts','nonTaxableOrderDetails','nonTaxableOrderProducts'))->render();
            return response()->json($response);
        } catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function getStoreEnquiryMessage($userCode)
    {
        try{
            $enquiryMessages = $this->enquiryMessageService->getAllMessageOfStore($userCode);
            $response['html']= view($this->module . $this->view . 'layout.partials.enquiry-message.index',compact('enquiryMessages'))->render();
            return response()->json($response);
        }catch(\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function getStoreBalanceTransaction(Request $request,$storeCode)
    {
        try{
            $store = $this->storeService->findOrFailStoreByCode($storeCode);
            $walletDetail = $store->wallet;
            $walletCode = $walletDetail->wallet_code;
                $filterParameters = [
                    'transaction_type' => $request->get('transaction_type'),
                    'transaction_date_from' => $request->get('transaction_date_from'),
                    'transaction_date_to' => $request->get('transaction_date_to'),
                    'records_per_page' => 20,
                ];

                $wallet = $this->walletService->findOrFailByWalletCode($walletCode);

                if($wallet->wallet_type != 'store'){
                    throw new Exception('The detail you are searching is not of Wallet Type Store)');
                }

                $activeBalance = $this->storeBalance->getStoreActiveBalance($wallet->walletable);
                $frozenBalanceDetails = $this->storeBalance->getStoreFreezeBalanceDetails($wallet->walletable);
                $wallet->holder_name = WalletHelper::getWalletHolderName($wallet);
                $userTypeCode = $wallet->walletable->storeUserTypeCode();
                $transactionPurposes = $this->walletTransactionPurposeService->getWalletTransactionPurposeByUserTypeCode($userTypeCode);

                $allTransactionByWalletCode = WalletTransactionHelper::getWalletTransactionDetailsWithParameters($walletCode,$filterParameters);

            $response['html'] = view($this->module . $this->view .
                'layout.partials.balance.balance-transaction',
                compact(
                    'allTransactionByWalletCode','wallet',
                    'transactionPurposes','filterParameters',
                    'activeBalance','frozenBalanceDetails','store')
            )->render();
            return response()->json($response);

        }catch (Exception $e){
            return $e->getMessage();
        }
    }

}
