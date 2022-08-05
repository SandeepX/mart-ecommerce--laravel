<?php

namespace App\Modules\AlpasalWarehouse\Services\StoreOrder;

use App\Exceptions\Custom\InactiveProductException;
use App\Exceptions\Custom\ProductNotEligibleToOrderException;
use App\Exceptions\Custom\StockUnavailibilityException;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockHelper;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductStockRepository;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Repositories\ProductRepository;

//use App\Modules\Product\Services\ProductPriceService;
use App\Modules\Product\Services\ProductService;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Events\StoreWalletTransactionEvent;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Helpers\StoreOrderHelper;
use App\Modules\AlpasalWarehouse\Repositories\StoreOrder\WHStoreOrderRepository;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Interfaces\TransactionConfigurationInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class WHStoreOrderService  implements TransactionConfigurationInterface
{
    private $storeOrderRepository;
    private $productRepository;
    //private $productPriceService;
    private $storeRepository;
    private $cartRepository;
    private $whProductMasterRepository;
    private $whProductStockRepository;
    private $storeBalanceMgmtRepo;
    private $vendorProductPackagingHistoryRepository;
    private $transactionNotificationConfiguration;


    public function __construct(
        WHStoreOrderRepository $whStoreOrderRepository,
        ProductRepository $productRepository,
        StoreRepository $storeRepository,
        CartRepository $cartRepository,
        ProductService $productService,
        WarehouseProductMasterRepository $whProductMasterRepository,
        WarehouseProductStockRepository $whProductStockRepository,
        StoreBalanceManagementRepository $storeBalanceMgmtRepo,
        VendorProductPackagingHistoryRepository $vendorProductPackagingHistoryRepository,
        TransactionNotificationConfiguration $transactionNotificationConfiguration
    )
    {
        $this->storeOrderRepository = $whStoreOrderRepository;
        $this->productRepository = $productRepository;
        //$this->productPriceService = $productPriceService;
        $this->storeRepository = $storeRepository;
        $this->cartRepository = $cartRepository;
        $this->whProductMasterRepository = $whProductMasterRepository;
        $this->whProductStockRepository = $whProductStockRepository;
        $this->storeBalanceMgmtRepo = $storeBalanceMgmtRepo;
        $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
        $this->transactionNotificationConfiguration = $transactionNotificationConfiguration;
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


    public function findOrFailStoreOrderByCodeForAuthWH($orderCode, array $with = [],$select='*')
    {
        $storeOrder =  $this->storeOrderRepository->findOrFailStoreOrderByCodeForAuthWH($orderCode,$with,$select);
        return $storeOrder;
    }


    //usage : Warehouse Store Controller : Show Page :Warehouse Login
    public function getWarehouseStoreOrderDetailsByAdmin($storeOrderCode)
    {
        $storeOrder =  $this->findOrFailStoreOrderByCodeForAuthWH($storeOrderCode,
            [
                'statusLogs'=>function($query){
                    $query->select('store_order_code','status','remarks','updated_at');
                },
                'storeOrderDispatchDetail'=>function($query){
                    $query->select('driver_name',
                        'vehicle_type',
                        'vehicle_number',
                        'contact_number',
                        'expected_delivery_time',
                        'store_order_code');
                },
                'store'=>function ($query){
                    $query->select('store_code','store_name','store_contact_phone','store_contact_mobile');
                },
                'warehouse' => function($query){
                    $query->select( 'warehouse_name','warehouse_code');
                },
                'latestRemarks'=> function($query){
                   $query->select('store_order_code','remark','created_at');
                }
            ],
            ['wh_code',
                'store_code',
                'store_order_code',
                'delivery_status',
                'payment_status',
                'acceptable_amount',
                'total_price','created_at'
            ]
        );
        $storeOrderDetails = $this->getStoreOrderDetails($storeOrderCode);

        $storeOrderDetails = collect($storeOrderDetails)->map(function ($storeOrderDetail,$key){

            $productPackagingUnitsArr =[
                $storeOrderDetail->super_unit_code ,
                $storeOrderDetail->macro_unit_code ,
                $storeOrderDetail->unit_code ,
                $storeOrderDetail->micro_unit_code
            ];

            $storeOrderDetail->package_order = ProductUnitPackageDetail::determinePackagingBreakingLevel($productPackagingUnitsArr,$storeOrderDetail->package_code);
            return $storeOrderDetail;
        });
        $storeOrderStatus = $this->changableStatus($storeOrderCode);

        $taxabilityGroupedItems = $storeOrderDetails->groupBy('is_taxable_product');
        $taxableOrderDetails = collect();
        $taxableItemsData= [];

        $nonTaxableOrderDetails =collect();
        $nonTaxableItemsTotal = 0;

        if(isset($taxabilityGroupedItems[1])){
            $taxableGroupedItems = $this->getTaxableGroupedItems($taxabilityGroupedItems[1]);
            $taxableOrderDetails = $taxableGroupedItems['taxableOrderDetails'];
            $taxableItemsData = $taxableGroupedItems['taxableItemsData'];

        }

        if(isset($taxabilityGroupedItems[0])) {

            $nonTaxableGroupedItems = $this->getNonTaxableGroupedItems($taxabilityGroupedItems[0]);
            $nonTaxableOrderDetails = $nonTaxableGroupedItems['nonTaxableOrderDetails'];
            $nonTaxableItemsTotal = $nonTaxableGroupedItems['nonTaxableItemsTotal'];
        }

        return [
            'store_order' => $storeOrder,
            'store_order_statuses' => $storeOrderStatus,
            'taxable_order_details' => $taxableOrderDetails,
            'taxable_items_data' => $taxableItemsData,
            'non_taxable_order_details' => $nonTaxableOrderDetails,
            'non_taxable_items_total' => $nonTaxableItemsTotal
        ];


    }

    public function getStoreOrderDetails($orderCode){

        $storeOrderDetails = DB::select("
                            SELECT t1.store_order_code,
                                   t1.store_order_detail_code,
                                   t1.quantity,
                                   t1.initial_order_quantity,
                                   t1.unit_rate,
                                   t1.is_taxable_product,
                                   t1.is_accepted,
                                   t1.acceptance_status,
                                   t2.warehouse_product_master_code,
                                   t2.current_stock,
                                   t4.vendor_name,
                                   t5.product_name,
                                   t6.product_variant_name,
                                   t7.package_name,
                                   t7.package_code,
                                   old_package_types.package_name as old_package_name,
                                   product_packaging_history.micro_unit_code,
                                   product_packaging_history.unit_code,
                                   product_packaging_history.macro_unit_code,
                                   product_packaging_history.super_unit_code
                            from store_order_details t1
                            LEFT JOIN warehouse_product_master t2
                            on (
                                t2.product_code = t1.product_code
                                and (
                                    t2.product_variant_code = t1.product_variant_code
                                        or t1.product_variant_code is null and t2.product_variant_code is null)
                                and t2.warehouse_code = t1.warehouse_code
                            )
                            LEFT JOIN vendors_detail t4
                            on (
                                 t4.vendor_code = t2.vendor_code
                            )
                             LEFT JOIN products_master t5
                            on (
                                 t5.product_code = t1.product_code
                            )
                            LEFT JOIN product_variants t6
                            on (
                                 t6.product_variant_code = t1.product_variant_code
                            )
                            LEFT JOIN package_types t7
                            on (
                                 t7.package_code = t1.package_code
                            )
                            LEFT JOIN product_package_details
                            on (
                                 product_package_details.product_code = t5.product_code
                            )
                            LEFT JOIN package_types old_package_types
                            on (
                                 old_package_types.package_code = product_package_details.package_code
                            )
                            LEFT JOIN product_packaging_history
                            on (
                                 product_packaging_history.product_packaging_history_code = t1.product_packaging_history_code
                            )

                        WHERE t1.store_order_code = '".$orderCode."'
                           Group By t1.is_taxable_product,t1.store_order_detail_code"
        );

        return $storeOrderDetails;
    }

    public function getTaxableGroupedItems($taxableGroupedItems){

        $taxableOrderDetails = $taxableGroupedItems->map(function ($taxableItem){
            //dd($taxableItem->quantity);
            $taxableItem->sub_total = $taxableItem->quantity * ($taxableItem->unit_rate);
            return $taxableItem;
        });

        $taxableItemsData['tax_excluded_amount'] = roundPrice($taxableGroupedItems->sum('sub_total'));
        $taxableItemsData['tax_amount'] = roundPrice((StoreOrder::VAT_PERCENTAGE_VALUE /100)*  $taxableItemsData['tax_excluded_amount']);
        $taxableItemsData['total_amount'] = $taxableItemsData['tax_excluded_amount'] + $taxableItemsData['tax_amount'] ;

        $data = ['taxableOrderDetails'=>$taxableOrderDetails,'taxableItemsData'=>$taxableItemsData];

        return $data;
    }

    public function  getNonTaxableGroupedItems($nonTaxableGroupedItems){
        $nonTaxableOrderDetails = $nonTaxableGroupedItems->map(function ($nonTaxableItem) {
            $nonTaxableItem->sub_total = $nonTaxableItem->quantity * ($nonTaxableItem->unit_rate);
            return $nonTaxableItem;
        });

        $nonTaxableItemsTotal = roundPrice($nonTaxableGroupedItems->sum('sub_total'));
        $data = ['nonTaxableOrderDetails'=>$nonTaxableOrderDetails,'nonTaxableItemsTotal'=>$nonTaxableItemsTotal];

        return $data;
    }

    public function updateStoreOrderDeliveryStatusWithNotifications($validatedStoreOrderStatus,$storeOrderCode){

         $this->setSMSSendStatus(true);
         return  $this->updateStoreOrderDeliveryStatus($validatedStoreOrderStatus,$storeOrderCode);
    }

    public function updateStoreOrderDeliveryStatus($validatedStoreOrderStatus, $storeOrderCode)
    {
        $storeOrder = $this->storeOrderRepository->findOrFailStoreOrderByCodeForAuthWH($storeOrderCode);
        $deliveryStatus = $validatedStoreOrderStatus['delivery_status'];
        $remarks = $validatedStoreOrderStatus['remarks'];

        if ($storeOrder->delivery_status == $deliveryStatus && $storeOrder->delivery_status != 'processing') {
            throw new Exception('Cannot Update the Same Status ( ' . $deliveryStatus . ') More than Once', 403);
        }

        if ($deliveryStatus == 'ready_to_dispatch') {
            if ($storeOrder->delivery_status != 'processing') {
                throw new Exception('Order status must be in processing first to assign a selected status');
            }
        }

//        if ($deliveryStatus == 'dispatched') {
//            if ($storeOrder->delivery_status != 'ready_to_dispatch') {
//                throw new Exception('Order status must be in ready_to_dispatch first to assign a selected status');
//            }
//        }

        if($deliveryStatus == 'cancelled'){
            if ($storeOrder->delivery_status != 'processing' && $storeOrder->delivery_status !='accepted'
                && $storeOrder->delivery_status == 'ready_to_dispatch')
            {
                throw new Exception('Order status must be in processing or accepted or should not be in ready to dispatch state to assign a selected status');
            }
        }

        if ($deliveryStatus == 'processing') {
            if (isset($validatedStoreOrderStatus['order_items'])) {
                $storeOrderDetailCodes = $storeOrder->details()->pluck('store_order_detail_code')->toArray();
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
                //update the main delivery status to processing
                $deliveryStatus = "processing";

                foreach ($requestedOrderItems as $key => $value) {
                    $orderDetail = $storeOrder->details()->where('store_order_detail_code', $value)->first();

                    $updatableFields = [
                        'quantity' => $requestedDispatchableQuantities[$key],
                        'acceptance_status' => $requestedOrderStatus[$key]
                    ];

                    if($updatableFields['quantity'] > $orderDetail['initial_order_quantity']){
                        throw new Exception('product quantity cannot be more than store ordered Quantity');
                    }
//                    if ($storeOrder->delivery_status == 'processing') {
//                        $updatableFields = [
//                            'quantity' => $requestedDispatchableQuantities[$key],
//                            'acceptance_status' => $requestedOrderStatus[$key]
//                        ];
//                    }
                    $orderDetail->update($updatableFields);
                }

                //updating the acceptable price
                // -- when any order item has been accepted in processing state

                $acceptableTotal = 0;

                //resetting the acceptable price
                // every update call should bring new acceptable price
                $storeOrder->update(['acceptable_amount' => NULL]);
                $acceptedOrderedItems = $storeOrder->details()->where('acceptance_status', 'accepted')->get();
                //dd($acceptedOrderedItems);
                if (count($acceptedOrderedItems) > 0) {
                    foreach ($acceptedOrderedItems as $acceptedOrderedItem) {

                        $subTotal = $acceptedOrderedItem->quantity * $acceptedOrderedItem->unit_rate;
                        if ($acceptedOrderedItem->is_taxable_product) {
                            //$value = $subTotal + roundPrice((StoreOrder::VAT_PERCENTAGE_VALUE / 100) * $subTotal);
                            $value = $subTotal + ((StoreOrder::VAT_PERCENTAGE_VALUE / 100) * $subTotal);
                        } else {
                            $value = $subTotal;
                        }
                        $acceptableTotal += $value;
                    }
                    $storeOrder->update([
                        'acceptable_amount' => roundPrice($acceptableTotal)
                    ]);
                }
            }

            if($storeOrder['acceptable_amount']>$storeOrder['total_price']){
                throw new Exception('quantity price cannot not be  more than store ordered total price');
            }
        }

        if($deliveryStatus == 'cancelled'){
            //dd($storeOrder);
            $totalSalesReturnBalance = $storeOrder->total_price;

            if ($totalSalesReturnBalance > 0) {
                $storeOrderDetails = $storeOrder->details()->with(['product','productVariant'])->get();
                // dd($storeOrderDetails);
                foreach ($storeOrderDetails as $key => $detail) {
                    // dd($detail);
                    $storeOrderCode = $detail->store_order_code;
                    $warehouseCode = $detail->warehouse_code;
                    $productCode = $detail->product_code;
                    $productVariantCode = $detail->product_variant_code;

                    if (isset($detail->initial_order_quantity) && !is_null($detail->initial_order_quantity)) {
                        $stockReturnQuantity = $detail->initial_order_quantity;
                        $stockPackageQty = $stockReturnQuantity;
                    } else {
                        $stockReturnQuantity = $detail->quantity;
                        $stockPackageQty = $stockReturnQuantity;
                    }

                    if ($detail->package_code){
                        //$productPackagingDetail = StoreOrderHelper::getOrderedProductPackagingDetail($storeOrderLookUpResult);
                        $productPackagingHistory = $this->vendorProductPackagingHistoryRepository->findProductPackagingHistoryByCode(
                            $detail->product_packaging_history_code);

                    //orderedMicroQuantity
                    if (!$productPackagingHistory) {
                        throw new Exception('Cancellation failed: package type does not exist for ' .
                            $detail->product->product_name . ($detail->productVariant ? $detail->productVariant->product_variant_name : ''));
                     }
                     $convertedOrderedMicroQuantity = ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                            $detail->package_code, $productPackagingHistory, $stockReturnQuantity);
                        $stockReturnQuantity = $convertedOrderedMicroQuantity;
                    }
                    // dd($stockReturnQuantity);
                    //updating the acceptance status of all ordered item to rejected since status is changed to cancelled

                    $detail->update([
                        'acceptance_status'=>'rejected'
                    ]);

                    /*   Ending updating acceptance status*/

                    if ($stockReturnQuantity > 0) {
                        $warehouseProductMaster = $this->whProductMasterRepository
                            ->findProductByWarehouseCode(
                                $warehouseCode,
                                $productCode,
                                $productVariantCode
                            );
                        $warehouseStockAdditionData['warehouse_product_master_code'] = $warehouseProductMaster['warehouse_product_master_code'];
                        $warehouseStockAdditionData['quantity'] = $stockReturnQuantity;
                        $warehouseStockAdditionData['package_qty'] = $stockPackageQty;
                        $warehouseStockAdditionData['package_code'] = $detail->package_code;
                        $warehouseStockAdditionData['product_packaging_history_code'] = $productPackagingHistory->product_packaging_history_code;
                        $warehouseStockAdditionData['reference_code'] = $storeOrder->store_order_code;
                        $warehouseStockAdditionData['action'] = 'sales-return';
                        $this->whProductStockRepository->storeWarehouseProductStock($warehouseStockAdditionData);

                        $currentStock = $warehouseProductMaster->current_stock + (int) $stockReturnQuantity;
                        $this->whProductMasterRepository->updateWarehouseProductCurrentStock($warehouseProductMaster , $currentStock);
                    }
                }
                // balance refund/return of Store Order due to warehouse product return in wallet Transaction
                $this->prepareStoreWalletTransactionDetailsForRefund($storeOrder,$totalSalesReturnBalance);
            }

        }

        if($deliveryStatus =='ready_to_dispatch') {

            if (isset($validatedStoreOrderStatus['order_items'])) {

                $storeOrderDetailCodes = $storeOrder->details()->pluck('store_order_detail_code')->toArray();
                $validRequestedOrderDetailCodes = [];
                $requestedOrderItems = array_filter($validatedStoreOrderStatus['order_items']);

                $requestedDispatchableQuantities = array_filter($validatedStoreOrderStatus['dispatchable_quantity']);
                $requestedOrderStatus = array_filter($validatedStoreOrderStatus['acceptance_status']);

                //dd($requestedDispatchableQuantities);
                if(!in_array('accepted',$requestedOrderStatus)){
                    throw new Exception('For Dispatch , there must be at least one accepted item !');
                }


                if (in_array('pending', $requestedOrderStatus)) {

                    throw new Exception('There is still acceptance_status : pending of some ordered products !');
                }

                foreach ($requestedOrderItems as $requestedOrderItem) {
                    if (in_array($requestedOrderItem, $storeOrderDetailCodes)) {
                        array_push($validRequestedOrderDetailCodes, $requestedOrderItem);
                    } else {
                        throw new Exception('You are trying to corrupt the data !');
                    }
                }
                foreach ($requestedOrderItems as $key => $value) {

                    $orderDetail = $storeOrder->details()->where('store_order_detail_code', $value)->first();
                    //dd($orderDetail);

                    $updatableFields = [
                        'quantity' => $requestedDispatchableQuantities[$key],
                        'acceptance_status' => $requestedOrderStatus[$key]
                    ];


                    if($updatableFields['quantity'] > $orderDetail['initial_order_quantity']){
                        throw new Exception('product quantity cannot be more than store ordered Quantity');
                    }


                    $orderDetail->update($updatableFields);
                }
                //updating the acceptable price
                // -- when any order item has been accepted in processing state

                $acceptableTotal = 0;

                //resetting the acceptable price
                // every update call should bring new acceptable price
                $storeOrder->update(['acceptable_amount' => NULL]);


                $acceptedOrderedItems = $storeOrder->details()->where('acceptance_status', 'accepted')->get();

             //   dd($acceptedOrderedItems);

                if (count($acceptedOrderedItems) > 0) {
                    foreach ($acceptedOrderedItems as $acceptedOrderedItem) {
                        $subTotal = $acceptedOrderedItem->quantity * $acceptedOrderedItem->unit_rate;
                        if ($acceptedOrderedItem->is_taxable_product) {
                            //$value = $subTotal + roundPrice((StoreOrder::VAT_PERCENTAGE_VALUE / 100) * $subTotal);
                            $value = $subTotal + ((StoreOrder::VAT_PERCENTAGE_VALUE / 100) * $subTotal);
                        } else {
                            $value = $subTotal;
                        }
                        $acceptableTotal += $value;
                    }
                    $storeOrder->update([
                        'acceptable_amount' => roundPrice($acceptableTotal)
                    ]);
                }

                //checking if there is differece between store paid balance and our accepted balance

                $totalStorePaidBalance = $storeOrder->total_price;
                $warehouseAcceptedBalance = $storeOrder->acceptable_amount;

                $totalSalesReturnBalance = roundPrice($totalStorePaidBalance - $warehouseAcceptedBalance);

                //dd($totalSalesReturnBalance);

                if ($totalSalesReturnBalance > 0) {
                    $storeOrderDetail = $storeOrder->details()->with(['product','productVariant'])->get();
                    foreach ($storeOrderDetail as $key => $detail) {
                        $acceptanceStatus = $detail->acceptance_status;
                        $storeOrderCode = $detail->store_order_code;
                        $warehouseCode = $detail->warehouse_code;
                        $productCode = $detail->product_code;
                        $productVariantCode = $detail->product_variant_code;
                        $quantity = $detail->quantity;
                        $initialOrderQuantity = $detail->initial_order_quantity;

                        if ($quantity > $initialOrderQuantity) {
                            throw new Exception('Dispatched quantity cannot be more than store ordered Quantity');
                        }
                        if ($acceptanceStatus == 'rejected'){
                            $stockReturnQuantity = $initialOrderQuantity;
                        }

                        if ($acceptanceStatus == 'accepted'){
                            $stockReturnQuantity = $initialOrderQuantity - $quantity;
                        }


                        $productPackagingHistory = $this->vendorProductPackagingHistoryRepository->findProductPackagingHistoryByCode(
                            $detail->product_packaging_history_code);

                        $stockPackageQty = $stockReturnQuantity;


                        //orderedMicroQuantity
                        if (!$productPackagingHistory){
                            throw new Exception('Dispatch failed: package type does not exist for '.
                                $detail->product->product_name. ($detail->productVariant ? $detail->productVariant->product_variant_name  :''));
                        }

                        $convertedOrderedMicroQuantity = ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                            $detail->package_code, $productPackagingHistory,$stockReturnQuantity);
                        $stockReturnQuantity= $convertedOrderedMicroQuantity;

                        //dd($stockReturnQuantity);

                        if ($stockReturnQuantity > 0) {
                            $warehouseProductMaster = $this->whProductMasterRepository
                                ->findProductByWarehouseCode(
                                    $warehouseCode,
                                    $productCode,
                                    $productVariantCode
                                );
                            $warehouseStockAdditionData['warehouse_product_master_code'] = $warehouseProductMaster['warehouse_product_master_code'];
                            $warehouseStockAdditionData['quantity'] = $stockReturnQuantity;
                            $warehouseStockAdditionData['package_qty'] = $stockPackageQty;
                            $warehouseStockAdditionData['package_code'] = $detail->package_code;
                            $warehouseStockAdditionData['product_packaging_history_code'] = $detail->product_packaging_history_code;
                            $warehouseStockAdditionData['reference_code'] = $storeOrder->store_order_code;
                            $warehouseStockAdditionData['action'] = 'sales-return';
                            $this->whProductStockRepository->storeWarehouseProductStock($warehouseStockAdditionData);

                            $currentStock = $warehouseProductMaster->current_stock + (int) $stockReturnQuantity;
                            $this->whProductMasterRepository->updateWarehouseProductCurrentStock($warehouseProductMaster,$currentStock);

                        }
                    }
                    // Price Addition of Store Order due to warehouse product return in Wallet Transaction
                    $this->prepareStoreWalletTransactionDetailsForRefund($storeOrder,$totalSalesReturnBalance);
                }
            }
        }

//        if($deliveryStatus == 'dispatched'){
//            $this->storeOrderRepository->saveStoreOrderDispatchDetails($validatedStoreOrderStatus, $storeOrderCode);
//        }

        $updatedDeliveryStatus = $this->storeOrderRepository->updateStatus($deliveryStatus, $remarks, $storeOrder);

        return $updatedDeliveryStatus;
    }

    private function prepareStoreWalletTransactionDetailsForRefund(StoreOrder $storeOrder,$totalSalesReturnBalance){
        $store =$this->storeRepository->findOrFailStoreByCode($storeOrder->store_code);
        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $store->getStoreOrderRefundWalletTransactionPurpose();
        $walletTransaction['amount'] = roundPrice($totalSalesReturnBalance);
        $walletTransaction['remarks'] = 'Store Order Balance Refund ('.$storeOrder->store_order_code.')';
        $walletTransaction['transaction_purpose_reference_code'] = $storeOrder->store_order_code;
        $walletTransaction['transaction_notification_details']=[
            'sms' => [
                'contact_no' =>$store->store_contact_mobile,
                'status' => $this->transactionNotificationConfiguration->getSMSSendStatus(),
                'message' => 'Your Current Account has been credited Rs. '.$totalSalesReturnBalance. ' for store order refund -@ https://allpasal.com/'
            ]
        ];

        //event for wallet transaction and sms sending
        event(new StoreWalletTransactionEvent($walletTransaction));

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

    public function changableStatus($storeOrderCode)
    {
        $store = $this->storeOrderRepository->findOrFailStoreOrderByCodeForAuthWH($storeOrderCode);

        $changalbleOrderStatus = array();
        if ($store->delivery_status == 'accepted') {
            array_push($changalbleOrderStatus, 'processing','cancelled');
        } elseif ($store->delivery_status == 'processing') {
            array_push($changalbleOrderStatus, 'processing','cancelled','ready_to_dispatch');
        }
//        elseif ($store->delivery_status == 'ready_to_dispatch') {
//            array_push($changalbleOrderStatus, 'dispatched');
//        }

        return $changalbleOrderStatus;
    }

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
        foreach ($carts as $key => $cart) {
            if (
                $cart->product_code != $validatedStoreOrder['product_code'][$key]
                || $cart->product_variant_code != $validatedStoreOrder['product_variant_code'][$key]
                || $cart->quantity != $validatedStoreOrder['quantity'][$key]
            ) {
                throw new Exception('Order Items Mismatched from Cart', 400);
            }
        }
    }

    public function findorFailStoreOrderDispatchDetail($storeOrderCode){
        return $this->storeOrderRepository->findorFailStoreOrderDispatchDetail($storeOrderCode);
    }


}
