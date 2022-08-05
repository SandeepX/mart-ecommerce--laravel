<?php

namespace App\Modules\AlpasalWarehouse\Services\PreOrder;

use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Events\StoreWalletTransactionEvent;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderEarlyFinalizeRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderRepository;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderStatusLogRepository;
use App\Modules\Store\Repositories\StoreRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseStorePreOrderEarlyFinalizeService
{
    private $storePreOrderRepository;
    private $storePreOrderEarlyFinalizeRepository;
    private $storePreOrderStatusLogRepository;
    private $storeRepository;
    private $storeBalance;
    public function __construct(
        StorePreOrderRepository $storePreOrderRepository,
        StorePreOrderEarlyFinalizeRepository $storePreOrderEarlyFinalizeRepository,
        StorePreOrderStatusLogRepository $storePreOrderStatusLogRepository,
        StoreRepository $storeRepository,
        StoreBalance $storeBalance
    ){
        $this->storePreOrderRepository = $storePreOrderRepository;
        $this->storePreOrderEarlyFinalizeRepository = $storePreOrderEarlyFinalizeRepository;
        $this->storePreOrderStatusLogRepository = $storePreOrderStatusLogRepository;
        $this->storeRepository = $storeRepository;
        $this->storeBalance = $storeBalance;
    }

    public function createStorePreOrderEarlyFinalize($storePreOrderCode){
        try{
            $with = ['warehousePreOrderListing','store:store_code,store_name'];
            $storePreOrder = $this->storePreOrderRepository
                ->getStorePreOrderByPreOrderCode($storePreOrderCode,$with);

//            if($storePreOrder->warehousePreOrderListing->isPastFinalizationTime()){
//                throw new Exception('Early finalization cannot be done after the finalization time has ended!');
//            }
            if($storePreOrder->status != 'pending' || $storePreOrder->early_finalized){
                throw new Exception('Early finalization cannot be done. This Order is already in finalized state!');
            }

            return $storePreOrder;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveStorePreOrderEarlyFinalize($storePreOrderCode,$validatedData){
        try{
            $with = ['warehousePreOrderListing'];
            $storePreOrder = $this->storePreOrderRepository->getStorePreOrderByPreOrderCode($storePreOrderCode,$with);

//            if($storePreOrder->warehousePreOrderListing->isPastFinalizationTime()){
//                throw new Exception('Early finalization cannot be done after the finalization time has ended!');
//            }

            if($storePreOrder->status != 'pending'){
                throw new Exception('Early finalization cannot be done. This Order is already in finalized state!');
            }

            $dataToSave = [];
            $dataToSave['store_preorder_code'] = $storePreOrderCode;
            $dataToSave['early_finalization_date'] = Carbon::now()->toDateTimeString();
            $dataToSave['early_finalization_remarks'] = $validatedData['remarks'];
            $dataToSave['early_finalized_by'] = getAuthUserCode();

            DB::beginTransaction();


            $storePreOrdersCodeToBeFinalized=[];
            $storePreOrdersCodeToBeCanceled=[];

            if(!StorePreOrderHelper::isStorePreOrderFinalizableByReason
            ($storePreOrder->store_preorder_code,'non_deleted_preorder_details')){

                $statusLogData['remarks'] = 'Store Pre Order Cancelled because it contains all deleted pre order items';
                $statusLogData['status'] = 'cancelled';
                $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder,$statusLogData);
                array_push($storePreOrdersCodeToBeCanceled,$storePreOrder->store_preorder_code);
            }elseif(!StorePreOrderHelper::isStorePreOrderFinalizableByReason
            ($storePreOrder->store_preorder_code,'active_preorder_products')){
                $statusLogData['remarks'] = 'Store Pre Order Cancelled because it contains all inactive pre order items';
                $statusLogData['status'] = 'cancelled';
                $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder,$statusLogData);
                array_push($storePreOrdersCodeToBeCanceled,$storePreOrder->store_preorder_code);
            }else{

                $store =  $this->storeRepository->findOrFailStoreByCode($storePreOrder->store_code);
                $storeCurrentBalance= $this->storeBalance->getStoreWalletCurrentBalance($store);
                $totalPreOrderCost= StorePreOrderHelper::getTotalAmountOfStorePreOrder($storePreOrder->store_preorder_code);

                if ($totalPreOrderCost != 0 && $storeCurrentBalance >= $totalPreOrderCost){
                    /// for wallet transaction creation and current balance update starts here
                   $this->prepareStoreWalletTransactionDetails($storePreOrder,$totalPreOrderCost);
                    // ends here wallet transaction

                    $statusLogData['remarks'] = 'Store pre-ordered item finalized';
                    $statusLogData['status'] = 'finalized';
                    $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder,$statusLogData);
                    array_push($storePreOrdersCodeToBeFinalized,$storePreOrder->store_preorder_code);

                }else{
                    $statusLogData['remarks'] = 'Store pre-ordered item could not be finalized due to insufficient balance';
                    $statusLogData['status'] = 'cancelled';
                    $this->storePreOrderStatusLogRepository->saveStatusLog($storePreOrder,$statusLogData);
                    array_push($storePreOrdersCodeToBeCanceled,$storePreOrder->store_preorder_code);
                }
            }

            if(count($storePreOrdersCodeToBeFinalized) > 0){
                $this->storePreOrderRepository->finalizeMassPreOrders($storePreOrdersCodeToBeFinalized);
            }

            if(count($storePreOrdersCodeToBeCanceled) > 0){
                $this->storePreOrderRepository->cancelMassPreOrders($storePreOrdersCodeToBeCanceled);
            }

            $storePreOrderEarlyFinalize = $this->storePreOrderEarlyFinalizeRepository->saveEarlyFinalize($dataToSave);
            if($storePreOrderEarlyFinalize){
                $this->storePreOrderRepository->updateStorePreOrderForEarlyFianlized($storePreOrder);
            }

            DB::commit();
            return  $storePreOrderEarlyFinalize;
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
                'status' => true,
                'message' => 'Your Current Account has been debited Rs.'.$totalPreOrderAmount.''
                    . ' for store preorder : '.$storePreOrder->store_preorder_code.' ('.$storePreOrder->warehousePreOrderListing->pre_order_name.') @ https://allpasal.com/'
            ]
        ];
        //event for wallet transaction and sms sending
        event(new StoreWalletTransactionEvent($walletTransaction));
    }
}
