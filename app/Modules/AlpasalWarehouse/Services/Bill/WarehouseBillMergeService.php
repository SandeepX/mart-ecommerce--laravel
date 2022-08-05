<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/10/2020
 * Time: 3:13 PM
 */

namespace App\Modules\AlpasalWarehouse\Services\Bill;

use App\Modules\AlpasalWarehouse\Helpers\BillMerge\BillMergeHelper;
use App\Modules\AlpasalWarehouse\Models\BillMerge\BillMergeMaster;
use App\Modules\AlpasalWarehouse\Repositories\Bill\WarehouseBillMergeRepository;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehouseStorePreOrderService;
use App\Modules\AlpasalWarehouse\Services\StoreOrder\WHStoreOrderService;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderDetailHelper;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetailView;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderDetailRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderStatusLogRepository;
use App\Modules\Store\Repositories\StoreOrderRepository;
use App\Modules\Store\Repositories\StoreRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseBillMergeService
{

    private $warehouseBillMergeRepository;
    private $storeOrderRepository;
    private $storePreOrderDetailRepository;
    private $storePreOrderRepository;
    private $storePreOrderStatusLogRepository;
    private $warehouseStorePreOrderService;
    private $warehouseStoreOrderService;
    const VAT=13;

    public function __construct(
        WarehouseBillMergeRepository $warehouseBillMergeRepository,
        StorePreOrderDetailRepository $storePreOrderDetailRepository,
        StoreOrderRepository $storeOrderRepository,
        StorePreOrderRepository $storePreOrderRepository,
        StorePreOrderStatusLogRepository $storePreOrderStatusLogRepository,
        WarehouseStorePreOrderService $warehouseStorePreOrderService,
        WHStoreOrderService $warehouseStoreOrderService,
        StoreRepository $storeRepository,
        StoreBalance $storeBalance
    )
    {
        $this->warehouseBillMergeRepository=$warehouseBillMergeRepository;
        $this->storePreOrderDetailRepository = $storePreOrderDetailRepository;
        $this->storeOrderRepository = $storeOrderRepository;
        $this->storePreOrderRepository = $storePreOrderRepository;
        $this->storePreOrderStatusLogRepository = $storePreOrderStatusLogRepository;
        $this->warehouseStorePreOrderService = $warehouseStorePreOrderService;
        $this->warehouseStoreOrderService = $warehouseStoreOrderService;
        $this->storeRepository = $storeRepository;
        $this->storeBalance = $storeBalance;
    }

    public function findBillMergeMasterByCode($billMergeMasterCode){
        return $this->warehouseBillMergeRepository->findBillMergeMasterByCode($billMergeMasterCode);
    }

    public function getAllMergedOrders($wareHouseCode,$filterParameters){
        try{
            $mergedOrders = $this->warehouseBillMergeRepository->getAllMergedOrders($wareHouseCode,$filterParameters);
            return $mergedOrders;
        }catch (Exception $exception){
            throw $exception;
        }
    }
    public function getAllStoresOfWarehouse(){

        try{
            $warehouseCode=getAuthWarehouseCode();
            $stores = $this->warehouseBillMergeRepository->getAllStoresOfWarehouse($warehouseCode);
            return $stores;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function getAllStoreOrdersOfWarehouse($storeCode){

        try{
            $warehouseCode=getAuthWarehouseCode();
            $storeOrders = $this->warehouseBillMergeRepository->getAllStoreOrdersOfWarehouse($storeCode,$warehouseCode);
            return $storeOrders;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function getAllStorePreOrdersOfWarehouse($storeCode){

        try{
            $warehouseCode=getAuthWarehouseCode();
            $storePreOrders = $this->warehouseBillMergeRepository->getAllStorePreOrdersOfWarehouse($storeCode,$warehouseCode);
            return $storePreOrders;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function getStoreOrdersByCode($storeCode,$storeOrderCode){

        try{
            $warehouseCode=getAuthWarehouseCode();
            $storeOrders = $this->warehouseBillMergeRepository->getStoreOrdersByCode($storeCode,$warehouseCode,$storeOrderCode);
            return $storeOrders;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function getStorePreOrdersByCode($storeCode,$storePreOrderCode){

        try{
            $warehouseCode=getAuthWarehouseCode();
            $storeOrders = $this->warehouseBillMergeRepository->getStorePreOrdersByCode($storeCode,$warehouseCode,$storePreOrderCode);
            return $storeOrders;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function storeBillMergeAllDetails($validatedData){

        try{

            $storeCode = $validatedData['store_code'];
            $store = $this->storeRepository->findOrFailStoreByCode($storeCode);
            $currentStoreBalance = $this->storeBalance->getStoreActiveBalance($store);

            if($currentStoreBalance < 0){
                throw new Exception("The Store balance is in negative.");
            }

            $storeOrderCode = isset($validatedData['store_order_code']) ? $validatedData['store_order_code'] : [];

            $storePreOrderCode = isset($validatedData['store_preorder_code']) ? $validatedData['store_preorder_code'] : [];

            DB::beginTransaction();
            $billMergeMaster=$this->storeBillMergeMaster(getAuthWarehouseCode(),$storeCode);
            if(sizeof($storeOrderCode)){
                foreach ($storeOrderCode as $storeOrderCode)
                {
                    $billMergeDetailStoreOrder  = $this->storeBillMergeDetailForStoreOrder($billMergeMaster->bill_merge_master_code,$storeOrderCode);
                    $storeOrders = $this->getStoreOrdersByCode($storeCode,$storeOrderCode);
                    foreach($storeOrders as $storeOrder)
                    {
                        if(in_array($storeOrder->order_status,['ready_to_dispatch','dispatched','cancelled'])){
                            throw new Exception('Store Order # : '.$storeOrder->store_order_code.' cannot be merged since the status is '.$storeOrder->order_status.' !');
                        }

                       if($storeOrder->has_merged){
                           throw new Exception('Store Order # : '.$storeOrder->store_order_code.' has already been merged !');
                       }
                        $this->storeBillMergeProduct($billMergeMaster->bill_merge_master_code,$billMergeDetailStoreOrder->bill_merge_details_code,$storeOrder);
                    }

                    $storeOrder = $this->storeOrderRepository->findStoreOrderByCode($storeOrderCode);
                    $this->storeOrderRepository->updateHasMergedByStoreOrder($storeOrder);
                }
            }

            if(sizeof($storePreOrderCode)){
               // dd($storePreOrderCode);
                foreach ($storePreOrderCode as $storePreOrderCode)
                {
                    $billMergeDetailPreOrder =   $this->storeBillMergeDetailForStorePreOrder($billMergeMaster->bill_merge_master_code,$storePreOrderCode);
                    $storePreOrders=$this->getStorePreOrdersByCode($storeCode,$storePreOrderCode);
                    //dd($storePreOrders);
                    foreach($storePreOrders as $storePreOrder)
                    {
                        if(!in_array($storePreOrder->order_status,['finalized'])){
                            throw new Exception('Store Pre Order # : '.$storePreOrder->store_preorder_code.' cannot be merged since the status is '.$storePreOrder->order_status.' !');
                        }

                        if($storePreOrder->has_merged){
                            throw new Exception('Store Pre Order # : '.$storePreOrder->store_preorder_code.' has already been merged !');
                        }
                        $this->storeBillMergeProduct($billMergeMaster->bill_merge_master_code,$billMergeDetailPreOrder->bill_merge_details_code,$storePreOrder);
                    }

                    $storePreOrder = $this->storePreOrderRepository->getStorePreOrderByPreOrderCode($storePreOrderCode);
                    $this->storePreOrderRepository->updateHasMergedByStorePreOrder($storePreOrder);

                }
            }

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw  $exception;
        }
    }

    public function storeBillMergeMaster($warehouseCode,$storeCode){

        try {
              $billMergeMaster = $this->warehouseBillMergeRepository->createStoreBillMergeMaster($warehouseCode,$storeCode);
        } catch (Exception $exception) {
            throw  $exception;
        }
        return $billMergeMaster;
    }
    public function storeBillMergeProduct($billMergeMasterCode,$billMergeDetailCode,$mergedOrder){

        try {
            $billMergeProduct = $this->warehouseBillMergeRepository->createStoreBillMergeProduct($billMergeMasterCode,$billMergeDetailCode,$mergedOrder);
        } catch (Exception $exception) {
            throw  $exception;
        }
        return $billMergeProduct;
    }

    public function storeBillMergeDetailForStoreOrder($billMergeMasterCode,$storeOrderCode){


        try {
            $billMergeDetailForStoreOrder = $this->warehouseBillMergeRepository->createStoreBillMergeDetailForStoreOrder($billMergeMasterCode,$storeOrderCode);
        } catch (Exception $exception) {
            throw  $exception;
        }
        return $billMergeDetailForStoreOrder;


    }
    public function storeBillMergeDetailForStorePreOrder($billMergeMasterCode,$storePreOrderCode){

        try {
            $billMergeDetail = $this->warehouseBillMergeRepository->createStoreBillMergeDetailForStorePreOrder($billMergeMasterCode,$storePreOrderCode);
        } catch (Exception $exception) {
            throw  $exception;
        }
        return $billMergeDetail;
    }

    public function getProductsByBillMergeMasterCode($billMergeMasterCode,$with=[]){

        try{

            $mergedProducts = $this->warehouseBillMergeRepository->getProductsByBillMergeMasterCode($billMergeMasterCode,$with);

            $mergedProducts = $mergedProducts->map(function ($billMeregeProduct) {
                // $billMeregeProduct->amount = $billMeregeProduct->quantity * ($billMeregeProduct->unit_rate);

                // $packageOrder =ProductUnitPackageDetail::MICRO_PACKAGE_ORDER_VALUE;
                $packageMicroQuantity = 1;
                if (isset($billMeregeProduct->package_code) && ($billMeregeProduct->package_code == $billMeregeProduct->super_unit_code)){
                    // $packageOrder =ProductUnitPackageDetail::SUPER_PACKAGE_ORDER_VALUE;
                    $packageMicroQuantity = $billMeregeProduct->micro_to_unit_value
                        * $billMeregeProduct->unit_to_macro_value * $billMeregeProduct->macro_to_super_value;
                }
                elseif (isset($billMeregeProduct->package_code) && ($billMeregeProduct->package_code == $billMeregeProduct->macro_unit_code)){
                    // $packageOrder =ProductUnitPackageDetail::MACRO_PACKAGE_ORDER_VALUE;
                    $packageMicroQuantity =  $billMeregeProduct->micro_to_unit_value * $billMeregeProduct->unit_to_macro_value;
                }
                elseif (isset($billMeregeProduct->package_code)  && ($billMeregeProduct->package_code == $billMeregeProduct->unit_code)){
                    // $packageOrder =ProductUnitPackageDetail::UNIT_PACKAGE_ORDER_VALUE;
                    $packageMicroQuantity =  $billMeregeProduct->micro_to_unit_value;
                }
                // $billMeregeProduct->package_order = $packageOrder;
                $billMeregeProduct->package_micro_quantity = (int)$packageMicroQuantity;
                return $billMeregeProduct;
            });

            $mergedProducts=$mergedProducts->map(function($mergedProduct){
                if($mergedProduct->is_taxable==1)
                {
                       $mergedProduct->subtotal=$mergedProduct->subtotal + ($mergedProduct->subtotal*self::VAT/100);
                }
                return $mergedProduct;
            });
            return $mergedProducts;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function findorFailBillMergeDetailByCode($billMergeDetailCode){

        try{
          $billMergeDetail = $this->warehouseBillMergeRepository->findorFailBillMergeDetailByCode($billMergeDetailCode);
            return $billMergeDetail;
        }catch (Exception $exception){
            throw $exception;
        }
    }
    public function findorFailBillMergeProductByCode($billMergeProductCode){
        try{
            $billMergeProduct = $this->warehouseBillMergeRepository->findorFailBillMergeProductByCode($billMergeProductCode);
            return $billMergeProduct;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function updateBillMergeProductDetail($billMergeDetailCode,$billMergeProductCode,$vaidatedData){

        try{
            $billMergeDetail = $this->warehouseBillMergeRepository->findorFailBillMergeDetailByCode($billMergeDetailCode);
            $billMergeProductDetail = $this->warehouseBillMergeRepository->findorFailBillMergeProductByCode($billMergeProductCode);


            DB::beginTransaction();
            if($billMergeDetail->bill_type === 'cart'){

            $storeOrder = $this->storeOrderRepository->findStoreOrderByCode($billMergeDetail->bill_code);
            if(!in_array($storeOrder->delivery_status,['accepted','processing']) ){
                throw new Exception('product cannot be updated since store order status is in '.$storeOrder->delivery_status);
            }


             $storeOrderDetail = $this->storeOrderRepository->findOrderDetailByOrderProductAndVariantCodeWithPackageCode(
                 $billMergeDetail->bill_code,
                 $billMergeProductDetail->product_code,
                 $billMergeProductDetail->product_variant_code,
                 $billMergeProductDetail->package_code
             );

             $this->storeOrderRepository->updateStatusAndQuantityInBillMerge($storeOrderDetail,$vaidatedData);

            }elseif($billMergeDetail->bill_type == 'preorder'){

                $storePreOrder = $this->storePreOrderRepository->getStorePreOrderByPreOrderCode($billMergeDetail->bill_code);
                if($storePreOrder->status != 'finalized'){
                    throw new Exception('product cannot be updated since store order status is in '.$storePreOrder->status);
                }
                $storePreOrderDetail = $this->storePreOrderDetailRepository
                    ->findPreOrderDetailByOrderProductAndVariantCodeWithPackageCode(
                    $billMergeDetail->bill_code,
                    $billMergeProductDetail->product_code,
                    $billMergeProductDetail->product_variant_code,
                    $billMergeProductDetail->package_code
                );

                $this->storePreOrderDetailRepository->updateStatusAndQuantityInBillMerge($storePreOrderDetail,$vaidatedData);

            }else{
                throw  new Exception('Bill Type Not Found');
            }
            $this->warehouseBillMergeRepository->updateBillMergeProduct($billMergeProductDetail,$vaidatedData);

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
    public function updateBillMergeStatusByWarehouse($validatedData,$billMergeMasterCode)
    {
      try{
          $billMergeMaster = $this->warehouseBillMergeRepository->findBillMergeMasterByCode($billMergeMasterCode);

          if($billMergeMaster->status !='pending'){
              throw  new Exception('Can update product only in pending state');
          }

          $billMergeDetails = $this->warehouseBillMergeRepository->getBillMergeDetailByMasterCode($billMergeMasterCode);

          DB::beginTransaction();

           $billMergeDispatchStatus = 0;
          foreach($billMergeDetails as $billMergeDetail){

            if($billMergeDetail->bill_type == 'cart'){

               $storeOrder = $this->storeOrderRepository->findStoreOrderByCode($billMergeDetail->bill_code);
               if(!in_array($storeOrder->delivery_status,['accepted','processing'])){
                   throw new Exception(
                       'Only accepted or processing store normal order should be in bill merge list ! : Normal Order # : '.$storeOrder->store_order_code.'');
               }

               $validatedData['delivery_status']= $validatedData['status'];
               $storeValidatedData = $validatedData;

               $billMergeProductsofDetail = $this->storeOrderRepository->getStoreOrderDetailsByStoreOrderCode($billMergeDetail->bill_code);
                $orderitems=[];
                $acceptanceStatus=[];
                $dispatchableQuantity=[];
                $formattedData=[];
               foreach($billMergeProductsofDetail as $key => $datum)
               {
                   array_push($orderitems,$datum->store_order_detail_code);
                   array_push($acceptanceStatus,$datum->acceptance_status);
                   array_push($dispatchableQuantity,$datum->quantity);
               }

               $formattedData['order_items']=$orderitems;
               $formattedData['dispatchable_quantity']=$dispatchableQuantity;
               $formattedData['acceptance_status']=$acceptanceStatus;

               ///dd($formattedData);
                $storeOrder = $this->storeOrderRepository->findStoreOrderByCode($billMergeDetail->bill_code);
                $countAcceptedStoreOrderDetails = $storeOrder->details()->where('acceptance_status','accepted')->count();
                if($storeOrder->has_merged==1  && $countAcceptedStoreOrderDetails == 0){
                    $validatedData['delivery_status'] = 'cancelled';
                }
                $formattedData =  array_merge($formattedData,$validatedData);
                $storeOrder = $this->warehouseStoreOrderService->updateStoreOrderDeliveryStatus($formattedData,$billMergeDetail->bill_code);
                if($storeOrder->delivery_status == "ready_to_dispatch"){
                    $billMergeDispatchStatus += 1;
                }

            }
            elseif($billMergeDetail->bill_type == 'preorder'){
                $validatedData['delivery_status']= $validatedData['status'];

                $storePreOrderIns = $this->storePreOrderRepository->getStorePreOrderByPreOrderCode($billMergeDetail->bill_code);
                if($storePreOrderIns->status != 'finalized'){
                    throw  new Exception(
                        'Only finalized pre-Order should be in bill merge list ! : Pre Order #  : '.$storePreOrderIns->store_preorder_code.''
                    );
                }
                $storePreOrderDetails =  $this->storePreOrderDetailRepository->getStorePreOrderDetailsFromViewByStorePreOrderCode($billMergeDetail->bill_code);

                $refundCalculatingStorePreOrderDetails = StorePreOrderDetailView::whereHas('relatedStorePreOrderDetail.warehousePreOrderProduct',function ($query){
                    $query->where('is_active',1);
                })
                    ->whereNull('deleted_at')
                    ->where('store_preorder_code',$billMergeDetail->bill_code)->get();

                if(count($storePreOrderDetails) < 1){
                    throw new Exception('No Store Pre Order Item Found ! : Cannot Update Status');
                }

                $isDeliveryAccepted = StorePreOrderDetailHelper::isAnyStorePreOrderDetailDeliveryAccepted($billMergeDetail->bill_code);
                if($storePreOrderIns->has_merged == 1 && !$isDeliveryAccepted){
                    $validatedData['delivery_status'] = 'cancelled';
                }

                $refundableAmount = 0;
                if ( $validatedData['delivery_status'] == 'cancelled') {
                    foreach ($storePreOrderDetails as $storePreOrderDetailViewObj){
                        $storePreOrderDetailViewObj->relatedStorePreOrderDetail->update([
                            'delivery_status'=> 0
                        ]);
                    }
                    $refundableAmount = $this->warehouseStorePreOrderService->getRefundableAmountIfCancelled($refundCalculatingStorePreOrderDetails);
                }

                if($validatedData['delivery_status'] == 'ready_to_dispatch'){
                    if (!$isDeliveryAccepted) {
                        throw new Exception('Cannot dispatch pre-order: at least one order delivery should be accepted.');
                    }

                    $dispatchableItems = $this->warehouseStorePreOrderService->gettingDispatchableItems($billMergeDetail->bill_code);

                    $this->warehouseStorePreOrderService->updatingTheStockAfterDispatching($dispatchableItems);
                    $refundableAmount= $this->warehouseStorePreOrderService->getRefundableAmountIfDisptached($refundCalculatingStorePreOrderDetails);
                    $billMergeDispatchStatus += 1;
                }

                if( $validatedData['delivery_status'] != 'processing') {
                    if($refundableAmount > 0) {
                        $this->warehouseStorePreOrderService->savingRefundableAmountToStoreBalance($billMergeDetail->bill_code, $billMergeMaster->store_code, $refundableAmount);
                    }
                }

//                if ( $validatedData['delivery_status'] == 'dispatched') {
//                    $this->storePreOrderRepository->createStorePreOrderDispatchDetail($validatedData, $billMergeDetail->bill_code);
//                }

                $updatedValidatedData = $validatedData;
                $updatedValidatedData['status'] = $validatedData['delivery_status'];

                $storePreOrder = $this->storePreOrderRepository->updatePreOrderStatus($storePreOrderIns, $updatedValidatedData);
                $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder, $updatedValidatedData);

            }else{
                throw  new Exception('Bill Type Undefined');
            }
          }

          $validatedData['status'] = $billMergeDispatchStatus > 0 ? 'ready_to_dispatch' : 'cancelled';

           $this->warehouseBillMergeRepository->updateBillMergeStatus($billMergeMaster,$validatedData);

           DB::commit();

           return true;
      }catch (Exception $exception){
          DB::rollBack();
          throw $exception;
      }

    }

    public function getBillMergeProductsforPdfBill($billMergeMasterCode){

      try{
          $with = ['store','warehouse'];
          $billMergeMaster = $this->warehouseBillMergeRepository->findBillMergeMasterByCode($billMergeMasterCode,$with);

          $orderInfo['store_name'] =$billMergeMaster['store']['store_name'];
          $orderInfo['invoice_num'] = $billMergeMaster['bill_merge_master_code'];
          $orderInfo['store_vat_pan_type'] = ucwords($billMergeMaster['store']['pan_vat_type']);
          $orderInfo['store_vat_pan'] = $billMergeMaster['store']['pan_vat_no'];
          $orderInfo['store_contact_num'] = $billMergeMaster->store->store_contact_phone . '/' . $billMergeMaster->store->store_contact_mobile;
          $orderInfo['store_address'] =  ucwords($billMergeMaster->store->store_landmark_name);
          $orderInfo['transaction_date'] =date('Y-m-d', strtotime($billMergeMaster['created_at']));
          $orderInfo['warehouse_name'] = $billMergeMaster->warehouse->warehouse_name;

          $billMergeProducts = BillMergeHelper::getBillMergeAcceptedProductForWarehouse($billMergeMasterCode);
          $billMergeProducts = $billMergeProducts->map(function ($billMeregeProduct) {

              $packageMicroQuantity = 1;
              if ($billMeregeProduct->package_code && ($billMeregeProduct->package_code == $billMeregeProduct->super_unit_code)){
                  $packageMicroQuantity = $billMeregeProduct->micro_to_unit_value
                      * $billMeregeProduct->unit_to_macro_value * $billMeregeProduct->macro_to_super_value;
              }
              elseif ($billMeregeProduct->package_code && ($billMeregeProduct->package_code == $billMeregeProduct->macro_unit_code)){
                  $packageMicroQuantity =  $billMeregeProduct->micro_to_unit_value * $billMeregeProduct->unit_to_macro_value;
              }
              elseif ($billMeregeProduct->package_code && ($billMeregeProduct->package_code == $billMeregeProduct->unit_code)){
                  $packageMicroQuantity =  $billMeregeProduct->micro_to_unit_value;
              }
              $billMeregeProduct->package_micro_quantity = (int)$packageMicroQuantity;

              $productPackagingUnitsArr =[
                  $billMeregeProduct->super_unit_code ,
                  $billMeregeProduct->macro_unit_code ,
                  $billMeregeProduct->unit_code ,
                  $billMeregeProduct->micro_unit_code
              ];

              $billMeregeProduct->package_order = ProductUnitPackageDetail::determinePackagingBreakingLevel($productPackagingUnitsArr,$billMeregeProduct->package_code);

              return $billMeregeProduct;
          });

          //dd($billMergeProducts);


          $billMergeProducts = $billMergeProducts->groupBy('is_taxable')
              ->keyBy(function ($value, $key) {
                  if ($key == 0) {
                      return 'non_taxable';
                  } else {
                      return 'taxable';
                  }
              });

          $fullOrderDetailsWithChunk=collect();
          if (isset($billMergeProducts['taxable'])){
              $fullOrderDetailsWithChunk['taxable']=collect($billMergeProducts['taxable']->chunk(16));
          }
          if (isset($billMergeProducts['non_taxable'])){
              $fullOrderDetailsWithChunk['non_taxable'] =collect($billMergeProducts['non_taxable']->chunk(16));
          }

          foreach ($fullOrderDetailsWithChunk as $key => $fullOrderDetails) {

              foreach($fullOrderDetails as $item=>$fullOrderDetail) {
                  $taxableAmount = 0;
                  $vat = '';

                  $subTotal = $fullOrderDetail->sum('subtotal');
                  $totalQty=$fullOrderDetail->sum('quantity');

                  if ($key == 'taxable') {
                      $taxableAmount = (13 / 100) * $subTotal;
                      $grandTotal = roundPrice($subTotal + $taxableAmount);
                      $vat = 13 . '%';
                  } else {
                      $grandTotal = $subTotal;
                  }

                  $fullOrderDetailsWithChunk[$key][$item] = [
                      'sub_total' => $subTotal,
                      'store_order_details' => $fullOrderDetail,
                      'grand_total' => $grandTotal,
                      'taxable_amount' => $taxableAmount == 0 ? '' : $taxableAmount,
                      'vat' => $vat,
                      'total_qty'=>$totalQty
                  ];
              }
          }

          return[
              'order_info' =>$orderInfo,
              'bill_merge_products'=>$fullOrderDetailsWithChunk
          ];

      }catch (Exception $exception){
          throw  $exception;
      }
    }

    public function getMergeOrderDetailsByMasterCode($billMergeMasterCode){

        try{
            $billMergeDetails = $this->warehouseBillMergeRepository->getBillMergeDetailByMasterCode($billMergeMasterCode);
            return $billMergeDetails;
        }catch (Exception $exception){
            throw $exception;
        }

    }
}
