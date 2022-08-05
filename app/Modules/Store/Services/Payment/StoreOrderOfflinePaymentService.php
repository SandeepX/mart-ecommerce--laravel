<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 1:22 PM
 */

namespace App\Modules\Store\Services\Payment;


use App\Modules\Store\Models\Payments\StoreOrderOfflinePayment;
use App\Modules\Store\Repositories\Payment\StoreOrderOfflinePaymentRepository;

use App\Modules\Store\Repositories\StoreOrderRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreOrderOfflinePaymentService
{
    private $storeOrderOfflinePaymentRepo,$storeOrderRepository;
    
    public function __construct(StoreOrderOfflinePaymentRepository $offlinePaymentRepository,StoreOrderRepository $storeOrderRepository)
    {
        $this->storeOrderOfflinePaymentRepo= $offlinePaymentRepository;
        $this->storeOrderRepository = $storeOrderRepository;
    }

    public function getPaginatedStoreOrderPayments($storeCode){
        try{
            return $this->storeOrderOfflinePaymentRepo->getAllPaginatedByStoreCodeWith($storeCode,10,
                ['submittedBy','respondedBy','paymentDocuments','paymentMetaData']);

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function getPaymentsByStoreOrderCode($storeCode,$storeOrderCode){
        try{
            return $this->storeOrderOfflinePaymentRepo->getPaymentsByStoreOrderCode($storeCode,100,
                ['submittedBy','respondedBy','paymentDocuments','paymentMetaData'],$storeOrderCode);

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function findOrFailStoreOrderPaymentWithEager($storeOfflinePaymentCode,$storeCode){
        try{
            return $this->storeOrderOfflinePaymentRepo->findOrFailByCodeOfStore($storeOfflinePaymentCode,$storeCode,
                ['submittedBy','respondedBy','paymentDocuments','paymentMetaData']);

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function findOrFailStoreOrderPaymentByCodeWithEager($storeOfflinePaymentCode){
        try{
            return $this->storeOrderOfflinePaymentRepo->findOrFailByCode($storeOfflinePaymentCode,
                ['submittedBy','respondedBy','paymentDocuments','paymentMetaData']);

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function respondToStoreOrderPaymentByAdmin($validatedData,$storeOfflinePaymentCode){
        try{
            $storeOfflinePayment = $this->storeOrderOfflinePaymentRepo->findOrFailByCode($storeOfflinePaymentCode);
            $storeOrder = $this->storeOrderRepository->findOrFailByCode($storeOfflinePayment->store_order_code);

            if ($storeOfflinePayment->isVerified()){
                throw new Exception('Following store payment was already verified at '.$storeOfflinePayment->responded_at);
            }

            if ($storeOfflinePayment->isRejected()){
                throw new Exception('Following store payment was already rejected at '.$storeOfflinePayment->responded_at);
            }

            DB::beginTransaction();
            $validatedData['remarks']=$validatedData['remarks'] ? $validatedData['remarks']:null;
            $storeOfflinePayment=$this->storeOrderOfflinePaymentRepo->updatePaymentStatus($storeOfflinePayment,$validatedData);

            if ($storeOfflinePayment->payment_status == 'verified'){
                $this->storeOrderRepository->updatePaymentStatus($storeOrder,1);
            }

            DB::commit();
            return $storeOfflinePayment;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function savePayment($validatedData,$storeOrderCode){
        try{

            $authStoreCode = getAuthStoreCode();
            $storeOrder = $this->storeOrderRepository->findOrFailByStoreCode($storeOrderCode,$authStoreCode);


            if ($storeOrder->hasBeenPaid()){
                throw new Exception('Payment already done for the order');
            }

            if ($storeOrder->isPaymentPending()){
              throw new Exception('Payment for the order is already in processing');
            }

            // if($validatedData['amount'] != $storeOrder->total_price){
            //     throw new Exception('Please Make Payment Upto : '.$storeOrder->total_price);
            // }

            $validatedData['payment_status'] ='pending';
            $validatedData['store_code'] =$authStoreCode;
            $validatedData['store_order_code'] =$storeOrderCode;

            DB::beginTransaction();
            $storePayment = $this->storeOrderOfflinePaymentRepo->save($validatedData);

            $this->saveStoreOrderPaymentDocuments($storePayment,$validatedData['document_images'],
               $validatedData['document_types']);

            //meta details
            if ($validatedData['payment_type'] == 'cash'){
                $this->saveStoreOrderCashPaymentMetaDetails($storePayment,$validatedData);
            }
            elseif($validatedData['payment_type'] == 'cheque'){
                $this->saveStoreOrderChequePaymentMetaDetails($storePayment,$validatedData);
            }
            elseif($validatedData['payment_type'] == 'remit'){
               $this->saveStoreOrderRemitPaymentMetaDetails($storePayment,$validatedData);
            }
            elseif($validatedData['payment_type'] == 'wallet'){
                $this->saveStoreOrderWalletPaymentMetaDetails($storePayment,$validatedData);
            }

            DB::commit();

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function saveStoreOrderCashPaymentMetaDetails(StoreOrderOfflinePayment $storeOrderOfflinePayment,
                                                                  $validatedData){

        $data=[
            [
                'key' => 'bank_code',
                'value' => $validatedData['bank_code']
            ],
            [
                'key' => 'bank_name',
                'value' => $validatedData['bank_name']
            ],
            [
                'key' => 'branch_name',
                'value' => $validatedData['branch_name']
            ],
        ];

        $this->storeOrderOfflinePaymentRepo->savePaymentMetaDetail($storeOrderOfflinePayment,$data);
    }
    private function saveStoreOrderChequePaymentMetaDetails(StoreOrderOfflinePayment $storeOrderOfflinePayment,
                                                                    $validatedData){

        $data=[
            [
                'key' => 'deposit_bank_name',
                'value' => $validatedData['deposit_bank_name']
            ],
            [
                'key' => 'cheque_bank',
                'value' => $validatedData['cheque_bank']
            ],
            [
                'key' => 'cheque_holder_name',
                'value' => $validatedData['cheque_holder_name']
            ],
            [
                'key' => 'cheque_account_number',
                'value' => $validatedData['cheque_account_number']
            ],
            [
                'key' => 'cheque_number',
                'value' => $validatedData['cheque_number']
            ],
        ];

        $this->storeOrderOfflinePaymentRepo->savePaymentMetaDetail($storeOrderOfflinePayment,$data);
    }

    private function saveStoreOrderRemitPaymentMetaDetails(StoreOrderOfflinePayment $storeOrderOfflinePayment,
                                                                   $validatedData){

        $data=[
            [
                'key' => 'remit_name',
                'value' => $validatedData['remit_name']
            ],
            [
                'key' => 'branch_name',
                'value' => $validatedData['branch_name']
            ],
        ];

        $this->storeOrderOfflinePaymentRepo->savePaymentMetaDetail($storeOrderOfflinePayment,$data);
    }

    private function saveStoreOrderWalletPaymentMetaDetails(StoreOrderOfflinePayment $storeOrderOfflinePayment,
                                                                    $validatedData){

        $data=[
            [
                'key' => 'payment_partner',
                'value' => $validatedData['payment_partner']
            ],
        ];

        $this->storeOrderOfflinePaymentRepo->savePaymentMetaDetail($storeOrderOfflinePayment,$data);
    }

    private function saveStoreOrderPaymentDocuments(StoreOrderOfflinePayment $storeOrderOfflinePayment,$documents,$documentTypes){

        foreach ($documents as $i=>$document){
            $this->storeOrderOfflinePaymentRepo->savePaymentDocument($storeOrderOfflinePayment,$document,$documentTypes[$i]);
        }
    }
}