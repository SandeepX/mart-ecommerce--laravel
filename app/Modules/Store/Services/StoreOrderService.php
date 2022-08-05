<?php

namespace App\Modules\Store\Services;

use App\Exceptions\Custom\InactiveProductException;
use App\Exceptions\Custom\ProductNotEligibleToOrderException;
use App\Exceptions\Custom\StoreOrderPlacementException;
use App\Modules\AlpasalWarehouse\Models\PreOrder\PreOrderPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductPackagingUnitDisableList;
use App\Modules\AlpasalWarehouse\Repositories\Setting\MinOrderSettingRepository;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Repositories\ProductRepository;
//use App\Modules\Product\Services\ProductPriceService;
use App\Modules\Product\Services\ProductService;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Events\StoreWalletTransactionEvent;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Helpers\StoreOrderHelper;
use App\Modules\Store\Models\StoreOrderDetails;
use App\Modules\Store\Repositories\StoreOrderRepository;
use App\Modules\Store\Repositories\StoreRepository;

use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductStockRepository;

use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Interfaces\TransactionConfigurationInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreOrderService implements TransactionConfigurationInterface
{
    private $storeOrderRepository;
    private $productRepository;
    private $balanceMasterRepository;
    private $warehouseProductMasterRepository;
    private $warehouseProductStockRepository;

    //private $productPriceService;
    private $storeRepository;
    private $cartRepository;
    private $storeOrderNotificationService, $productService;
    private $vendorProductPackagingHistoryRepository;
    private $minOrderSettingRepository;
    private $transactionNotificationConfiguration;
    private $storeBalance;
    public function __construct(
        StoreOrderRepository $storeOrderRepository,
        ProductRepository $productRepository,
        // ProductPriceService $productPriceService,
        StoreRepository $storeRepository,
        CartRepository $cartRepository,
        StoreOrderNotificationService $storeOrderNotificationService,
        ProductService $productService,
        StoreBalanceManagementRepository $balanceMasterRepository,
        WarehouseProductMasterRepository $warehouseProductMasterRepository,
        WarehouseProductStockRepository $warehouseProductStockRepository,
        VendorProductPackagingHistoryRepository $vendorProductPackagingHistoryRepository,
        MinOrderSettingRepository $minOrderSettingRepository,
        TransactionNotificationConfiguration $transactionNotificationConfiguration,
        StoreBalance $storeBalance
    ) {
        $this->storeOrderRepository = $storeOrderRepository;
        $this->productRepository = $productRepository;
        //$this->productPriceService = $productPriceService;
        $this->storeRepository = $storeRepository;
        $this->cartRepository = $cartRepository;
        $this->storeOrderNotificationService = $storeOrderNotificationService;
        $this->productService = $productService;
        $this->balanceMasterRepository = $balanceMasterRepository;
        $this->warehouseProductMasterRepository = $warehouseProductMasterRepository;
        $this->warehouseProductStockRepository = $warehouseProductStockRepository;
        $this->vendorProductPackagingHistoryRepository= $vendorProductPackagingHistoryRepository;
        $this->minOrderSettingRepository= $minOrderSettingRepository;
        $this->transactionNotificationConfiguration= $transactionNotificationConfiguration;
        $this->storeBalance =$storeBalance;

    }


    public function setSMSSendStatus($status)
    {
         $this->transactionNotificationConfiguration->setSMSSendStatus($status);
    }

    public function setMailSendStatus($status)
    {
        // TODO: Implement setMailSendStatus() method.
    }

    public function setWEBNotificationSendStatus($status)
    {
        // TODO: Implement setWEBNotificationSendStatus() method.
    }

    public function getAllStoreOrders($filterByStore = null, $filterByStatus = null)
    {
        if ($filterByStore == null && $filterByStatus == null) {
            return $this->storeOrderRepository->getAllStoreOrders();
        } elseif ($filterByStore != null && $filterByStatus == null) {
            $store = $this->storeRepository->findOrFailStoreByCode($filterByStore);
            return $this->storeOrderRepository->filterStoreOrdersByStore($store);
        } else {
            $store = $this->storeRepository->findOrFailStoreByCode($filterByStore);
            return $this->storeOrderRepository->filterStoreOrdersByStatus($filterByStatus, $store);
        }
    }

    public function getAllStoreOrdersByStore($filterBy, $store)
    {
        if ($filterBy == 'all' || $filterBy == '') {
            return $this->storeOrderRepository->getAllStoreOrdersByStore($store);
        } elseif (in_array($filterBy, ['pending', 'dispatched', 'processing', 'accepted', 'received'])) {
            return $this->filterStoreOrdersByStatus($filterBy, $store);
        } else {
            throw new Exception('Invalid Store Order Status', 400);
        }
    }

    public function findStoreOrderByCode($storeCode,$with=[],$select='*')
    {
        return $this->storeOrderRepository->findStoreOrderByCode($storeCode,$with,$select);
    }

    //usage : Admin Store Controller : Show Page
    public function getOrderDetailsByAdmin($storeOrderCode){
        return $this->findStoreOrderByCode($storeOrderCode,
            [
                'statusLogs'=>function($query){
                    $query->select('store_order_code','status','remarks','updated_at');
                },
                'details.product.vendor'=>function($query){
                    $query->select('vendor_name','vendor_code');
                },
                'details.product.package.packageType'=>function($query){
                    $query->select('package_name','package_code');
                },
                'details.productVariant'=>function($query){
                    $query->select('product_variant_code','product_variant_name');
                },
                'details.product'=>function($query){
                    $query->select('product_code','product_name','vendor_code');
                },
                'details.productPackageType'=>function($query){
                    $query->select('package_code','package_name');
                },
                'details'=>function($query){
                    $query->select('store_order_code','is_taxable_product','product_code','product_variant_code','package_code','quantity','unit_rate','is_accepted','acceptance_status','initial_order_quantity');
                },
                'store'=>function ($query){
                    $query->select('store_code','store_name','store_contact_phone','store_contact_mobile');
                },
                'warehouse' => function($query){
                    $query->select( 'warehouse_name','warehouse_code');
                }
            ],
            //select columns
            [
                'wh_code','store_code','store_order_code','delivery_status','payment_status','acceptable_amount','total_price'
            ]
        );
    }

    public function getOrderDetailsByWarehouse($storeOrderCode,$with=[]){


    }



    public function findOrFailStoreOrderByCode($orderCode)
    {
        return $this->storeOrderRepository->findOrFailByCode($orderCode);
    }

    public function findOrFailStoreOrderByCodeWith($orderCode,array $with)
    {
        return $this->storeOrderRepository->findOrFailByCode($orderCode,$with);
    }

    public function findOrFailStoreOrderByStoreCodeWith($storeOrderCode,$storeCode,array $with){
        return $this->storeOrderRepository->findOrFailByStoreCode($storeOrderCode,$storeCode,$with);
    }

    public function filterStoreOrdersByStatus($status, $store)
    {
        return $this->storeOrderRepository->filterStoreOrdersByStatus($status, $store);
    }


    public function createStoreOrder($validatedStoreOrder)
    {
        try {
            $cartCodes = $validatedStoreOrder['cart_codes'];
            $authUserCode = getAuthUserCode();
            //$validatedStoreOrder['unit_rate'] = [];
            //$validatedStoreOrder['total_product_price'] = [];
            $validatedStoreOrder['products'] = [];
            $orderedTaxableProductsSubTotal = 0;
            $orderedNonTaxableProductsSubTotal = 0;
            $orderGrossTotal = 0;

            //$inActiveProducts = [];
            $ineligibleProducts = [];

            foreach ($cartCodes as $i => $cartCode) {
                $cart = $this->cartRepository->findOrFailCartByUserCode($cartCode, $authUserCode);
                $product = $this->productService->findOrFailProductByCode($cart->product_code);

                $isProductEligibleData = StoreOrderHelper::isProductEligibleToOrderByStore($cart['warehouse_code'],
                    $cart['quantity'],$cart['product_code'], $cart['product_variant_code']);

                if(!$isProductEligibleData['isEligible']){
                    array_push($ineligibleProducts,$isProductEligibleData);
                    continue;
                }
                /*
                                if (!$product->isActive()) {
                                    array_push($inActiveProducts, [
                                        'product_code' => $product->product_code,
                                        'product_name' => $product->product_name,
                                    ]);

                                    continue;
                                }*/

                // $price = $this->productPriceService->getProductPrice($cart->product_code, $cart->product_variant_code);
                $price = (new ProductPriceHelper)->getProductStorePrice($cart->warehouse_code,$cart->product_code, $cart->product_variant_code);
                //$unit_rate = 0;


                if($product->isTaxable()){
                    $unit_rate = roundPrice($price / ( (1 + (StoreOrder::VAT_PERCENTAGE_VALUE/100) )) );
                    $orderedTaxableProductsSubTotal += roundPrice($unit_rate * $cart->quantity);
                }else{
                    $unit_rate = $price;
                    $orderedNonTaxableProductsSubTotal +=  roundPrice($unit_rate * $cart->quantity);
                }

                //array_push($validatedStoreOrder['total_product_price'], roundPrice($price * $cart->quantity));

                $validatedStoreOrder['cartItems'][$i] = [
                    'warehouse_code' => $cart->warehouse_code,
                    'product_code' => $cart->product_code,
                    'product_variant' => $cart->product_variant_code,
                    'quantity' => $cart->quantity,
                    //'unit_rate' => $price,
                    'unit_rate' => $unit_rate,
                    'is_taxable_product' => $product->is_taxable
                ];
            }

            if (count($ineligibleProducts) > 0) {
                throw new ProductNotEligibleToOrderException('Unable to order', $ineligibleProducts);
            }

            //$validatedStoreOrder['total_product_price'] = array_sum($validatedStoreOrder['total_product_price']);
            $orderGrossTotal = ($orderedTaxableProductsSubTotal + roundPrice( (StoreOrder::VAT_PERCENTAGE_VALUE/100)*$orderedTaxableProductsSubTotal) ) + $orderedNonTaxableProductsSubTotal;
            $validatedStoreOrder['total_product_price'] = $orderGrossTotal;


            DB::beginTransaction();

            $validatedStoreOrder['warehouse_code'] = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthStoreCode());
            $storeOrder = $this->storeOrderRepository->createStoreOrder($validatedStoreOrder);

            //Delete User Carts
            $this->cartRepository->massDeleteCarts($cartCodes, $authUserCode);

            //Send Notification to Admin
            $this->storeOrderNotificationService->storeOrderPlacementNotification($storeOrder);
            DB::commit();
            return $storeOrder;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function newCreateStoreOrderWithNotification($validatedStoreOrder){
        $this->setSMSSendStatus(true);
        return $this->newCreateStoreOrder($validatedStoreOrder);
    }

    public function newCreateStoreOrder($validatedStoreOrder){
        try {
            $cartCodes = $validatedStoreOrder['cart_codes'];
            $authUserCode = getAuthUserCode();
            $validatedStoreOrder['warehouse_code'] = StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthStoreCode());
            $cartWhCodes = (new CartRepository())->getDistinctWarehouseCodesFromCartCodes(
                                        $cartCodes,
                                        $authUserCode
                           )->pluck('warehouse_code')->toArray();

            if(
                !in_array($validatedStoreOrder['warehouse_code'],$cartWhCodes)
                || count($cartWhCodes) > 1
            ){
                throw new Exception('Order Contains Items From Multiple Warehouses');
            }

            $storeOrderLookUpResults = StoreOrderHelper::storeOrderPlacingLookUp($cartCodes, $authUserCode);

            $orderedTaxableProductsSubTotal = 0;
            $orderedNonTaxableProductsSubTotal = 0;
            $notEligibleStock = 0;
            $notEligiblePrice = 0;
            $notEligibleActive = 0;
            $ineligibleCartItems = [];

            $storeOrderLookUpResultsCollection = collect($storeOrderLookUpResults)->groupBy(['warehouse_product_master_code']);
            $warehouseProductsMicroCurrentStocks = $storeOrderLookUpResultsCollection->map(function ($t) {
                return $t[0]->micro_current_stock;
            });
            //dd($warehouseProductsMicroCurrentStocks);

            foreach (collect($storeOrderLookUpResults)->chunk(50) as $storeOrderLookUpResultChunk) {

                foreach ($storeOrderLookUpResultChunk as $i => $storeOrderLookUpResult) {


                    $cartCheckoutProblemStatuses = [
                        'stock_unavailable' => $storeOrderLookUpResult->not_eligible_stock,
                        'no_price_found' =>  $storeOrderLookUpResult->not_eligible_price,
                        'in_active' => $storeOrderLookUpResult->not_eligible_active,
                        'max_order_quantity_limit_exceeded' => $storeOrderLookUpResult->not_eligible_max_order_quantity,
                        'order_quantity_less_then_min_order_quantity_limit' => $storeOrderLookUpResult->not_eligible_min_order_quantity
                    ];

                    $cartCheckoutProblems=  array_filter($cartCheckoutProblemStatuses, function ($problemStatus) {
                        return $problemStatus == 1;
                    });

                    if(count($cartCheckoutProblems) > 0){
                        $notEligibleCart = [
                            'cart_code'=> $storeOrderLookUpResult->cart_code,
                            'image'=>photoToUrl($storeOrderLookUpResult->image, asset((new ProductMaster())->uploadFolder)),
                            'product_name'=>$storeOrderLookUpResult->product_name.'('.$storeOrderLookUpResult->product_variant_name.')',
                            'message' => implodeArray(array_keys($cartCheckoutProblems)),
                        ];
                        $finalCartErrorArray = $notEligibleCart + $cartCheckoutProblemStatuses;
                        array_push($ineligibleCartItems,$finalCartErrorArray);
                    }

                    $productPackagingHistory = $this->vendorProductPackagingHistoryRepository->getLatestProductPackagingHistoryByPackageCode(
                        $storeOrderLookUpResult->package_code,$storeOrderLookUpResult->product_code,$storeOrderLookUpResult->product_variant_code);

                    if (!$productPackagingHistory){
                        throw new Exception('Purchase Order failed: package type does not exist for '.
                            $storeOrderLookUpResult->product_name. $storeOrderLookUpResult->product_variant_name);
                    }


                    //for disabled unit list
                    $disabledUnitList = WarehouseProductPackagingUnitDisableList::where('warehouse_product_master_code'
                        ,$storeOrderLookUpResult->warehouse_product_master_code)
                        ->pluck('unit_name')->toArray();

                    if( $storeOrderLookUpResult->package_code == $productPackagingHistory->micro_unit_code){
                        $orderedPackageName ='micro';
                    }elseif ($storeOrderLookUpResult->package_code == $productPackagingHistory->unit_code){
                        $orderedPackageName ='unit';
                    }
                    elseif ($storeOrderLookUpResult->package_code == $productPackagingHistory->macro_unit_code){
                        $orderedPackageName ='macro';
                    }
                    elseif ($storeOrderLookUpResult->package_code == $productPackagingHistory->super_unit_code){
                        $orderedPackageName ='super';
                    }else{
                        throw new Exception('Invalid order package type of product '.$storeOrderLookUpResult->product_name. $storeOrderLookUpResult->product_variant_name);
                    }

                    if (in_array($orderedPackageName,$disabledUnitList)){
                        throw new Exception('Invalid order package type of product '.$storeOrderLookUpResult->product_name. $storeOrderLookUpResult->product_variant_name);
                    }
                    //end of disabled unit list

                    $productPackagingDetail = StoreOrderHelper::getOrderedProductPackagingDetail($storeOrderLookUpResult);
                    $cost = StoreOrderHelper::calculateOrderCost($productPackagingDetail,$storeOrderLookUpResult);


                    //orderedMicroQuantity
                    $convertedOrderedMicroQuantity = ProductUnitPackagingHelper::convertToMicroPackagingUnitQuantity(
                        $productPackagingDetail, $storeOrderLookUpResult->quantity,$productPackagingDetail->ordered_package_type);

                    $currentStock=$warehouseProductsMicroCurrentStocks[$storeOrderLookUpResult->warehouse_product_master_code];
                   // dd($currentStock);


                   if ( $convertedOrderedMicroQuantity >$currentStock ){
                        throw new Exception('Purchase Order failed: Insufficient stock for '.
                            $storeOrderLookUpResult->product_name. $storeOrderLookUpResult->product_variant_name.' of package type '.$storeOrderLookUpResult->package_name);
                    }

                    $warehouseProductsMicroCurrentStocks[$storeOrderLookUpResult->warehouse_product_master_code]=
                        $warehouseProductsMicroCurrentStocks[$storeOrderLookUpResult->warehouse_product_master_code]-$convertedOrderedMicroQuantity;

                    $validatedStoreOrder['cartItems'][$i] = [
                        'warehouse_code' => $storeOrderLookUpResult->warehouse_code,
                        'product_code' => $storeOrderLookUpResult->product_code,
                        'product_variant' => $storeOrderLookUpResult->product_variant_code,
                        'package_code' => $storeOrderLookUpResult->package_code,
                        'product_packaging_history_code' => $productPackagingHistory->product_packaging_history_code,
                        'quantity' => $storeOrderLookUpResult->quantity,
                        'initial_order_quantity' => $storeOrderLookUpResult->quantity,
                        'micro_order_quantity' => $convertedOrderedMicroQuantity,
                        'micro_unit_rate' => $storeOrderLookUpResult->unit_rate,
                        'unit_rate' => $cost/$storeOrderLookUpResult->quantity,
                        'is_taxable_product' => $storeOrderLookUpResult->is_taxable
                    ];

                    //Taxability based subTotal Summation
                    if ($storeOrderLookUpResult->is_taxable) {
                        $orderedTaxableProductsSubTotal += $cost;
                        //$orderedTaxableProductsSubTotal += roundPrice($storeOrderLookUpResult->unit_rate * $storeOrderLookUpResult->quantity);
                    } else {
                       // $orderedNonTaxableProductsSubTotal += ($storeOrderLookUpResult->unit_rate * $storeOrderLookUpResult->quantity);
                        $orderedNonTaxableProductsSubTotal += $cost;
                    }
                }

            }

           //dd($warehouseProductsMicroCurrentStocks);

            if (count($ineligibleCartItems)) {
                // $message = 'because the checkout contains stock unavaialiabioity,';
                throw new StoreOrderPlacementException('Cannot Place Store Order !',$ineligibleCartItems);
            }

            $orderGrossTotal = ($orderedTaxableProductsSubTotal + ((StoreOrder::VAT_PERCENTAGE_VALUE / 100) * $orderedTaxableProductsSubTotal)) + $orderedNonTaxableProductsSubTotal;
            $validatedStoreOrder['total_product_price'] = roundPrice($orderGrossTotal);

           // dd($validatedStoreOrder);
            DB::beginTransaction();



            $minOrderAmount = $this->minOrderSettingRepository->findActiveMinOrderSettingByWarehouseCode($validatedStoreOrder['warehouse_code']);
            if(isset($minOrderAmount) && $minOrderAmount->count())
            {
                if($validatedStoreOrder['total_product_price'] < $minOrderAmount->min_order_amount)
                {
                    throw new Exception('Can not place order as your total order amount is less than minimum order amount ('.$minOrderAmount->min_order_amount.') set by warehouse');
                }
            }
            /**compared current balance with order price to place order**/

          //  $currentStoreBalance = StoreTransactionHelper::getStoreCurrentBalance(getAuthStoreCode());
            $store = $this->storeRepository->findOrFailStoreByCode(getAuthStoreCode());
            $currentStoreBalance = $this->storeBalance->getStoreActiveBalance($store);

            if($validatedStoreOrder['total_product_price'] > $currentStoreBalance){
                throw new Exception("Your current balance is insufficient to place order. Please load your balance and try again.");
            }

            $storeOrder = $this->storeOrderRepository->createStoreOrder($validatedStoreOrder);
            $storeOrderCode = $storeOrder->store_order_code;

            $this->prepareStoreWalletTransactionDetails($storeOrder);

            foreach($validatedStoreOrder['cartItems'] as  $value){
                $productCode = $value['product_code'];
                $warehouseCode = $value['warehouse_code'];
                $productMicroOrderQuantity = $value['micro_order_quantity'];
                $productVariantCode = $value['product_variant'];
                $warehouseProductMaster = $this->warehouseProductMasterRepository->findProductByWarehouseCode($warehouseCode,$productCode,$productVariantCode);

                $stockDeductionData['warehouse_product_master_code'] = $warehouseProductMaster['warehouse_product_master_code'];

                $stockDeductionData['quantity'] = $productMicroOrderQuantity;
                $stockDeductionData['action'] = 'sales';
                $stockDeductionData['package_qty'] = $value['quantity'];
                $stockDeductionData['package_code'] = $value['package_code'];
                $stockDeductionData['product_packaging_history_code'] = $value['product_packaging_history_code'];
                $stockDeductionData['reference_code'] = $storeOrderCode;

                $warehouseProductStock =  $this->warehouseProductStockRepository->storeWarehouseProductStock($stockDeductionData);

                // deduct stock from current stock
                $currentStock = $warehouseProductMaster->current_stock - (int) $productMicroOrderQuantity;
                $this->warehouseProductMasterRepository->updateWarehouseProductCurrentStock($warehouseProductMaster,$currentStock);
            }

            /********/
            //Delete User Carts
            $this->cartRepository->massDeleteCarts($cartCodes, $authUserCode);
            //Send Notification to Admin
            $this->storeOrderNotificationService->storeOrderPlacementNotification($storeOrder);

            DB::commit();

            return $storeOrder;
        }catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function prepareStoreWalletTransactionDetails(StoreOrder $storeOrder){

        $store =$this->storeRepository->findOrFailStoreByCode($storeOrder->store_code);
        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $store->getStoreOrderWalletTransactionPurpose();
        $walletTransaction['amount'] = roundPrice($storeOrder->total_price);
        $walletTransaction['remarks'] = 'normal order balance deduct';
        $walletTransaction['transaction_purpose_reference_code'] = $storeOrder->store_order_code;
        $walletTransaction['transaction_notification_details']=[
            'sms' => [
                'contact_no' =>$store->store_contact_mobile,
                'status' => $this->transactionNotificationConfiguration->getSMSSendStatus(),
                'message' => 'Your Current Account has been debited Rs. '.$storeOrder->total_price. ' for Store Order -@ https://allpasal.com/'
            ]
        ];

        //event for wallet transaction and sms sending
        event(new StoreWalletTransactionEvent($walletTransaction));

    }

    public function updateStoreOrderDeliveryStatus($validatedStoreOrderStatus, $storeOrderCode)
    {
        $storeOrder = $this->storeOrderRepository->findStoreOrderByCode($storeOrderCode);

        $deliveryStatus = $validatedStoreOrderStatus['delivery_status'];
        $remarks = $validatedStoreOrderStatus['remarks'];

        if ($storeOrder->delivery_status == $deliveryStatus && $storeOrder->delivery_status != 'under-verification') {
            throw new Exception('Cannot Update the Same Status ( '.$deliveryStatus.') More than Once', 403);
        }

        if ($deliveryStatus == 'under-verification') {

            if($storeOrder->delivery_status != 'pending' &&  $storeOrder->delivery_status != 'under-verification' ){
                throw new Exception('Order status must be pending or under-verification first to assign the selected status : under-verification');
            }

        }

        if ($deliveryStatus == 'finalize_order') {
            if ($storeOrder->delivery_status != 'under-verification') {
                throw new Exception('Order status must be in under-verification first to finalize the order');
            }
        }

        if ($deliveryStatus == 'processing') {
            $allowedCondition = $storeOrder->delivery_status == 'accepted' || $storeOrder->delivery_status == 'partially-accepted';
            $verifiedPayment = $storeOrder->offlinePayments()->where('payment_status','verified')->latest('id')->first();
            if (!$allowedCondition) {
                throw new Exception('Order status must be accepted or partially accepted first to assign the selected status : processing');
            }
            if(!$verifiedPayment){
                throw new Exception('Order must have its payment verified');
            }
        }

        if ($deliveryStatus == 'dispatched') {
            if ($storeOrder->delivery_status != 'processing') {
                throw new Exception('Order status must be in processing first to assign a selected status');
            }
        }

        if($deliveryStatus == 'under-verification'){

            if (isset($validatedStoreOrderStatus['order_items'])) {
                $storeOrderDetailCodes =  $storeOrder->details()->pluck('store_order_detail_code')->toArray();
                $validRequestedOrderDetailCodes = [];
                $requestedOrderItems = array_filter($validatedStoreOrderStatus['order_items']);
                $requestedDispatchableQuantities = array_filter($validatedStoreOrderStatus['dispatchable_quantity']);
                $requestedOrderStatus = array_filter($validatedStoreOrderStatus['acceptance_status']);

                foreach ($requestedOrderItems as $requestedOrderItem) {
                    if (in_array($requestedOrderItem, $storeOrderDetailCodes)) {
                        array_push($validRequestedOrderDetailCodes, $requestedOrderItem);
                    } else {
                        throw new Exception('You are trying to corrupt the data !');
                    }
                }
                //update the main delivery status to under-verification
                $deliveryStatus = "under-verification";

                foreach($requestedOrderItems as $key =>$value){
                    $orderDetail = $storeOrder->details()->where('store_order_detail_code', $value)->first();
                    $updatableFields = [
                        'quantity'=>$requestedDispatchableQuantities[$key],
                        'initial_order_quantity'=> $orderDetail->quantity,
                        'acceptance_status' => $requestedOrderStatus[$key]
                    ];
                    if($storeOrder->delivery_status == 'under-verification'){
                        $updatableFields = [
                            'quantity'=>$requestedDispatchableQuantities[$key],
                            'acceptance_status' => $requestedOrderStatus[$key]
                        ];
                    }
                    $orderDetail->update($updatableFields);
                }

                //updating the acceptable price
                // -- when any order item has been accepted in under-verification state

                $acceptableTotal = 0;

                //resetting the acceptable price
                // every update call should bring new acceptable price
                $storeOrder->update([ 'acceptable_amount' => NULL]);

                $acceptedOrderedItems = $storeOrder->details()->where('acceptance_status','accepted')->get();
                if(count($acceptedOrderedItems) > 0) {
                    foreach ($acceptedOrderedItems as $acceptedOrderedItem) {
                        $subTotal = $acceptedOrderedItem->quantity * $acceptedOrderedItem->unit_rate;
                        if ($acceptedOrderedItem->is_taxable_product) {
                            $value = $subTotal + (StoreOrder::VAT_PERCENTAGE_VALUE / 100) * $subTotal;
                        } else {
                            $value = $subTotal;
                        }
                        $acceptableTotal += $value;
                    }
                    $storeOrder->update([
                        'acceptable_amount' => $acceptableTotal
                    ]);
                }


            }

        }

        if ($deliveryStatus == 'finalize_order') {

            if (isset($validatedStoreOrderStatus['order_items'])) {

                $validRequestedOrderDetailCodes = [];

                $requestedOrderItems = array_filter($validatedStoreOrderStatus['order_items']);
                $requestedDispatchableQuantities = array_filter($validatedStoreOrderStatus['dispatchable_quantity']);
                $storeOrderDetailCodes = $storeOrder->details()->whereIn('store_order_detail_code',$requestedOrderItems)->pluck('store_order_detail_code')->toArray();

                $requestedOrderStatus = array_filter($validatedStoreOrderStatus['acceptance_status']);


                foreach ($requestedOrderItems as $requestedOrderItem) {
                    if (in_array($requestedOrderItem, $storeOrderDetailCodes)) {
                        array_push($validRequestedOrderDetailCodes, $requestedOrderItem);
                    } else {
                        throw new Exception('You are trying to corrupt the data !');
                    }
                }

                /*  updating the delivery  status of the store order  */

                if (in_array('pending', $requestedOrderStatus)) {
                    throw new Exception('There is still acceptance_status : pending of some ordered products !');
                }

                if(in_array('accepted', $requestedOrderStatus)){
                    if(in_array('rejected', $requestedOrderStatus)){
                        $deliveryStatus = "partially-accepted";
                    }else{
                        $deliveryStatus = "accepted";
                    }

                }else{
                    $deliveryStatus = "cancelled";
                }


                /*updating the acceptance_status of each ordered item detail */

                foreach($requestedOrderItems as $key =>$value){
                    $orderDetail = $storeOrder->details()->where('store_order_detail_code', $value)->first();
                    $updatableFields = [
                        'quantity'=>$requestedDispatchableQuantities[$key],
                        'acceptance_status' => $requestedOrderStatus[$key]
                    ];
                    $orderDetail->update($updatableFields);
                }


                // calculating the acceptable amount of accepted ordered items when the finalized delivery status is accepted or partially_accepted

                if($deliveryStatus != 'cancelled'){
                    $acceptedOrderedItems = $storeOrder->details()->where('acceptance_status','accepted')->get();

                    $acceptableTotal = 0;

                    foreach($acceptedOrderedItems as $acceptedOrderedItem){
                        $subTotal = $acceptedOrderedItem->quantity * $acceptedOrderedItem->unit_rate;
                        if($acceptedOrderedItem->is_taxable_product){
                            $value = $subTotal + (StoreOrder::VAT_PERCENTAGE_VALUE/100) * $subTotal;
                        }else{
                            $value = $subTotal;
                        }
                        $acceptableTotal += $value;
                    }

                    $storeOrder->update([
                        'acceptable_amount' => $acceptableTotal
                    ]);
                }



            }

        }

        return $this->storeOrderRepository->updateStatus($deliveryStatus,$remarks,$storeOrder);
    }

    public function checkProductAndVariant($validatedStoreOrder)
    {

        foreach ($validatedStoreOrder['product_code'] as $key => $productCode) {
            $product = $this->productRepository->findOrFailVerifiedProductByCode($productCode);
            $variantCodes = $product->productVariants()->pluck('product_variant_code')->toArray();
            if (!empty($variantCodes) && !in_array($validatedStoreOrder['product_variant_code'][$key], $variantCodes)) {
                throw new Exception('Variant.' . $key . ' must Be of Selected Product', 422);
            }

            if (empty($variantCodes)) {
                if ($validatedStoreOrder['product_variant_code'][$key] != null)
                    throw new Exception('Variant.' . $key . ' must Be of Selected Product', 422);
            }
        }
    }

//    public function changableStatus($storeOrderCode)
//    {
//        $store = $this->storeOrderRepository->findStoreOrderByCode($storeOrderCode);
//        $changalbleOrderStatus = array();
//        if ($store->delivery_status == 'pending') {
//            array_push($changalbleOrderStatus,'under-verification');
//        } elseif ($store->delivery_status == 'accepted' || $store->delivery_status == 'partially-accepted') {
//            array_push($changalbleOrderStatus, 'processing');
//        } elseif ($store->delivery_status == 'processing') {
//            array_push($changalbleOrderStatus, 'dispatched');
//        }
//
//        //if the product is in under-verification stage then there is option to be accept or reject
//        elseif ($store->delivery_status == 'under-verification') {
//            array_push($changalbleOrderStatus, 'finalize_order','under-verification');
//        }
//
//        return $changalbleOrderStatus;
//    }

    public function checkProductInCart($validatedStoreOrder)
    {
        $carts = $this->cartRepository->getAllCarts(auth()->user());

        //One  Way to do
        // foreach($carts as $cart){
        //     if(!in_array($cart->product_code, $validatedStoreOrder['product_code'])
        //         || !in_array($cart->product_variant_code, $validatedStoreOrder['product_variant_code'])
        //         || !in_array($cart->product_variant_code, $validatedStoreOrder['product_variant_code'])
        //     ){
        //         throw new Exception('Order Items Mismatched from Cart', 400);
        //     }
        // }

        //Better way to do as quantity also matches
        foreach ($carts as $key =>  $cart) {
            if (
                $cart->product_code != $validatedStoreOrder['product_code'][$key]
                || $cart->product_variant_code != $validatedStoreOrder['product_variant_code'][$key]
                || $cart->quantity != $validatedStoreOrder['quantity'][$key]
            ) {
                throw new Exception('Order Items Mismatched from Cart', 400);
            }
        }
    }

}
