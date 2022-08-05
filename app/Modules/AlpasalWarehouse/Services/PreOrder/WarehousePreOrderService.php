<?php


namespace App\Modules\AlpasalWarehouse\Services\PreOrder;


use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderHelper;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderProductFilter;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderProductRepository;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderRepository;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Events\StoreWalletTransactionEvent;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderStatusLogRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Interfaces\TransactionConfigurationInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderService implements  TransactionConfigurationInterface
{
    use ImageService;
    private $warehousePreOrderRepository, $productRepository, $warehousePreOrderProductRepository;
    private $storeRepository;
    private $transactionNotificationConfiguration;
    private $storePreOrderRepository,$storeBalanceManagementRepository,$storePreOrderStatusLogRepository;
    private $storeBalance;

    public function __construct(WarehousePreOrderRepository $warehousePreOrderRepository,
                                ProductRepository $productRepository,
                                WarehousePreOrderProductRepository $warehousePreOrderProductRepository,
                                StorePreOrderRepository $storePreOrderRepository,
                                StoreBalanceManagementRepository $storeBalanceManagementRepository,
                                StorePreOrderStatusLogRepository $storePreOrderStatusLogRepository,
                                StoreRepository $storeRepository,
                                TransactionNotificationConfiguration $transactionNotificationConfiguration,
                                 StoreBalance $storeBalance
    ){
        $this->warehousePreOrderRepository = $warehousePreOrderRepository;
        $this->productRepository = $productRepository;
        $this->warehousePreOrderProductRepository = $warehousePreOrderProductRepository;
        $this->storePreOrderRepository = $storePreOrderRepository;
        $this->storeBalanceManagementRepository = $storeBalanceManagementRepository;
        $this->storePreOrderStatusLogRepository =$storePreOrderStatusLogRepository;
        $this->storeRepository =$storeRepository;
        $this->transactionNotificationConfiguration =$transactionNotificationConfiguration;
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

    public function getPreOrdersOfWarehouse($warehouseCode)
    {
        return $this->warehousePreOrderRepository->getWarehousePreOrdersByWarehouseCode($warehouseCode);
    }

    public function getPreOrdersOfWarehouseWith($warehouseCode, array $with)
    {
        return $this->warehousePreOrderRepository->getWarehousePreOrdersByWarehouseCode($warehouseCode, $with);
    }

    public function getDisplayableWarehousePreOrdersByWarehouseCode($warehouseCode, $with = [])
    {
        return $this->warehousePreOrderRepository->getDisplayablePreOrdersByWarehouseCode($warehouseCode, $with);
    }

    public function getDisplayableLimitedWarehousePreOrdersByWarehouseCode(
        $warehouseCode,
        $paginateBy,
        $with = []
    )
    {
        return $this->warehousePreOrderRepository
            ->getDisplayableLimitedPreOrdersByWarehouseCode(
                $warehouseCode,
                $paginateBy,
                $with
            );
    }

    public function getFinalizableWarehousePreOrdersByWarehouseCode($warehouseCode, $with = [])
    {
        return $this->warehousePreOrderRepository->getFinalizablePreOrdersByWarehouseCode($warehouseCode, $with);
    }

    public function getPaginatedPreOrdersOfWarehouse($warehouseCode,$filterParameters, $paginateBy)
    {
        return $this->warehousePreOrderRepository->getPaginatedWarehousePreOrdersByWarehouseCode($warehouseCode,$filterParameters, $paginateBy);
    }

    public function findOrFailWarehousePreOrderByCode($warehousePreOrderCode)
    {
        return $this->warehousePreOrderRepository->findOrFailPreOrderByCode($warehousePreOrderCode);
    }

    public function findOrFailWarehousePreOrderByWarehouseCode($warehousePreOrderCode, $warehouseCode, $with = [])
    {
        return $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode, $warehouseCode, $with);
    }

    public function storeWarehousePreOrder($validatedData)
    {
        try {
            $authWarehouseCode=getAuthWarehouseCode();
            $validatedData['warehouse_code'] = $authWarehouseCode;
            if(isset($validatedData['banner_image'])){
                $fileNameToStore = $this->storeImageInServer($validatedData['banner_image'], WarehousePreOrderListing::IMAGE_PATH);
                $validatedData['banner_image'] = $fileNameToStore;
            }
            $validatedData['is_active'] = isset($validatedData['is_active']) ? 1 : 0;
//            if (WarehousePreOrderHelper::isWarehousePreOrdersDateOverlapping($authWarehouseCode,
//                $validatedData['start_time'],$validatedData['end_time'])){
//                throw new Exception('Input dates overlaps other pre-order dates,please select other dates.');
//            }
            DB::beginTransaction();
            $warehousePreOrder = $this->warehousePreOrderRepository->create($validatedData);
            DB::commit();
            return $warehousePreOrder;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateWarehousePreOrder($validatedData,$warehousePreOrderCode)
    {
        try {
            $authWarehouseCode=getAuthWarehouseCode();
            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode,$authWarehouseCode);

            if ($warehousePreOrder->isFinalized()){
                throw new Exception(
                    'Cannot update after the pre-order listing has been finalized.'
                );
            }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot update: pre-order was cancelled.');
            }

            if ($warehousePreOrder->isPastStartTime()){
                $validatedData['start_time']=$warehousePreOrder->start_time;
            }
//            if (WarehousePreOrderHelper::isWarehousePreOrdersDateOverlapping($authWarehouseCode,
//                $validatedData['start_time'],$validatedData['end_time'],$warehousePreOrder->warehouse_preorder_listing_code)){
//                throw new Exception('Input dates overlaps other pre-order dates,please select other dates.');
//            }
            if(isset($validatedData['banner_image'])){
                $this->deleteImageFromServer(WarehousePreOrderListing::IMAGE_PATH, $warehousePreOrder->banner_image);
                $validatedData['banner_image'] = $this->storeImageInServer($validatedData['banner_image'], WarehousePreOrderListing::IMAGE_PATH);
            }

            $validatedData['warehouse_code'] = $authWarehouseCode;
            $validatedData['is_active'] = isset($validatedData['is_active']) ? 1 : 0;
            DB::beginTransaction();
            $warehousePreOrder = $this->warehousePreOrderRepository->update($warehousePreOrder,$validatedData);
            DB::commit();
            return $warehousePreOrder;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }



    public function getPaginatedProductsOfWarehousePreOrder($warehousePreOrderCode, $warehouseCode, $paginateBy = 20)
    {
        try {
            $with = ['product', 'productVariant'];
            $preOrderProducts = $this->warehousePreOrderProductRepository->getPaginatedPreOrderProductsByWarehouseCode($warehousePreOrderCode,
                $warehouseCode, $paginateBy, $with);

            return $preOrderProducts;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getPaginatedGroupedProductsOfWarehousePreOrder($filterParameters, $paginateBy = 20)
    {
        try {

            $with = ['product', 'productVariant','product.vendor'];
            $warehousePreOrderProducts = WarehousePreOrderProductFilter::filterPaginatedWarehouseGroupedPreOrderProducts(
                $filterParameters, $paginateBy, $with);

            return $warehousePreOrderProducts;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function storeProductsPriceSettingForPreOrder($validatedData, $warehousePreOrderCode, $productCode)
    {
        try {
            $authWarehouseCode = getAuthWarehouseCode();

            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode, $authWarehouseCode);

            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot add product after finalization time.');
            }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot add product: pre-order was cancelled.');
            }
            if (WarehousePreOrderHelper::doesPreOrderConsistProduct($warehousePreOrderCode, $productCode)) {
                throw new Exception('Product was already added to preorder.');
            }
            $product = $this->productRepository->findOrFailProductByCodeWith($productCode, ['productVariants']);
            $productVariantsCode = $product->productVariants->pluck('product_variant_code')->toArray();
            $toBeStoredData = [];
            //dd($validatedData['mrp']);
            foreach (array_filter($validatedData['mrp']) as $key => $mrp) {
                if (count($productVariantsCode) > 0) {
                    if (!in_array($validatedData['product_variant_code'][$key], $productVariantsCode)) {
                        throw new Exception('Variant not found for the product');
                    }
                }
                array_push($toBeStoredData, [
                    'product_code' => $product->product_code,
                    'product_variant_code' => $validatedData['product_variant_code'][$key],
                    'mrp' => $mrp,
                    'admin_margin_type' => $validatedData['admin_margin_type'][$key],
                    'admin_margin_value' => (isset($validatedData['admin_margin_value'][$key])) ? $validatedData['admin_margin_value'][$key] : 0,
                    'wholesale_margin_type' => $validatedData['wholesale_margin_type'][$key],
                    'wholesale_margin_value' => (isset($validatedData['wholesale_margin_value'][$key])) ?$validatedData['wholesale_margin_value'][$key] :0 ,
                    'retail_margin_type' => $validatedData['retail_margin_type'][$key],
                    'retail_margin_value' =>(isset($validatedData['retail_margin_value'][$key])) ? $validatedData['retail_margin_value'][$key] : 0,
                    'is_active' => 1,
                ]);
            }

            //temporary
            foreach($toBeStoredData as $key => $singleData){

                $productVariantCode= isset($singleData['product_variant_code'])?$singleData['product_variant_code'] : null;

                $productPackagingDetail = ProductUnitPackageDetail::where('product_code',$productCode)
                    ->where('product_variant_code',$productVariantCode)->first();
                if (!$productPackagingDetail){
                    throw new Exception('Product packaging detail not found for product '. $productCode);
                }

                if ($productPackagingDetail->macro_to_super_value){
                    $microValue=$productPackagingDetail->macro_to_super_value * $productPackagingDetail->unit_to_macro_value *$productPackagingDetail->micro_to_unit_value;
                    $singleData['mrp'] = $singleData['mrp'] /$microValue;
                }elseif ($productPackagingDetail->unit_to_macro_value){
                    $singleData['mrp'] = $singleData['mrp'] /($productPackagingDetail ->unit_to_macro_value *$productPackagingDetail ->micro_to_unit_value);
                }
                elseif ($productPackagingDetail->micro_to_unit_value){
                    $singleData['mrp'] = $singleData['mrp'] /$productPackagingDetail ->micro_to_unit_value;
                }else{
                    $singleData['mrp'] = $singleData['mrp'];
                }
            }

            //end of temporary
            DB::beginTransaction();
            $this->warehousePreOrderProductRepository->addProductToWarehousePreOrder($warehousePreOrder, $toBeStoredData);
            DB::commit();
            return $warehousePreOrder;

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    public function updateProductPriceSettingForPreOrder($validatedData, $warehousePreOrderCode, $productCode)
    {


        try {
            $authWarehouseCode = getAuthWarehouseCode();
            $validatedData['admin_margin_value'] = (isset($validatedData['admin_margin_value'])) ? $validatedData['admin_margin_value'] : 0;
            $validatedData['wholesale_margin_value'] = (isset($validatedData['wholesale_margin_value'])) ? $validatedData['wholesale_margin_value'] : 0;
            $validatedData['retail_margin_value'] = (isset($validatedData['retail_margin_value'])) ? $validatedData['retail_margin_value'] : 0;


            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode, $authWarehouseCode);
            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot update product after finalization time.');
            }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot update product: pre-order was cancelled.');
            }
            $product = $this->productRepository->findOrFailProductByCodeWith($productCode, ['productVariants']);
            $productVariantsCode = $product->productVariants->pluck('product_variant_code')->toArray();

            if(count($productVariantsCode) > 0){
                if (!in_array($validatedData['product_variant_code'], $productVariantsCode)) {
                    throw new Exception('Variant not found for the product');
                }
            }
            else{
                $validatedData['product_variant_code'] =null;
            }

            $validatedData['is_active'] =1;
            $validatedData['product_code'] = $product->product_code;
           // $validatedData['warehouse_preorder_listing_code'] = $warehousePreOrderCode;


            //dd($toBeStoredData);
            DB::beginTransaction();
            $this->warehousePreOrderProductRepository->updateOrCreateWarehousePreOrderProduct($warehousePreOrder, $validatedData);
            DB::commit();
            return $warehousePreOrder;

        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateActiveStatus($warehousePreOrderCode)
    {

        try {

            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode, getAuthWarehouseCode());
            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot update after finalization time.');
            }
            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot update status: pre-order was cancelled.');
            }

            DB::beginTransaction();
            $warehousePreOrder->is_active == 1 ? $data['is_active'] = 0 : $data['is_active'] = 1;
            $this->warehousePreOrderRepository->updateActiveStatus($data, $warehousePreOrder);
            DB::commit();
            return $warehousePreOrder;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }

    }

    public function finalizeWarehousePreOrders()
    {
        try {
            $warehousePreOrders = $this->warehousePreOrderRepository->getFinalizablePreOrdersByWarehouseCode(getAuthWarehouseCode());
           // dd($warehousePreOrders);
            $warehousePreOrdersCode= $warehousePreOrders->pluck('warehouse_preorder_listing_code')->toArray();
            if (count($warehousePreOrders) < 1){
                throw new Exception('No Pre-Orders to finalize.');
            }

            $storePreOrdersCodeToBeFinalized=[];
            $storePreOrders = $this->storePreOrderRepository->getStorePreOrdersByWarehousePreOrderCodes($warehousePreOrdersCode);

            DB::beginTransaction();
            $this->warehousePreOrderRepository->finalizeMassPreOrders($warehousePreOrdersCode);
            foreach ($storePreOrders as $storePreOrder){
                $storeCurrentBalance=StoreTransactionHelper::getStoreCurrentBalance($storePreOrder->store_code);
                $totalPreOrderCost= StorePreOrderHelper::getTotalAmountOfStorePreOrder($storePreOrder->store_preorder_code);

                if ($storeCurrentBalance >= $totalPreOrderCost){
                    $storeBalanceMaster=$this->storeBalanceManagementRepository->saveTransaction([
                        'store_code'=>$storePreOrder->store_code,
                        'transaction_amount' => $totalPreOrderCost,
                        'transaction_type'=>'preorder',
                        'remarks'=> 'preorder balance deduct',
                        'current_balance'=>roundPrice($storeCurrentBalance - $totalPreOrderCost),
                        'created_by' => getAuthUserCode()
                    ]);

                    $this->storeBalanceManagementRepository->saveStoreBalancePreOrderDetail([
                        'store_balance_master_code' => $storeBalanceMaster->store_balance_master_code,
                        'store_preorder_code' => $storePreOrder->store_preorder_code
                    ]);

                    $statusLogData['remarks'] = 'Store pre-ordered item finalized';
                    $statusLogData['status'] = 'finalized';
                    $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder,$statusLogData);
                    array_push($storePreOrdersCodeToBeFinalized,$storePreOrder->store_preorder_code);

                }
            }
            $this->storePreOrderRepository->finalizeMassPreOrders($storePreOrdersCodeToBeFinalized);



            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }

    }

    public function finalizeWarehousePreOrderWithNotification($warehousePreOrderListingCode){

        $this->setSMSSendStatus(true);
        return $this->finalizeWarehousePreOrder($warehousePreOrderListingCode);
    }

    public function finalizeWarehousePreOrder($warehousePreOrderListingCode){
        try{
            $warehousePreOrder =$this->warehousePreOrderRepository->findOrFailWithLockPreOrderByWarehouseCode($warehousePreOrderListingCode,getAuthWarehouseCode());
//            if($warehousePreOrder->is_active == 0){
//                throw new Exception('Pre-Order : ('.$warehousePreOrder->pre_order_name.' ) cannot be finalized because it is inactive !');
//            }
            if(!$warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Pre-Order : ('.$warehousePreOrder->pre_order_name.' ) finalization time has not ended.');
            }
            if ($warehousePreOrder->isFinalized()){
                throw new Exception('Pre-Order : ('.$warehousePreOrder->pre_order_name.' ) already finalized.');
            }
            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Cannot finalize : Pre-Order : ('.$warehousePreOrder->pre_order_name.' )  was cancelled.');
            }

            $warehousePreOrderListingsCode = (array)$warehousePreOrderListingCode;
            $storePreOrders = $this->storePreOrderRepository->getStorePreOrdersByWarehousePreOrderCodes(
                $warehousePreOrderListingsCode,
                //relations
                [
                    'warehousePreOrderListing:warehouse_preorder_listing_code,pre_order_name',
                    'store:store_code,store_contact_mobile'
                ]
            );


//            if (count($storePreOrders) < 1){
//                throw new Exception('No store pre orders found in this pre-order ('.$warehousePreOrder->pre_order_name.') for finalization.');
//            }


            DB::beginTransaction();
            $this->warehousePreOrderRepository->finalizeMassPreOrders($warehousePreOrderListingsCode);

            if (count($storePreOrders) > 0) {
                $storePreOrdersCodeToBeFinalized=[];
                $finalizedStorePreOrderForSms=[];
                $storePreOrdersCodeToBeCanceled=[];
                foreach ($storePreOrders as $i => $storePreOrder) {
                    if (!$storePreOrder->early_finalized) {

                        if (!StorePreOrderHelper::isStorePreOrderFinalizableByReason
                        ($storePreOrder->store_preorder_code, 'non_deleted_preorder_details')) {

                            $statusLogData['remarks'] = 'Store Pre Order Cancelled because it contains all deleted pre order items';
                            $statusLogData['status'] = 'cancelled';
                            $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder, $statusLogData);
                            array_push($storePreOrdersCodeToBeCanceled, $storePreOrder->store_preorder_code);
                        } elseif (!StorePreOrderHelper::isStorePreOrderFinalizableByReason
                        ($storePreOrder->store_preorder_code, 'active_preorder_products')) {

                            $statusLogData['remarks'] = 'Store Pre Order Cancelled because it contains all inactive pre order items';
                            $statusLogData['status'] = 'cancelled';
                            $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder, $statusLogData);
                            array_push($storePreOrdersCodeToBeCanceled, $storePreOrder->store_preorder_code);
                        } else {
                            //$storeCurrentBalance=StoreTransactionHelper::getLatestStoreCumulativeBalance($storePreOrder->store_code);
                            $store = $this->storeRepository->findOrFailStoreByCode($storePreOrder->store_code);
                            $storeCurrentBalance = $this->storeBalance->getStoreWalletCurrentBalance($store);
                            $totalPreOrderCost = StorePreOrderHelper::getTotalAmountOfStorePreOrder($storePreOrder->store_preorder_code);

                            if ($totalPreOrderCost != 0 && $storeCurrentBalance >= $totalPreOrderCost) {
                                /// for wallet transaction creation and current balance update starts here
                                $this->prepareStoreWalletTransactionDetails($storePreOrder, $totalPreOrderCost);
                                // ends here wallet transaction

                                $statusLogData['remarks'] = 'Store pre-ordered item finalized';
                                $statusLogData['status'] = 'finalized';
                                $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder, $statusLogData);
                                array_push($storePreOrdersCodeToBeFinalized, $storePreOrder->store_preorder_code);

                            } else {

                                $statusLogData['remarks'] = 'Store pre-ordered item could not be finalized due to insufficient balance';
                                $statusLogData['status'] = 'cancelled';
                                $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder, $statusLogData);
                                array_push($storePreOrdersCodeToBeCanceled, $storePreOrder->store_preorder_code);
                            }
                        }
                    }
                }

                if (count($storePreOrdersCodeToBeFinalized) > 0) {
                    $this->storePreOrderRepository->finalizeMassPreOrders($storePreOrdersCodeToBeFinalized);
                }
                if (count($storePreOrdersCodeToBeCanceled) > 0) {
                    $this->storePreOrderRepository->cancelMassPreOrders($storePreOrdersCodeToBeCanceled);
                }
            }

            DB::commit();

            return $warehousePreOrder;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function prepareStoreWalletTransactionDetails(StorePreOrder $storePreOrder,$totalPreOrderAmount){

        $store =$this->storeRepository->findOrFailStoreByCode($storePreOrder->store_code);
        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $store->getPreOrderWalletTransactionPurpose();
        $walletTransaction['amount'] = $totalPreOrderAmount;
        $walletTransaction['remarks'] = 'preorder balance deduct';
        $walletTransaction['transaction_purpose_reference_code'] = $storePreOrder->store_preorder_code;
        $walletTransaction['transaction_notification_details']=[
            'sms' => [
                'contact_no' =>$store->store_contact_mobile,
                'status' => $this->transactionNotificationConfiguration->getSMSSendStatus(),
                'message' => 'Your Current Account has been debited Rs.'.$totalPreOrderAmount.''
                        . ' for store preorder : '.$storePreOrder->store_preorder_code.' ('.$storePreOrder->warehousePreOrderListing->pre_order_name.') @ https://allpasal.com/'
            ]
        ];

        //event for wallet transaction and sms sending
        event(new StoreWalletTransactionEvent($walletTransaction));

    }

    public function cancelWarehousePreOrder($warehousePreOrderListingCode,$validatedData){
        try{
            $warehousePreOrder =$this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderListingCode,getAuthWarehouseCode());
            if ($warehousePreOrder->isFinalized()){
                throw new Exception('Cannot cancel:Pre-Order already finalized.');
            }

            if ($warehousePreOrder->isCancelled()){
                throw new Exception('Pre-Order already cancelled.');
            }
            //$warehousePreOrderListingsCode = (array)$warehousePreOrderListingCode;
            DB::beginTransaction();
            $storePreOrdersCodeToBeCanceled=[];

            $storePreOrderToBeCancelled = $this->storePreOrderRepository
                   ->getStorePreOrdersByWarehousePreOrderCodes(
                       [$warehousePreOrder->warehouse_preorder_listing_code]
                   );

            foreach($storePreOrderToBeCancelled as $storePreOrder){
                $statusLogData['remarks'] = 'Pre Order Date Cancelled : '.$validatedData['remarks'].'';
                $statusLogData['status'] = 'cancelled';
                $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder,$statusLogData);
                array_push($storePreOrdersCodeToBeCanceled,$storePreOrder->store_preorder_code);
            }

            if(count($storePreOrdersCodeToBeCanceled) > 0){
                $this->storePreOrderRepository->cancelMassPreOrders($storePreOrdersCodeToBeCanceled);
            }

            $this->warehousePreOrderRepository->cancelPreOrder($warehousePreOrder,$validatedData);
            DB::commit();

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function getWarehousesHavingPreOrder($filterParameters,$paginateBy)
    {
        return $this->warehousePreOrderRepository->getWarehousesHavingPreOrder($filterParameters,$paginateBy);
    }
    public function getPreOrdersInWarehouse($warehouseCode,$paginateBy)
    {
        return $this->warehousePreOrderRepository->getPreOrdersInWarehouse($warehouseCode,$paginateBy);
    }
    public function getProductsInPreOrder($filterParameters,$preOrderListingCode,$paginateBy)
    {
        return $this->warehousePreOrderRepository->getProductsInPreOrder($filterParameters,$preOrderListingCode, $paginateBy);
    }


    public function deleteWarehousePreOrder($warehousePreOrderCode)
    {
        try {
            $warehousePreOrder =$this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderCode, getAuthWarehouseCode());

            if ($warehousePreOrder->isPastFinalizationTime()){
                throw new Exception('Cannot delete after finalization time.');
            }

            if ($warehousePreOrder->hasBeenOrderedByStore()){
                throw new Exception('Cannot delete:has store orders.');
            }
            if ($warehousePreOrder->isPastStartTime()){
                throw new Exception('Cannot delete after start time.');
            }
            DB::beginTransaction();
            $this->warehousePreOrderProductRepository->massDeleteWarehousePreOrderProducts($warehousePreOrderCode);
            $this->warehousePreOrderRepository->delete($warehousePreOrder);
            DB::commit();
            return $warehousePreOrder;
        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }
    public function getWarehouseByCode($warehouseCode)
    {
        return $this->warehousePreOrderRepository->getWarehouseByCode($warehouseCode);
    }
    public function getPreOrderByPreOrderListingCode($preOrderListingCode)
    {
        return $this->warehousePreOrderRepository->getPreOrderByPreOrderListingCode($preOrderListingCode);
    }
    public function getVendorsList($preOrderListingCode)
    {
        return $this->warehousePreOrderRepository->getVendorsList($preOrderListingCode);
    }

    public function findStoreByCode($filterParameters)
    {
        return $this->warehousePreOrderRepository->findStoreByCode($filterParameters);
    }

    public function findPreorderByCode($filterParameters)
    {
        return $this->warehousePreOrderRepository->findPreorderByCode($filterParameters);
    }
    public function getPreOrderProducts($filterParameters)
    {
        return $this->warehousePreOrderRepository->getPreOrderProducts($filterParameters);
    }
    public function getPreOrderAmount($filterParameters)
    {
        return $this->warehousePreOrderRepository->getPreOrderAmount($filterParameters);
    }

    public function deletedProducts($filterParameters)
    {
        return $this->warehousePreOrderRepository->deletedProducts($filterParameters);
    }
    public function deactiveProducts($filterParameters)
    {
        return $this->warehousePreOrderRepository->deactiveProducts($filterParameters);
    }
    public function activeProducts($filterParameters)
    {
        return $this->warehousePreOrderRepository->activeProducts($filterParameters);
    }

    public function cloneWhPreOrderListing($validatedData){
        $whPreOrderListing = $this->findOrFailWarehousePreOrderByCode($validatedData['wh_preorder_listing_code']);

        $validatedData['warehouse_code'] = $whPreOrderListing->warehouse_code;
        $validatedData['banner_image'] = $whPreOrderListing->banner_image;
        $validatedData['is_active'] = 1;

        try {
            DB::beginTransaction();
            $warehousePreOrder = $this->warehousePreOrderRepository->create($validatedData);
            $this->warehousePreOrderProductRepository
                ->cloneProductsFromSourceToDestinationListingCode(
                    [
                        'source_listing_code' => $validatedData['wh_preorder_listing_code'],
                        'destination_listing_code' => $warehousePreOrder->warehouse_preorder_listing_code,
                        'created_by' => getAuthUserCode()
                    ]

                );

            DB::commit();
            return $warehousePreOrder;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


}
