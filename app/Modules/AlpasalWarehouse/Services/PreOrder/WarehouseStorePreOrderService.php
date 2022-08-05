<?php


namespace App\Modules\AlpasalWarehouse\Services\PreOrder;

use App\Exceptions\Custom\NotEnoughProductStockException;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderStockRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductStockRepository;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Events\StoreWalletTransactionEvent;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderDetailHelper;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetailView;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderDetailRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderStatusLogRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Interfaces\TransactionConfigurationInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseStorePreOrderService implements TransactionConfigurationInterface
{
    private $storePreOrderRepository;
    private $storePreOrderDetailRepository, $storePreOrderStatusLogRepository;
    private $warehouseProductStockRepository;
    private $storeRepository;
    private $transactionNotificationConfiguration;
    private $warehouseProductMasterRepository;

    private $warehousePreOrderStockRepository,$storeBalanceManagementRepository;

    public function __construct(
        StorePreOrderRepository $storePreOrderRepository,
        StorePreOrderDetailRepository $storePreOrderDetailRepository,
        StorePreOrderStatusLogRepository $storePreOrderStatusLogRepository,
        WarehouseProductStockRepository $warehouseProductStockRepository,
        WarehousePreOrderStockRepository $warehousePreOrderStockRepository,
        StoreBalanceManagementRepository $storeBalanceManagementRepository,
        StoreRepository $storeRepository,
        WarehouseProductMasterRepository $warehouseProductMasterRepository,
        TransactionNotificationConfiguration $transactionNotificationConfiguration
    ){

        $this->storePreOrderRepository = $storePreOrderRepository;
        $this->storePreOrderDetailRepository = $storePreOrderDetailRepository;
        $this->storePreOrderStatusLogRepository = $storePreOrderStatusLogRepository;
        $this->warehouseProductStockRepository = $warehouseProductStockRepository;
        $this->warehousePreOrderStockRepository = $warehousePreOrderStockRepository;
        $this->storeBalanceManagementRepository = $storeBalanceManagementRepository;
        $this->storeRepository = $storeRepository;
        $this->warehouseProductMasterRepository = $warehouseProductMasterRepository;
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

    public function getStorePreOrderDetailForWarehouse($storePreOrderCode)
    {

        try {
            $authWarehouseCode = getAuthWarehouseCode();
            $with = [
                'store:store_code,store_name,store_contact_phone,store_contact_mobile',
                'warehousePreOrderListing.warehouse:warehouse_code,warehouse_name',
                'warehousePreOrderListing:warehouse_preorder_listing_code,warehouse_code',
                'storePreOrderView',
                'storePreOrderStatusLogs.updatedBy:user_code,name',
                'storePreOrderStatusLogs:store_preorder_code,status,remarks,updated_by,created_at,updated_at',
//                 'storePreOrderDetails',
                'storePreOrderDetails.warehousePreOrderProduct:warehouse_preorder_product_code,is_active',
                'storePreOrderDispatchDetail'
//                 'storePreOrderDetails.warehousePreOrderProduct.product',
//                 'storePreOrderDetails.warehousePreOrderProduct.productVariant',
            ];

            $storePreOrder = $this->storePreOrderRepository->findOrFailByWarehouseCode($authWarehouseCode, $storePreOrderCode, $with);
            $storePreOrderDetails = StorePreOrderDetailHelper::getStorePreOrderDetailForWarehouse($storePreOrderCode,$authWarehouseCode);
            $storePreOrderDetails = $storePreOrderDetails->map(function ($storePreOrderDetail) use ($storePreOrder) {

                if ($storePreOrderDetail->is_taxable == 1) {
                    $taxUnitRate = $storePreOrderDetail->unit_rate + ($storePreOrderDetail->unit_rate * (StorePreOrder::VAT_PERCENTAGE_VALUE/100));
                    $taxSubTotal = $storePreOrderDetail->quantity * $taxUnitRate;
                    $taxPercent =StorePreOrder::VAT_PERCENTAGE_VALUE.'%';
                } else {
                    $taxUnitRate = $storePreOrderDetail->unit_rate;
                    $taxSubTotal = $storePreOrderDetail->quantity * ($storePreOrderDetail->unit_rate);
                    $taxPercent = '-';
                }
                $storePreOrderDetail->sub_total = $storePreOrderDetail->quantity * ($storePreOrderDetail->unit_rate);
                $storePreOrderDetail->delivery_status_name = $storePreOrderDetail->delivery_status == 1 ? 'Accepted' : 'Rejected';
                $storePreOrderDetail->tax_unit_rate = $taxUnitRate;
                $storePreOrderDetail->tax_sub_total = $taxSubTotal;
                $storePreOrderDetail->tax_percent = $taxPercent;
                $storePreOrderDetail->store_name = $storePreOrder->store->store_name;
                $storePreOrderDetail->store_preorder_code = $storePreOrder->store_preorder_code;
                $storePreOrderDetail->is_active_in_preorder_list = $storePreOrderDetail->warehousePreOrderProduct->is_active;

                /*$packageOrder =ProductUnitPackageDetail::MICRO_PACKAGE_ORDER_VALUE;
                if ($storePreOrderDetail->package_code == $storePreOrderDetail->super_unit_code){
                    $packageOrder =ProductUnitPackageDetail::SUPER_PACKAGE_ORDER_VALUE;
                }
                elseif ($storePreOrderDetail->package_code == $storePreOrderDetail->macro_unit_code){
                    $packageOrder =ProductUnitPackageDetail::MACRO_PACKAGE_ORDER_VALUE;
                }
                elseif ($storePreOrderDetail->package_code == $storePreOrderDetail->unit_code){
                    $packageOrder =ProductUnitPackageDetail::UNIT_PACKAGE_ORDER_VALUE;
                }*/

                $productPackagingUnitsArr =[
                    $storePreOrderDetail->super_unit_code ,
                    $storePreOrderDetail->macro_unit_code ,
                    $storePreOrderDetail->unit_code ,
                    $storePreOrderDetail->micro_unit_code
                ];

                $storePreOrderDetail->package_order = ProductUnitPackageDetail::determinePackagingBreakingLevel($productPackagingUnitsArr,$storePreOrderDetail->package_code);
                return $storePreOrderDetail;
            });

            $storePreOrderDetails = $storePreOrderDetails->groupBy('is_taxable')
                ->keyBy(function ($value, $key) {
                    if ($key == 0) {
                        return 'non_taxable';
                    } else {
                        return 'taxable';
                    }
                });

            $taxableOrderDetails = collect();
            $nonTaxableOrderDetails = collect();
            $storePreOrderDispatchDetail = collect();

            $storePreOrderDispatchDetail=   $storePreOrder->storePreOrderDispatchDetail;
            if (isset($storePreOrderDetails['taxable'])) {
                $taxableOrderDetails['tax_excluded_amount'] = $storePreOrderDetails['taxable']->sum('sub_total');
                $taxableOrderDetails['tax_amount'] = (13 / 100) * $taxableOrderDetails['tax_excluded_amount'];
                $taxableOrderDetails['total_amount'] = $taxableOrderDetails['tax_excluded_amount'] + $taxableOrderDetails['tax_amount'];
            } else {
                $storePreOrderDetails['taxable'] = collect();
            }

            if (isset($storePreOrderDetails['non_taxable'])) {
                $nonTaxableOrderDetails['total_amount']= $storePreOrderDetails['non_taxable']->sum('sub_total');
            } else {
                $storePreOrderDetails['non_taxable'] = collect();
            }


            return [
                'store_pre_order' => $storePreOrder,
                'taxable_order_details' => $taxableOrderDetails,
                'taxable_order_products' => $storePreOrderDetails['taxable'],
                'non_taxable_order_details' => $nonTaxableOrderDetails,
                'non_taxable_order_products' => $storePreOrderDetails['non_taxable'],
                'storePreOrderDispatchDetail' => $storePreOrderDispatchDetail
            ];



        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public function updateStorePreOrderDetailByWarehouse(
        $validatedData,$storePreOrderCode,$storePreOrderDetailCode)
    {
        try {
            $authWarehouseCode = getAuthWarehouseCode();
            $storePreOrder = $this->storePreOrderRepository->findOrFailByWarehouseCode($authWarehouseCode, $storePreOrderCode);

            if($storePreOrder->has_merged){
                  throw new Exception('Cannot Updated after Merged');
            }

            if(!in_array($storePreOrder->status,['finalized','processing'])){
                throw new Exception('Cannot update detail while the pre-order status is '.$storePreOrder->status.'');
            }

//            if ($storePreOrder->status == "pending") {
//                throw new Exception('Cannot update: Store pre-order while pending');
//            }
//            if ($storePreOrder->status == 'dispatched' || $storePreOrder->status == 'cancelled') {
//                throw new Exception('Cannot update: Store pre-order already ' . ucwords($storePreOrder->status));
//            }
            $withPreOrderDetail=[
                'warehousePreOrderProduct'
            ];
            $storePreOrderDetail = $this->storePreOrderDetailRepository->findOrFailByStorePreOrderCode(
                $storePreOrderCode, $storePreOrderDetailCode,$withPreOrderDetail);

            if ($storePreOrderDetail->warehousePreOrderProduct->is_active != 1){
                throw new Exception('Cannot update as pre-order product is inactive.');
            }

            if ($validatedData['dispatch_quantity'] > $storePreOrderDetail->initial_order_quantity) {
                throw new Exception('Dispatch quantity cannot be greater than order quantity.');
            }
            DB::beginTransaction();
            $validatedData['quantity'] = $validatedData['dispatch_quantity'];
            $storePreOrderDetail = $this->storePreOrderDetailRepository->updatePreOrderDetailDeliveryStatus($storePreOrderDetail, $validatedData);
            DB::commit();

            return $storePreOrderDetail;

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateStorePreOrderstatusByWarehouseWithNotification($validatedData, $storePreOrderCode){
        $this->setSMSSendStatus(true);
        $this->updateStorePreOrderStatusByWarehouse($validatedData, $storePreOrderCode);
    }

    public function updateStorePreOrderStatusByWarehouse($validatedData, $storePreOrderCode)
    {
        try {
            // throw new Exception('This Feature has cancel status working , but still dispatching feature remaining ! please co-operate');
            $authWarehouseCode = getAuthWarehouseCode();
            $storePreOrder = $this->storePreOrderRepository->findOrFailByWarehouseCode($authWarehouseCode, $storePreOrderCode);

            if($storePreOrder->has_merged){
                throw new Exception('Cannot Updated after Order is Merged');
            }

            $storeCode = $storePreOrder->store_code;


            if ($storePreOrder->status == $validatedData['status'] && $storePreOrder->status != 'processing') {
                throw new Exception('Cannot Update the Same Status ( ' . $validatedData['status'] . ') More than Once', 403);
            }

            if ($storePreOrder->status != 'processing' && $validatedData['status'] == 'processing') {
                if ($storePreOrder->status != 'finalized') {
                    throw new Exception('Cannot update: Store pre-order should be finalized first.');
                }
            }

            if ($validatedData['status'] == 'ready_to_dispatch') {
                if ($storePreOrder->status != 'processing') {
                    throw new Exception('Cannot update: Store pre-order should be processed first.');
                }
            }

//            if ($validatedData['status'] == 'dispatched') {
//                if ($storePreOrder->status != 'ready_to_dispatch') {
//                    throw new Exception('Cannot update: Store pre-order should be in ready to dispatch first.');
//                }
//            }

            if($validatedData['status'] == 'cancelled'){
                if ($storePreOrder->status != 'processing' && $storePreOrder->status !='finalized'
                    && $storePreOrder->status == 'ready_to_dispatch')
                {
                    throw new Exception('Store Pre Order status must be in processing or finalized or should not be in ready to dispatch state to assign a selected status');
                }
            }

            DB::beginTransaction();
            $storePreOrderDetails = $this->storePreOrderDetailRepository
                ->getStorePreOrderDetailsFromViewByStorePreOrderCode(
                    $storePreOrderCode
                );

            $refundCalculatingStorePreOrderDetails = StorePreOrderDetailView::whereHas('relatedStorePreOrderDetail.warehousePreOrderProduct',function ($query){
                  $query->where('is_active',1);
            })
                ->whereNull('deleted_at')
                ->where('store_preorder_code',$storePreOrderCode)->get();

            if(count($storePreOrderDetails) < 1){
              throw new Exception('No Store Pre Order Item Found ! : Cannot Update Status');
            }

            $refundableAmount = 0;
            if ($validatedData['status'] == 'cancelled') {
                foreach ($storePreOrderDetails as $storePreOrderDetailViewObj){
                    $storePreOrderDetailViewObj->relatedStorePreOrderDetail->update([
                        'delivery_status'=> 0
                    ]);
                }
                $refundableAmount = $this->getRefundableAmountIfCancelled($refundCalculatingStorePreOrderDetails);
            }

            if($validatedData['status'] != 'processing') {
                if($refundableAmount > 0){
                    $this->savingRefundableAmountToStoreBalance($storePreOrderCode,$storeCode,$refundableAmount);
                }
            }

            if ($validatedData['status'] == 'ready_to_dispatch') {
                $isDeliveryAccepted = StorePreOrderDetailHelper::isAnyStorePreOrderDetailDeliveryAccepted($storePreOrder->store_preorder_code);
                if (!$isDeliveryAccepted) {
                    throw new Exception('Cannot dispatch pre-order: at least one order delivery should be accepted.');
                }

                $dispatchableItems = $this->gettingDispatchableItems($storePreOrderCode);


                $this->updatingTheStockAfterDispatching($dispatchableItems);
                $refundableAmount= $this->getRefundableAmountIfDisptached($refundCalculatingStorePreOrderDetails);

            }

//           if ($validatedData['status'] == 'dispatched') {
//               $this->storePreOrderRepository->createStorePreOrderDispatchDetail($validatedData, $storePreOrderCode);
//           }

            /*  Updating the pre order status*/

            $storePreOrder = $this->storePreOrderRepository->updatePreOrderStatus($storePreOrder, $validatedData);
            $storePreOrderStatusLog = $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder, $validatedData);

            /* Ending the pre order status*/

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    public function getStorePreOrderDetailForWarehouseMamata($storePreOrderCode,$warehouseCode)
    {
        try {
            $with = [
                'store:store_code,store_name,store_contact_phone,store_contact_mobile',
                'warehousePreOrderListing.warehouse:warehouse_code,warehouse_name',
                'warehousePreOrderListing:warehouse_preorder_listing_code,warehouse_code',
                'storePreOrderView',
                'storePreOrderStatusLogs.updatedBy:user_code,name',
                'storePreOrderStatusLogs:store_preorder_code,status,remarks,updated_by,created_at'
                /* 'storePreOrderDetails',
                 'storePreOrderDetails.warehousePreOrderProduct',
                 'storePreOrderDetails.warehousePreOrderProduct.product',
                 'storePreOrderDetails.warehousePreOrderProduct.productVariant',*/
            ];


//            dd($warehouseCode,$storePreOrderCode);

            $storePreOrder = $this->storePreOrderRepository->findOrFailByWarehouseCode($warehouseCode, $storePreOrderCode, $with);

//             dd($storePreOrder);

            $storePreOrderDetails = StorePreOrderDetailHelper::getStorePreOrderDetailForWarehouse($storePreOrderCode, $warehouseCode);

            //dd($storePreOrderDetails);
            $storePreOrderDetails = $storePreOrderDetails->map(function ($storePreOrderDetail) use ($storePreOrder) {

                if ($storePreOrderDetail->is_taxable == 1) {
                    $taxUnitRate = $storePreOrderDetail->unit_rate + ($storePreOrderDetail->unit_rate * (StorePreOrder::VAT_PERCENTAGE_VALUE / 100));
                    $taxSubTotal = $storePreOrderDetail->quantity * $taxUnitRate;
                    $taxPercent = StorePreOrder::VAT_PERCENTAGE_VALUE . '%';
                } else {
                    $taxUnitRate = $storePreOrderDetail->unit_rate;
                    $taxSubTotal = $storePreOrderDetail->quantity * ($storePreOrderDetail->unit_rate);
                    $taxPercent = '-';
                }
                $storePreOrderDetail->sub_total = $storePreOrderDetail->quantity * ($storePreOrderDetail->unit_rate);
                $storePreOrderDetail->delivery_status_name = $storePreOrderDetail->delivery_status == 1 ? 'Accepted' : 'Rejected';
                $storePreOrderDetail->tax_unit_rate = $taxUnitRate;
                $storePreOrderDetail->tax_sub_total = $taxSubTotal;
                $storePreOrderDetail->tax_percent = $taxPercent;
                $storePreOrderDetail->store_name = $storePreOrder->store->store_name;
                $storePreOrderDetail->store_preorder_code = $storePreOrder->store_preorder_code;
                return $storePreOrderDetail;
            });

            $storePreOrderDetails = $storePreOrderDetails->groupBy('is_taxable')
                ->keyBy(function ($value, $key) {
                    if ($key == 0) {
                        return 'non_taxable';
                    } else {
                        return 'taxable';
                    }
                });

            $taxableOrderDetails = collect();
            $nonTaxableOrderDetails = collect();
            if (isset($storePreOrderDetails['taxable'])) {
                $taxableOrderDetails['tax_excluded_amount'] = roundPrice($storePreOrderDetails['taxable']->sum('sub_total'));
                $taxableOrderDetails['tax_amount'] = roundPrice((13 / 100) * $taxableOrderDetails['tax_excluded_amount']);
                $taxableOrderDetails['total_amount'] = $taxableOrderDetails['tax_excluded_amount'] + $taxableOrderDetails['tax_amount'];
            } else {
                $storePreOrderDetails['taxable'] = collect();
            }

            if (isset($storePreOrderDetails['non_taxable'])) {
                $nonTaxableOrderDetails['total_amount'] = roundPrice($storePreOrderDetails['non_taxable']->sum('sub_total'));
            } else {
                $storePreOrderDetails['non_taxable'] = collect();
            }

            return [
                'store_pre_order' => $storePreOrder,
                'taxable_order_details' => $taxableOrderDetails,
                'taxable_order_products' => $storePreOrderDetails['taxable'],
                'non_taxable_order_details' => $nonTaxableOrderDetails,
                'non_taxable_order_products' => $storePreOrderDetails['non_taxable']
            ];

        } catch (Exception $exception) {
            throw $exception;
        }
    }


    /*
        getting the refundable amount after cancellation
        updating the pre order status to cancelled with remarks
        saving status log as cancelled
    */
    public function getRefundableAmountIfCancelled($storePreOrderDetails){
        //in order to work $storePreOrderDetails should be store_pre_order_detail_view object
        $refundableAmount = 0;
        foreach ($storePreOrderDetails as $storePreOrderDetail){
            $boughtUnitRate = $storePreOrderDetail->unit_rate;
            $refundAppliedQty = $storePreOrderDetail->initial_order_quantity;
            if($storePreOrderDetail->is_taxable == 1){
                $amount = $boughtUnitRate + (StorePreOrder::VAT_PERCENTAGE_VALUE/100 * $boughtUnitRate);
                $refundableAmount += $amount * $refundAppliedQty;
            }else{
                $refundableAmount += $boughtUnitRate * $refundAppliedQty;
            }
        }
        return roundPrice($refundableAmount);
    }

    public function getRefundableAmountIfDisptached($storePreOrderDetails){
        $refundableAmount = 0;

        foreach ($storePreOrderDetails as $storePreOrderDetail){

            $refundAppliedQty = 0;

            $initialQty = $storePreOrderDetail->initial_order_quantity;
            $dispatchingQty = $storePreOrderDetail->quantity;

            /* for accepted items : checking if dispatching qty differs from initial order qty*/
            if($storePreOrderDetail->delivery_status == 1){
                if($initialQty > $dispatchingQty ){
                    $refundAppliedQty = $initialQty - $dispatchingQty;
                }
            }else{
                $refundAppliedQty = $initialQty;
            }

            $boughtUnitRate = $storePreOrderDetail->unit_rate;

            if($storePreOrderDetail->is_taxable == 1){
                $taxedUnitRate = $boughtUnitRate + (StorePreOrder::VAT_PERCENTAGE_VALUE/100 * $boughtUnitRate);
                // $amount = $boughtUnitRate + (StorePreOrder::VAT_PERCENTAGE_VALUE/100 * $refundAppliedQty);
                $refundableAmount += $taxedUnitRate * $refundAppliedQty;
            }else{
                $refundableAmount += $boughtUnitRate * $refundAppliedQty;
            }

        }
        return roundPrice($refundableAmount);
    }

    public function actionsWhileDispatching($storePreOrder){

        # $totalRefundableAmount = $dispatchableItems->where('net_refundable_amount','>',0)->sum('net_refundable_amount');

    }


    public function gettingDispatchableItems($storePreOrderCode){

        $acceptedStorePreOrderDetails = StorePreOrderDetailHelper::getAcceptedStorePreOrderDetailsForDispatchAndStockDeduction(
            $storePreOrderCode
        );

        $acceptedStorePreOrderDetails = collect($acceptedStorePreOrderDetails);
        $nonDispatchableItemsCount = $acceptedStorePreOrderDetails->sum('cannot_be_disptached');


        $nonDispatchableItems = [];

        if($nonDispatchableItemsCount>0){
            foreach($acceptedStorePreOrderDetails as $acceptedStorePreOrderDetail){

                if($acceptedStorePreOrderDetail->cannot_be_disptached){
                    array_push($nonDispatchableItems,[
                        'product_name'=>$acceptedStorePreOrderDetail->product_name,
                        'product_variant_name'=>$acceptedStorePreOrderDetail->product_variant_name,
                        'insufficientQty'=>$acceptedStorePreOrderDetail->micro_quantity - $acceptedStorePreOrderDetail->current_stock,
                        'dispatchingQty'=>$acceptedStorePreOrderDetail->quantity,
                        'dispatchingMicroQty'=>$acceptedStorePreOrderDetail->micro_quantity,
                    ]);
                }
            }

            throw new NotEnoughProductStockException('Not enough stock of accepted products',$nonDispatchableItems);
        }

        return $acceptedStorePreOrderDetails;
    }


    public function updatingTheStockAfterDispatching($dispatchableItems){


        $chunkedDispatchableItems = $dispatchableItems->chunk(50);
        foreach ($chunkedDispatchableItems as $dispatchableItems){
            foreach ($dispatchableItems as $dispatchableItem){

                $warehouseProductMaster = $this->warehouseProductMasterRepository->findOrFailProductByCode(
                    $dispatchableItem->wpm,
                    $dispatchableItem->warehouse_code
                );

              $this->warehouseProductStockRepository->storeWarehouseProductStock([
                    'warehouse_product_master_code' => $dispatchableItem->wpm,
                    'quantity' => $dispatchableItem->micro_quantity,
                    'package_qty' => $dispatchableItem->quantity,
                    'package_code' => $dispatchableItem->package_code,
                    'product_packaging_history_code' => $dispatchableItem->product_packaging_history_code,
                    'reference_code' => $dispatchableItem->store_preorder_code,
                    'action' =>'preorder_sales'
                ]);

                //updating currentStock
                $currentStock = $warehouseProductMaster->current_stock  - (int) $dispatchableItem->micro_quantity;
               $this->warehouseProductMasterRepository->updateWarehouseProductCurrentStock($warehouseProductMaster,$currentStock);

            }

        }
    }


    public function savingRefundableAmountToStoreBalance($storePreOrderCode,$storeCode,$refundableAmount)
    {
        $store =$this->storeRepository->findOrFailStoreByCode($storeCode);
        $storePreOrder = $this->storePreOrderRepository->getStorePreOrderByPreOrderCode($storePreOrderCode);
        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $store->getPreOrderRefundWalletTransactionPurpose();
        $walletTransaction['amount'] = roundPrice($refundableAmount);
        $walletTransaction['remarks'] = 'preorder balance refund ('.$storePreOrderCode.')';
        $walletTransaction['transaction_purpose_reference_code'] = $storePreOrderCode;
        $walletTransaction['transaction_notification_details']=[
            'sms' => [
                'contact_no' =>$store->store_contact_mobile,
                'status' => $this->transactionNotificationConfiguration->getSMSSendStatus(),
                'message' => 'Your Current Account has been credited Rs.'.$refundableAmount. ' for Store Pre-order refund -@ https://allpasal.com/'
            ]
        ];

      //  dd($walletTransaction);

        //event for wallet transaction and sms sending
        event(new StoreWalletTransactionEvent($walletTransaction));

    }


    public function changeableStatus($storePreOrderCode)
    {
        $storePreOrder = $this->storePreOrderRepository->getStorePreOrderByPreOrderCode($storePreOrderCode);

        $changeablePreOrderStatus = array();
        if ($storePreOrder->status == 'finalized') {
            array_push($changeablePreOrderStatus, 'processing','cancelled');
        } elseif ($storePreOrder->status == 'processing') {
            array_push($changeablePreOrderStatus, 'processing','cancelled','ready_to_dispatch');
        }
//        elseif ($storePreOrder->status == 'ready_to_dispatch') {
//            array_push($changeablePreOrderStatus, 'dispatched');
//        }
        return $changeablePreOrderStatus;
    }

    public function getStorePreOrderDetailForAdmin($storePreOrderCode)
    {
        try {

            $with = [
                'store:store_code,store_name,store_contact_phone,store_contact_mobile',
                'warehousePreOrderListing.warehouse:warehouse_code,warehouse_name',
                'warehousePreOrderListing:warehouse_preorder_listing_code,warehouse_code',
                'storePreOrderView',
                'storePreOrderStatusLogs.updatedBy:user_code,name',
                'storePreOrderStatusLogs:store_preorder_code,status,remarks,updated_by,created_at,updated_at',
//                 'storePreOrderDetails',
                'storePreOrderDetails.warehousePreOrderProduct:warehouse_preorder_product_code,is_active',
                'storePreOrderDispatchDetail'
//                 'storePreOrderDetails.warehousePreOrderProduct.product',
//                 'storePreOrderDetails.warehousePreOrderProduct.productVariant',
            ];

            $storePreOrder = $this->storePreOrderRepository->getStorePreOrderByPreOrderCode($storePreOrderCode, $with);
            $warehouseCode = $storePreOrder->warehousePreOrderListing->warehouse_code;
           // dd($storePreOrder);
            $storePreOrderDetails = StorePreOrderDetailHelper::getStorePreOrderDetailForWarehouse($storePreOrderCode, $warehouseCode);
            $storePreOrderDetails = $storePreOrderDetails->map(function ($storePreOrderDetail) use ($storePreOrder) {

                if ($storePreOrderDetail->is_taxable == 1) {
                    $taxUnitRate = $storePreOrderDetail->unit_rate + ($storePreOrderDetail->unit_rate * (StorePreOrder::VAT_PERCENTAGE_VALUE / 100));
                    $taxSubTotal = $storePreOrderDetail->quantity * $taxUnitRate;
                    $taxPercent = StorePreOrder::VAT_PERCENTAGE_VALUE . '%';
                } else {
                    $taxUnitRate = $storePreOrderDetail->unit_rate;
                    $taxSubTotal = $storePreOrderDetail->quantity * ($storePreOrderDetail->unit_rate);
                    $taxPercent = '-';
                }
                $storePreOrderDetail->sub_total = $storePreOrderDetail->quantity * ($storePreOrderDetail->unit_rate);
                $storePreOrderDetail->delivery_status_name = $storePreOrderDetail->delivery_status == 1 ? 'Accepted' : 'Rejected';
                $storePreOrderDetail->tax_unit_rate = $taxUnitRate;
                $storePreOrderDetail->tax_sub_total = $taxSubTotal;
                $storePreOrderDetail->tax_percent = $taxPercent;
                $storePreOrderDetail->store_name = $storePreOrder->store->store_name;
                $storePreOrderDetail->store_preorder_code = $storePreOrder->store_preorder_code;
                $storePreOrderDetail->is_active_in_preorder_list = $storePreOrderDetail->warehousePreOrderProduct->is_active;

                /*$packageOrder =ProductUnitPackageDetail::MICRO_PACKAGE_ORDER_VALUE;
                if ($storePreOrderDetail->package_code == $storePreOrderDetail->super_unit_code){
                    $packageOrder =ProductUnitPackageDetail::SUPER_PACKAGE_ORDER_VALUE;
                }
                elseif ($storePreOrderDetail->package_code == $storePreOrderDetail->macro_unit_code){
                    $packageOrder =ProductUnitPackageDetail::MACRO_PACKAGE_ORDER_VALUE;
                }
                elseif ($storePreOrderDetail->package_code == $storePreOrderDetail->unit_code){
                    $packageOrder =ProductUnitPackageDetail::UNIT_PACKAGE_ORDER_VALUE;
                }*/

                $productPackagingUnitsArr = [
                    $storePreOrderDetail->super_unit_code,
                    $storePreOrderDetail->macro_unit_code,
                    $storePreOrderDetail->unit_code,
                    $storePreOrderDetail->micro_unit_code
                ];

                $storePreOrderDetail->package_order = ProductUnitPackageDetail::determinePackagingBreakingLevel($productPackagingUnitsArr, $storePreOrderDetail->package_code);
                return $storePreOrderDetail;
            });

            $storePreOrderDetails = $storePreOrderDetails->groupBy('is_taxable')
                ->keyBy(function ($value, $key) {
                    if ($key == 0) {
                        return 'non_taxable';
                    } else {
                        return 'taxable';
                    }
                });

            $taxableOrderDetails = collect();
            $nonTaxableOrderDetails = collect();
            $storePreOrderDispatchDetail = collect();

            $storePreOrderDispatchDetail = $storePreOrder->storePreOrderDispatchDetail;

            if (isset($storePreOrderDetails['taxable'])) {
                $taxableOrderDetails['tax_excluded_amount'] = $storePreOrderDetails['taxable']->sum('sub_total');
                $taxableOrderDetails['tax_amount'] = (13 / 100) * $taxableOrderDetails['tax_excluded_amount'];
                $taxableOrderDetails['total_amount'] = $taxableOrderDetails['tax_excluded_amount'] + $taxableOrderDetails['tax_amount'];
            } else {
                $storePreOrderDetails['taxable'] = collect();
            }

            if (isset($storePreOrderDetails['non_taxable'])) {
                $nonTaxableOrderDetails['total_amount'] = $storePreOrderDetails['non_taxable']->sum('sub_total');
            } else {
                $storePreOrderDetails['non_taxable'] = collect();
            }


            return [
                'store_pre_order' => $storePreOrder,
                'taxable_order_details' => $taxableOrderDetails,
                'taxable_order_products' => $storePreOrderDetails['taxable'],
                'non_taxable_order_details' => $nonTaxableOrderDetails,
                'non_taxable_order_products' => $storePreOrderDetails['non_taxable'],
                'storePreOrderDispatchDetail' => $storePreOrderDispatchDetail
            ];
        }catch (Exception $exception) {
            throw $exception;
        }
    }

}
