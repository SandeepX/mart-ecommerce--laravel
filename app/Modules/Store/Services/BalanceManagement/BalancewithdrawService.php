<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 10/22/2020
 * Time: 1:41 PM
 */

namespace App\Modules\Store\Services\BalanceManagement;

use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Events\StoreWalletTransactionEvent;
use App\Modules\Store\Events\WithdrawRequestCompletedEvent;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequestVerificationDetail;
use App\Modules\Store\Repositories\BalanceManagement\StoreBalanceManagementRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Store\Repositories\Withdraw\StoreBalanceFreezeRepository;
use App\Modules\Store\Repositories\Withdraw\WithdrawRequestRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Interfaces\TransactionConfigurationInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;


class BalancewithdrawService implements TransactionConfigurationInterface
{
    private $storeBalanceManagementRepository,$balanceFreezeRepository,$withdrawRequestRepository;
    private $storeRepository;
    private $transactionNotificationConfiguration;

    public function __construct(StoreBalanceManagementRepository $storeBalanceMgmtRepository,
                                StoreBalanceFreezeRepository $balanceFreezeRepository,
                                WithdrawRequestRepository $withdrawRequestRepository,
                                StoreRepository $storeRepository,
                                TransactionNotificationConfiguration $transactionNotificationConfiguration
    )
    {
        $this->storeBalanceManagementRepository = $storeBalanceMgmtRepository;
        $this->balanceFreezeRepository = $balanceFreezeRepository;
        $this->withdrawRequestRepository = $withdrawRequestRepository;
        $this->storeRepository = $storeRepository;
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

    public function getAllwithdrawRequest($storeCode)
    {
        return $this->storeBalanceManagementRepository->getAllwithdrawRequest($storeCode);
    }
    public function getwithdrawRequestByStoreCode($withdraw_request_code)
    {
        return $this->storeBalanceManagementRepository->getAllwithdrawRequestBystoreCode($withdraw_request_code);
    }




    public function changeWithdrawRequestStatus($validated,$withdraw_request_code){
        $this->setSMSSendStatus(true);
        return $this->respondToWithdrawRequest($validated,$withdraw_request_code);
    }

    public function respondToWithdrawRequest($validated, $withdraw_request_code)
    {
        try {

            $status = $validated['status'];

            if ($status == 'pending') {
                throw new Exception(' status must not be pending');
            }
            $withdrawRequest=$this->withdrawRequestRepository->getWithdrawRequestByCode($withdraw_request_code);
            if(!$withdrawRequest){
                throw new Exception('Withdraw Request Not found',404);
            }
            $withdrawRequestVerificationDetail=$this->withdrawRequestRepository->getWithdrawRequestVerificationDetail($withdraw_request_code);

            $cannotRespondIfStatusExist=["completed","rejected"];
            $existingStatus=$withdrawRequest->status;
            if(in_array($existingStatus,$cannotRespondIfStatusExist))
            {
                throw new Exception('Withdraw request status cannot be changed once the status is already '.$withdrawRequest->status);
            }

            DB::beginTransaction();

            if($status=="processing")
            {
                $this->withdrawRequestRepository->updateWithdrawRequestStatus($withdrawRequest,$validated);
            }
            elseif($status=="rejected")
            {
                if(isset($withdrawRequestVerificationDetail) && $withdrawRequestVerificationDetail->count())
                {
                    throw new Exception('This request can not be rejected after the verification details have been added');
                }
                $this->withdrawRequestRepository->updateWithdrawRequestStatus($withdrawRequest,$validated);
            }
            elseif($status=="completed")
            {
                if($withdrawRequest->status=="pending")
                {
                    throw new Exception('withdraw request can not be completed from pending. It must be in processing status first ');
                }
                $pendingAmount=$this->getPendingAmount($withdrawRequest);
                if(isset($withdrawRequestVerificationDetail) && $pendingAmount==0)
                {
                    $this->prepareWalletTransactionDetails($withdrawRequest);
                    $this->withdrawRequestRepository->updateWithdrawRequestStatus( $withdrawRequest, $validated);

                }
                else{
                    throw new Exception('this action can not be done because verification detail should be added or pending amount should be zero');
                }
            }

            DB::commit();

            return $withdrawRequest;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }
    public function getWithdrawRequestMadeByStore($storeCode,$paginatedBy)
    {
        return $this->storeBalanceManagementRepository->getAllwithdrawRequestMadeByStore($storeCode,$paginatedBy);
    }


    public function storeVerificationDetail($validated,$withdrawRequestCode,$withdrawrequestdetail)
    {
        try {
            $sum=0;
            foreach($validated as $key=>$value)
            {
                $sum += $value['amount'];
            }

           $existingAmount=$withdrawrequestdetail->verificationDetails->where('status','passed')->sum('amount');
            $incomingAmount=$existingAmount + $sum;
             if($withdrawrequestdetail->requested_amount < $incomingAmount)
             {
                 throw new Exception('Amount exceeding the withdraw requested amount');
             }
            DB::beginTransaction();

            foreach($validated as $key=>$value)
            {
                $verificationDetail=$this->storeBalanceManagementRepository->storeVerificationDetail($value,$withdrawrequestdetail);
            }
            DB::commit();
            return $verificationDetail;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function getPendingAmount($withdrawrequestdetail)
    {
        return $this->storeBalanceManagementRepository->getPendingAmount($withdrawrequestdetail);
    }
    public function changeVerificationDetailStatus($withdrawRequestVerificationDetailCode)
    {
        $withdrawRequestVerificationDetail=$this->withdrawRequestRepository->getVerificationDetailByCode($withdrawRequestVerificationDetailCode);
        $withdrawRequestCode=$withdrawRequestVerificationDetail->withdrawRequest->store_balance_withdraw_request_code;
        $requestedAmount=$withdrawRequestVerificationDetail->withdrawRequest->requested_amount;
        $existingAmount=$this->withdrawRequestRepository->getWithdrawVerificationDetailsByWithdrawRequestCode($withdrawRequestCode,$withdrawRequestVerificationDetailCode);
        $incomingAmount=$withdrawRequestVerificationDetail->amount;
        if($requestedAmount < ($existingAmount + $incomingAmount))
        {
            throw new Exception('Cannot update the status ! if done , this will exceed the request amount');
        }
        return $this->withdrawRequestRepository->changeVerificationDetailStatus($withdrawRequestVerificationDetail);
    }

    private function prepareWalletTransactionDetails($withdrawRequest){

        $store =$this->storeRepository->findOrFailStoreByCode($withdrawRequest['store_code']);
        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $withdrawRequest->getWalletTransactionPurpose();
        $walletTransaction['amount'] = roundPrice($withdrawRequest->requested_amount);
        $walletTransaction['remarks'] = $withdrawRequest->remarks;
        $walletTransaction['transaction_purpose_reference_code'] = $withdrawRequest->store_balance_withdraw_request_code;
        $walletTransaction['transaction_notification_details']=[
           'sms' => [
               'contact_no' =>$store->store_contact_mobile,
               'status' => $this->transactionNotificationConfiguration->getSMSSendStatus(),
               'message' => "You current account has been debited with
                             Rs. `{$walletTransaction['amount']}`
                             due to withdraw (`{$walletTransaction['transaction_purpose_reference_code']}`)
                             @ https://allpasal.com/'
                             "
           ]
        ];



        //event for wallet transaction and sms sending
        event(new StoreWalletTransactionEvent($walletTransaction));

    }



}




