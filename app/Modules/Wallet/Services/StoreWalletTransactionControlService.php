<?php


namespace App\Modules\Wallet\Services;


use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Events\StoreWalletTransactionEvent;
use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Helpers\WalletTransactionHelper;
use App\Modules\Wallet\Interfaces\TransactionConfigurationInterface;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Models\WalletTransaction;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use App\Modules\Wallet\Repositories\WalletRepository;
use App\Modules\Wallet\Repositories\WalletTransactionPurposeRepository;
use App\Modules\Wallet\Repositories\WalletTransactionRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;

class StoreWalletTransactionControlService implements TransactionConfigurationInterface
{
    use ImageService;
    private $walletRepository;
    private $walletTransactionRepository;
    private $storeRepository;
    private $walletTransactionPurposeRepository;
    private $transactionNotificationConfiguration;

    public function __construct(
        WalletRepository $walletRepository,
        WalletTransactionRepository $walletTransactionRepository,
        StoreRepository $storeRepository,
        WalletTransactionPurposeRepository $walletTransactionPurposeRepository,
        TransactionNotificationConfiguration $transactionNotificationConfiguration
    ){
       $this->walletRepository = $walletRepository;
       $this->walletTransactionRepository = $walletTransactionRepository;
       $this->storeRepository = $storeRepository;
       $this->walletTransactionPurposeRepository = $walletTransactionPurposeRepository;
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

    public function saveStoreWalletTransaction($validated){
        $this->transactionNotificationConfiguration->setSMSSendStatus(true);
        $this->saveStoreWalletTransactionControl($validated);
    }

    public function saveStoreWalletTransactionControl($validated){

        try{
            $action_type = $validated['action_type'];
            $validated['wallet_transaction_purpose_code'] = $validated['transaction_type'];
            $validated['amount'] = $validated['transaction_amount'];
            $validated['meta'] = NULL;
            $validated['transaction_purpose_reference_code'] = NULL;


            $walletTransactionPurpose = $this->walletTransactionPurposeRepository->findOrFailByTransactionPurposeCode($validated['wallet_transaction_purpose_code']);
            if(
                $walletTransactionPurpose->purpose_type != $action_type
                ||
                $walletTransactionPurpose->admin_control == 0
                ||
                $walletTransactionPurpose->is_active == 0
            ){
               throw new Exception('You are trying to corrupt data!');
            }
            $wallet = $this->walletRepository->findorFailByWalletCode($validated['wallet_code']);
            $store = $wallet->walletable;
            if($store->storeUserTypeCode() != $walletTransactionPurpose->user_type_code){
               throw new Exception('Store user type does not matched with purpose user Type!');
            }

            if(!$store->isApproved()){
                throw new Exception('Store Is Not Approved');
            }

            if(isset($validated['proof_of_document'])){
                $fileNameToStore = $this->storeImageInServer($validated['proof_of_document'], WalletTransaction::IMAGE_PATH);
                $validated['proof_of_document'] = $fileNameToStore;
            }else{
                $validated['proof_of_document'] = NULL;
            }

            if($action_type === 'increment'){
                   $this->saveStoreWalletTransactionDetailsIncrement($wallet,$walletTransactionPurpose,$validated);
            }else if($action_type === 'decrement'){
                   $this->saveStoreWalletTransactionDetailsDecrement($wallet,$walletTransactionPurpose,$validated);
            }else{
                throw new Exception( 'Action Type Not Found');
            }

        }catch (Exception $exception){
            throw $exception;
        }
    }

    private function saveStoreWalletTransactionDetailsIncrement(
        Wallet $wallet,
        WalletTransactionPurpose $walletTransactionPurpose,
        $validated
    ){
        try{

            if($walletTransactionPurpose->slug == 'refund-release'){
                $totalRefundReleaseAmount = WalletTransactionHelper::getStoreTotalRefundReleasableAmount($wallet->walletable);

                if($validated['amount'] > $totalRefundReleaseAmount){
                    throw new Exception('Refund Release amount cannot be greater than total refundable amount of Store');
                }
            }
            if($walletTransactionPurpose->slug=='sales-reconciliation-increment'){
                $validated['transaction_purpose_reference_code'] = $validated['order_code'];
                $validated['meta'] = json_encode([
                        'order_code' => $validated['order_code'],
                        'ref_bill_no' => $validated['ref_bill_no'],
                        'type'=>'normal_store_order'
                ]);
            }
            elseif($walletTransactionPurpose->slug=='pre-orders-sales-reconciliation-increment')
            {
                $validated['transaction_purpose_reference_code'] = $validated['order_code'];
                $validated['meta'] = json_encode([
                        'order_code' => $validated['order_code'],
                        'ref_bill_no' => $validated['ref_bill_no'],
                        'type'=> 'store_pre_order'
                ]);
            }elseif($walletTransactionPurpose->slug=='transaction-correction-increment'){
                $validated['transaction_purpose_reference_code'] = $validated['transaction_code'];
            }
            elseif($walletTransactionPurpose->slug=='cash-received'){
                $validated['meta'] =  json_encode([
                        'ref_bill_no' => $validated['ref_bill_no'],
                ]);
            }

            $validated['sms_message'] = "You current account has been credited with Rs. `{$validated['amount']}` due to {$walletTransactionPurpose->purpose} (`{$validated['transaction_purpose_reference_code']}`) @ https://allpasal.com/";

            $this->prepareWalletTransactionDetails($wallet,$walletTransactionPurpose,$validated);

        }catch (Exception $exception){
            throw $exception;
        }

    }

    private function saveStoreWalletTransactionDetailsDecrement(
        Wallet $wallet,
        WalletTransactionPurpose $walletTransactionPurpose,
        $validated
    ){
        try{

            if($walletTransactionPurpose->slug=='sales-reconciliation-deduction'){
                $validated['transaction_purpose_reference_code'] = $validated['order_code'];
                $validated['meta'] = json_encode([
                    'order_code' => $validated['order_code'],
                    'ref_bill_no' => $validated['ref_bill_no'],
                    'type'=>'normal_store_order'
                ]);
            }
            elseif($walletTransactionPurpose->slug=='pre-orders-sales-reconciliation-deduction')
            {
                $validated['transaction_purpose_reference_code'] = $validated['order_code'];
                $validated['meta'] = json_encode([
                    'order_code' => $validated['order_code'],
                    'ref_bill_no' => $validated['ref_bill_no'],
                    'type'=> 'store_pre_order'
                ]);
            }elseif($walletTransactionPurpose->slug=='transaction-correction-deduction'){
                $validated['transaction_purpose_reference_code'] = $validated['transaction_code'];
            }

            $validated['sms_message'] = "You current account has been debited with Rs. {$validated['amount']} due to {$walletTransactionPurpose->purpose} ({$validated['transaction_purpose_reference_code']}) @ https://allpasal.com/";

           $this->prepareWalletTransactionDetails($wallet,$walletTransactionPurpose,$validated);

        }catch (Exception $exception){
            throw $exception;
        }

    }

    private function prepareWalletTransactionDetails(
        Wallet $wallet,
        WalletTransactionPurpose $walletTransactionPurpose,
        $validated
       ){

        $store = $wallet->walletable;
        $walletTransaction['wallet'] = $wallet;
        $walletTransaction['wallet_transaction_purpose'] = $walletTransactionPurpose;
        $walletTransaction['amount'] =  $validated['amount'];
        $walletTransaction['remarks'] =  $validated['remarks'];
        $walletTransaction['meta'] =  isset($validated['meta']) ? $validated['meta'] : NULL;
        $walletTransaction['proof_of_document'] = isset($validated['proof_of_document']) ? $validated['proof_of_document'] : NULL;
        $walletTransaction['transaction_purpose_reference_code'] =  $validated['transaction_purpose_reference_code'];
        $walletTransaction['transaction_notification_details']=[
            'sms' => [
                'contact_no' =>$store->store_contact_mobile,
                'status' => $this->transactionNotificationConfiguration->getSMSSendStatus(),
                'message' => $validated['sms_message']
            ]
        ];

        event(new StoreWalletTransactionEvent($walletTransaction));
    }


}
