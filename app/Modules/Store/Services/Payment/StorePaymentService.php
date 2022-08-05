<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 1:41 PM
 */

namespace App\Modules\Store\Services\Payment;

use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\PaymentMedium\Models\DigitalWallet;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Events\LoadBalanceCompletedEvent;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Repositories\Payment\StoreMiscellaneousPaymentRepository;
use App\Modules\Store\Repositories\StoreBalanceReconciliation\BalanceReconciliationUsageRepository;
use App\Modules\Store\Repositories\StoreBalanceReconciliation\StoreBalanceReconciliationRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Services\WalletTransactionService;
use Exception;
use Illuminate\Support\Facades\DB;


class StorePaymentService
{
    private $storeMiscellaneousPaymentRepo;
    private $balanceReconciliationRepo;
    private $balanceReconciliationUsageRepo;
    private $storeDetailRepo;
    private $transactionNotificationConfiguration;
    private $storeBalance;
    private $walletTransactionService;
    private $salesManagerService;



    public function __construct(
        StoreMiscellaneousPaymentRepository $storeMiscellaneousPaymentRepository,
        StoreBalanceReconciliationRepository $balanceReconciliationRepo,
        BalanceReconciliationUsageRepository $balanceReconciliationUsageRepo,
        StoreRepository $storeDetailRepo,
        TransactionNotificationConfiguration $transactionNotificationConfiguration,
        StoreBalance $storeBalance,
        WalletTransactionService $walletTransactionService,
        SalesManagerService $salesManagerService
    ){
        $this->storeMiscellaneousPaymentRepo = $storeMiscellaneousPaymentRepository;
        $this->balanceReconciliationRepo = $balanceReconciliationRepo;
        $this->balanceReconciliationUsageRepo = $balanceReconciliationUsageRepo;
        $this->storeDetailRepo = $storeDetailRepo;
        $this->transactionNotificationConfiguration = $transactionNotificationConfiguration;
        $this->storeBalance = $storeBalance;
        $this->walletTransactionService = $walletTransactionService;
        $this->salesManagerService = $salesManagerService;
    }


    public function getStoreMiscellaneousPayments($storeCode)
    {
        try {
            return $this->storeMiscellaneousPaymentRepo->getAllByStoreCodeWith($storeCode,
                ['submittedBy', 'respondedBy', 'paymentDocuments', 'paymentMetaData']);

        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public function getAllMiscPaymentsByStoreCodeAndPaymentType($storeCode, $payment_for)
    {
        try {
            return $this->storeMiscellaneousPaymentRepo->getAllMiscPaymentsByStoreCodeAndPaymentType($storeCode, $payment_for,
                ['submittedBy', 'respondedBy', 'paymentDocuments', 'paymentMetaData']);

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function findOrFailStoreMiscellaneousPaymentWithEager($miscPaymentCode, $storeCode)
    {
        try {
            return $this->storeMiscellaneousPaymentRepo->findOrFailByCodeOfStore($miscPaymentCode, $storeCode,
                ['submittedBy', 'respondedBy', 'paymentDocuments', 'paymentMetaData']);

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function findOrFailStoreMiscellaneousPaymentByCodeWithEager($miscPaymentCode)
    {
        try {
            return $this->storeMiscellaneousPaymentRepo->findOrFailByCode($miscPaymentCode,
                ['submittedBy', 'respondedBy', 'paymentDocuments', 'paymentMetaData']);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function respondToStoreMiscPaymentByAdmin($validatedData,$miscPaymentCode)
    {
        $this->transactionNotificationConfiguration->setSMSSendStatus(true);
        $validatedData['sms'] = $this->transactionNotificationConfiguration->getSMSSendStatus();
        return $this->updateStatusOfStoreMiscPayment($validatedData,$miscPaymentCode);
    }

    /****sandeep start from here*****/

    public function updateStatusOfStoreMiscPayment($validatedData,$miscPaymentCode){
        try{
            $storeMiscPayment = $this->storeMiscellaneousPaymentRepo->findOrFailByCode($miscPaymentCode);
            if(!in_array($storeMiscPayment->payment_for,['load_balance','initial_registration'])){
                throw new Exception('Only load Balance Can be handled from here. For others payment visit offline payments');
            }
            $store = $this->storeDetailRepo->findStoreByCode($storeMiscPayment['store_code']);

//            if($storeMiscPayment->payment_for != 'load_balance'){
//                throw new Exception('Only load Balance Can be handled from here. For others payment visit offline payments');
//            }
            if (!$storeMiscPayment->isPending()){
                throw new Exception(
                    'This '.convertToWords($storeMiscPayment->payment_for,'_').' payment status : (Code : '.$storeMiscPayment->store_misc_payment_code.') cannot be changed at the moment !
                    Since , this payment has been already '.$storeMiscPayment->verification_status.'
            ',403);
            }
            DB::beginTransaction();
            $validatedData['remarks']= isset($validatedData['remarks']) ? $validatedData['remarks']:null;
            $miscStorePaymentData = $this->storeMiscellaneousPaymentRepo->updateVerificationStatus($storeMiscPayment,$validatedData);
            if ($storeMiscPayment->isVerified()){
                event(new LoadBalanceCompletedEvent($store,$miscStorePaymentData,$validatedData));
            }
            DB::commit();
            return $storeMiscPayment;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
    public function handleBrCodeAfterBalanceLoad($balanceReconciliationCode, $storeMiscPayment)
    {

        //dd($balanceReconciliationCode,$storeMiscPayment);
        $balanceReconciliation = $this->balanceReconciliationRepo->getBalanceReconciliationForAdminVerfication($balanceReconciliationCode);
        if (is_null($balanceReconciliation)) {
            throw new Exception(
                'please wait the transaction record not found in balance reconciliation table
         or transation with this id already used'
            );
        }
        /**This updates the status of balance transaction detail to used using upcoming BRCode */
        $this->balanceReconciliationRepo->updateOnlyStatusWhenMiscPaymentVerified($balanceReconciliation);
        /**********Store In Balance Reconciliaiton Usage*********/
        $balanceReconciliationUsageData['balance_reconciliation_code'] = $balanceReconciliationCode;
        $balanceReconciliationUsageData['used_for_code'] = $storeMiscPayment->store_misc_payment_code;
        $balanceReconciliationUsageData['used_for'] = $storeMiscPayment->payment_for;
        $balanceReconciliationUsageData['created_by'] = getAuthUserCode();
        $this->balanceReconciliationUsageRepo->storeBalanceReconiliationUsage($balanceReconciliationUsageData);
    }

    public function storeStautusUpdateOnLoadBalance(Store $store, $payingAmount,$smsSendStatus = false)
    {
        $transactionPurposes = [];
        $storeTypePackage = $store->storeTypePackage;
        if ($storeTypePackage) {

            $storeNonRefundableRegCharge = $storeTypePackage->non_refundable_registration_charge;
            $storeRefundableRegCharge = $storeTypePackage->refundable_registration_charge;
            $storeBaseInvestmentCharge = $storeTypePackage->base_investment;
            $referralRegistrationIncentiveAmount = $storeTypePackage->referal_registration_incentive_amount;

            if ($store->status == "approved") {
                if ($store->has_purchase_power == 0 && ($payingAmount >= $storeBaseInvestmentCharge)){

//                    if($store->referredBy && $store->referredBy->isSalesManagerUser() && $referralRegistrationIncentiveAmount>0){
//                        $this->salesManagerService->prepareWalletTransactionForSalesManagerStoreReferralCommission(
//                                $store->referredBy,
//                                $store,
//                                $smsSendStatus
//                            );
//                    }

                    $this->storeDetailRepo->enablePurchasingPower($store);
                }
            }

            if ($store->status === "processing") {

                /*-------------  NON REFUNDABLE REGISTRATION CHARGE --------------------*/
                if ($storeNonRefundableRegCharge > 0) {
                    $nonRefundableChargePaidByStore = $this->storeBalance->getNonRefundableRegistrationChargeDeducted($store);
                    if (
                        $payingAmount >= $storeNonRefundableRegCharge
                        && ($nonRefundableChargePaidByStore < $storeNonRefundableRegCharge)
                    ) {
                        /*------- Recording loaded balance in wallet Transaction ---------------*/
                        $walletTransactionForNonRefundable = $this->prepareWalletTransactionForNonRefundableRegistrationCharge($store, $storeNonRefundableRegCharge);
                        $transactionPurposes['non_refundable'] = $walletTransactionForNonRefundable->amount;
                        $payingAmount = roundPrice($payingAmount - $storeNonRefundableRegCharge);
                        /* ---------------------------- ends here ------------------------------*/
                    }
                }

                /*-------------  REFUNDABLE REGISTRATION CHARGE --------------------*/


                if ($storeRefundableRegCharge > 0) {
                    $refundableChargePaidByStore = $this->storeBalance->getRefundableRegistrationChargeDeducted($store);
                    if (
                        $payingAmount >= $storeRefundableRegCharge
                        && ($refundableChargePaidByStore < $storeRefundableRegCharge)
                    ) {
                        /*------- Recording loaded balance in wallet Transaction ---------------*/
                        $walletTransactionForRefundable = $this->prepareWalletTransactionForRefundable($store, $storeRefundableRegCharge);
                        $transactionPurposes['refundable'] = $walletTransactionForRefundable->amount;
                        $payingAmount = roundPrice($payingAmount - $storeRefundableRegCharge);

                        /* ---------------------------- ends here ------------------------------*/
                    }
                }

                $nonRefundableChargePaidByStore = $this->storeBalance->getNonRefundableRegistrationChargeDeducted($store);
                $refundableChargePaidByStore = $this->storeBalance->getRefundableRegistrationChargeDeducted($store);

                if (
                    $nonRefundableChargePaidByStore >= $storeNonRefundableRegCharge
                    && $refundableChargePaidByStore >= $storeRefundableRegCharge
                ) {

                    $store = $this->storeDetailRepo->changeStoreStatusToApproved($store);
                }

                if ($store->has_purchase_power == 0
                    && $store->status == "approved"
                    && $payingAmount >= $storeBaseInvestmentCharge
                ) {

//                    if($store->referredBy && $store->referredBy->isSalesManagerUser() && $referralRegistrationIncentiveAmount>0){
//                       $this->salesManagerService->prepareWalletTransactionForSalesManagerStoreReferralCommission(
//                           $store->referredBy,
//                           $store,
//                           $smsSendStatus
//                       );
//                    }
                    $this->storeDetailRepo->enablePurchasingPower($store);
                }

            }
        }
        return $transactionPurposes;
    }

    public function prepareWalletTransactionForLoadBalance(
        Store $store,
        OfflinePaymentMaster $miscStorePaymentData,
        $validatedData
    )
    {
        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $store->getWalletTransactionPurposeForLoadBalance();
        $walletTransaction['amount'] = roundPrice($miscStorePaymentData['amount']);
        $walletTransaction['remarks'] = $validatedData['remarks'];
        $walletTransaction['transaction_purpose_reference_code'] = $miscStorePaymentData->store_misc_payment_code;
        $walletTransaction['transaction_notification_details'] = [
            'sms' => [
                'contact_no' => $store->store_contact_mobile,
                'status' =>   $this->transactionNotificationConfiguration->getSMSSendStatus()
            ]
        ];

        $walletTransactionDetails = $this->walletTransactionService->createWalletTransaction($walletTransaction);
        $walletTransactionDetails['transaction_notification_details'] = $walletTransaction['transaction_notification_details'];
        $walletTransactionDetails['wallet_transaction_purpose'] = $store->getWalletTransactionPurposeForLoadBalance();

        return $walletTransactionDetails;
    }

    private function prepareWalletTransactionForRefundable(Store $store, $storeRefundableRegCharge)
    {

        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $store->getWalletTransactionPurposeForRefundable();
        $walletTransaction['amount'] = roundPrice($storeRefundableRegCharge);
        $walletTransaction['remarks'] = 'Balance Deducted for refundable Registration';
        $walletTransaction['transaction_purpose_reference_code'] = NULL;
        $walletTransaction['transaction_notification_details'] = [
            'sms' => [
                'contact_no' => $store->store_contact_mobile,
                'status' => $this->transactionNotificationConfiguration->getSMSSendStatus(),
                'message' => "You current account has been debited with
                             Rs. {$walletTransaction['amount']}`
                             due to  Refundable Registration Charge
                             @ https://allpasal.com/'
                             "
            ]
        ];
        return $this->walletTransactionService->createWalletTransaction($walletTransaction);
    }

    private function prepareWalletTransactionForNonRefundableRegistrationCharge(Store $store, $storeNonRefundableRegCharge)
    {

        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $store->getWalletTransactionPurposeForNonRefundableRegistrationCharge();
        $walletTransaction['amount'] = roundPrice($storeNonRefundableRegCharge);
        $walletTransaction['remarks'] = 'Balance Deducted for Non Refundable Registration Charge';;
        $walletTransaction['transaction_purpose_reference_code'] = NULL;
        $walletTransaction['transaction_notification_details'] = [
            'sms' => [
                'contact_no' => $store->store_contact_mobile,
                'status' => $this->transactionNotificationConfiguration->getSMSSendStatus(),
                'message' => "You current account has been debited with
                             Rs. {$walletTransaction['amount']}`
                             due to Non Rufundable Registration Charge
                             @ https://allpasal.com/'
                             "
            ]
        ];

        return $this->walletTransactionService->createWalletTransaction($walletTransaction);
    }

    public function saveLoadBalanceDetail($storeBalanceMasterLoadBalanceDetails){
        return  $this->storeMiscellaneousPaymentRepo->saveLoadBalanceDetail($storeBalanceMasterLoadBalanceDetails);
    }

    public function saveTransactionData($loadBalanceTransactionData){
        return $this->storeMiscellaneousPaymentRepo->saveTransaction($loadBalanceTransactionData);
    }

    public function createStoreMiscellaneousPayment($paymentData){
        return $this->storeMiscellaneousPaymentRepo->save($paymentData);
    }



    public function saveOfflinePayment($validatedData)
    {
        try {
            $validatedData['verification_status'] = 'pending';
            $validatedData['store_code'] = getAuthStoreCode();

            DB::beginTransaction();

            $miscData['verification_status'] = 'pending';
            $miscData['payment_for'] = $validatedData['payment_for'];
            $miscData['payment_type'] = $validatedData['payment_type'];
            $miscData['store_code'] = getAuthStoreCode();

            $lastestStoreVerificationStatus = $this->storeMiscellaneousPaymentRepo->getLatestMiscPaymentVerificationStatus($miscData);
            if (!empty($lastestStoreVerificationStatus)) {
                $miscPaymentDetail = [];
                $miscPaymentDetail['deposited_by'] = $lastestStoreVerificationStatus->deposited_by;
                $miscPaymentDetail['transaction_date'] = $lastestStoreVerificationStatus->transaction_date;
                $miscPaymentDetail['amount'] = $lastestStoreVerificationStatus->amount;
                $miscPaymentDetail['contact_phone_no'] = $lastestStoreVerificationStatus->contact_phone_no;

                /*payment Detail*/
                $metaDetails = [];
                if (!is_null($lastestStoreVerificationStatus)) {
                    $storeMiscPaymentCode = $lastestStoreVerificationStatus->store_misc_payment_code;
                    $storeMiscellaneousPaymentsMetaData = $this->storeMiscellaneousPaymentRepo->getPaymentDetail($storeMiscPaymentCode);

                    foreach ($storeMiscellaneousPaymentsMetaData as $metadata) {
                        if($metadata->key != 'transaction_number'){
                            $metaDetails[$metadata->key] = $metadata->value;
                        }
                    }
                } else {
                    $metaDetails = [];
                }

                if (!empty($metaDetails)) {

                    $paymentDetailFront = [];

                    if ($validatedData['payment_type'] == 'cheque') {
                        $paymentDetailFront['deposit_bank_name'] = $validatedData['deposit_bank_name'];
                        $paymentDetailFront['deposited_branch_name'] = $validatedData['deposited_branch_name'];
                        $paymentDetailFront['cheque_bank'] = $validatedData['cheque_bank'];
                        $paymentDetailFront['cheque_bank_code'] = $validatedData['cheque_bank_code'];
                        $paymentDetailFront['cheque_holder_name'] = $validatedData['cheque_holder_name'];
                        $paymentDetailFront['cheque_account_number'] = $validatedData['cheque_account_number'];
                        $paymentDetailFront['cheque_number'] = $validatedData['cheque_number'];
                        $paymentDetailFront['bank_code'] = $validatedData['bank_code'];
                    }

                    if ($validatedData['payment_type'] == 'cash') {
                        $paymentDetailFront['bank_name'] = $validatedData['bank_name'];
                        $paymentDetailFront['bank_code'] = $validatedData['bank_code'];
                        $paymentDetailFront['branch_name'] = $validatedData['branch_name'];
                    }

                    if ($validatedData['payment_type'] == 'remit') {
                        $paymentDetailFront['remit_name'] = $validatedData['remit_name'];
                        $paymentDetailFront['remit_branch_name'] = $validatedData['remit_branch_name'];
                        $paymentDetailFront['remit_code'] = $validatedData['remit_code'];
                        $paymentDetailFront['bank_name'] = $validatedData['bank_name'];
                        $paymentDetailFront['bank_code'] = $validatedData['bank_code'];
                        $paymentDetailFront['receiver_name'] = $validatedData['receiver_name'];
                        $paymentDetailFront['receiver_bank_account_name'] = $validatedData['receiver_bank_account_name'];
                    }

                    if ($validatedData['payment_type'] == 'wallet') {
                        $paymentDetailFront['payment_partner'] = $validatedData['payment_partner'];
                        $paymentDetailFront['wallet_code'] = $validatedData['wallet_code'];

                        if($paymentDetailFront['wallet_code'] == (new DigitalWallet())->getConnectIpsCode()){
                            $paymentDetailFront['bank_code'] = $validatedData['bank_code'];
                            $paymentDetailFront['bank_name'] = $validatedData['bank_name'];
                            $paymentDetailFront['branch_name'] = $validatedData['branch_name'];
                            $paymentDetailFront['account_number'] = $validatedData['account_number'];
                            $paymentDetailFront['account_holder_name'] = $validatedData['account_holder_name'];
                            $paymentDetailFront['remark'] = $validatedData['remark'];
                            $paymentDetailFront['sender_bank_code'] = $validatedData['sender_bank_code'];
                            $paymentDetailFront['sender_bank_name'] = $validatedData['sender_bank_name'];
                            $paymentDetailFront['sender_branch_name'] = $validatedData['sender_branch_name'];
                            $paymentDetailFront['sender_account_number'] = $validatedData['sender_account_number'];
                        }else{
                            $paymentDetailFront['receiver_name'] = $validatedData['receiver_name'];
                            $paymentDetailFront['receiver_id'] = $validatedData['receiver_id'];
                        }
                    }


                    if($validatedData['payment_type'] == 'mobile_banking'){
                        $paymentDetailFront['bank_code'] = $validatedData['bank_code'];
                        $paymentDetailFront['bank_name'] = $validatedData['bank_name'];
                        $paymentDetailFront['account_number'] = $validatedData['account_number'];
                        $paymentDetailFront['account_holder_name'] = $validatedData['account_holder_name'];
                        $paymentDetailFront['remark'] = $validatedData['remark'];
                        $paymentDetailFront['sender_bank_code'] = $validatedData['sender_bank_code'];
                        $paymentDetailFront['sender_bank_name'] = $validatedData['sender_bank_name'];
                        $paymentDetailFront['sender_account_number'] = $validatedData['sender_account_number'];
                    }

                    $diffInPaymentDetailWhileLoadBalance = array_diff($metaDetails, $paymentDetailFront);

                    if (empty($diffInPaymentDetailWhileLoadBalance)) {
                        $miscDataFront = [];
                        $miscDataFront['deposited_by'] = $validatedData['deposited_by'];
                        $miscDataFront['transaction_date'] = $validatedData['transaction_date'];
                        $miscDataFront['amount'] = $validatedData['amount'];
                        $miscDataFront['contact_phone_no'] = $validatedData['contact_phone_no'];

                        $diffInMiscPaymentDetailWhileLoadBalance = array_diff($miscPaymentDetail, $miscDataFront);

                        if (empty($diffInMiscPaymentDetailWhileLoadBalance)) {
                            throw new Exception('Already Submitted a same Data , Please wait for verification or contact support center.', 403);
                        }
                    }
                }
            }
            /*** load balance front end validation using parameter:trasaction_date,payment_body_code,transaction_type,status and amount before saving detail in database to avoid false data in database.  ****/

            if ($validatedData['payment_type'] == 'remit') {
                $paymentBodyCode = $validatedData['remit_code'];
            }
            if ($validatedData['payment_type'] == 'wallet') {
                $paymentBodyCode = $validatedData['wallet_code'];
            }
            if ($validatedData['payment_type'] == 'cash' || $validatedData['payment_type'] == 'cheque') {
                $paymentBodyCode = $validatedData['bank_code'];
            }
            if ($validatedData['payment_type'] == 'mobile_banking') {
                $paymentBodyCode = $validatedData['bank_code'];
            }
            if ($validatedData['payment_type'] == 'cheque') {
                $chequeNo = $validatedData['cheque_number'];
            } else {
                $chequeNo = '';
            }

            if (empty($paymentBodyCode)) {
                throw new Exception(
                    'cannot find payment body code of this payment.'
                    , 404);
            }

            $balanceReconcilationData['payment_body_code'] = $paymentBodyCode;
            $balanceReconcilationData['transaction_amount'] = $validatedData['amount'];
            $balanceReconcilationData['transaction_date'] = $validatedData['transaction_date'];
            $balanceReconcilationData['transacted_by'] = $validatedData['deposited_by'];
            $balanceReconcilationData['transaction_numbers'] = isset($validatedData['transaction_number']) ? $validatedData['transaction_number'] : [];
            $balanceReconcilationData['cheque_no'] = $chequeNo;
            $balanceReconcilationData['transaction_type'] = 'deposit';
            $balanceReconcilationData['contact_phone_no'] = $validatedData['contact_phone_no'];
            $balanceReconcilationData['remark'] = isset($validatedData['remark']) ? $validatedData['remark'] : NULL;

            $loadBalanceReconcileDetail = $this->balanceReconciliationRepo->getBalanceReconciliationForVerification($balanceReconcilationData);
           // dd($validatedData);
           // dd($loadBalanceReconcileDetail);
            if (count($loadBalanceReconcileDetail) > 0) {
                $validatedData['has_matched'] = 1;
            }

            $validatedData['user_code'] = getAuthUserCode();

//            dd($validatedData);
            $storePayment = $this->createStoreMiscellaneousPayment($validatedData);

            $this->saveStoreMiscellaneousPaymentDocuments($storePayment, $validatedData['document_images'],
                $validatedData['document_types']);

            //meta details
            if ($validatedData['payment_type'] == 'cash') {
                $this->saveStoreMiscellaneousCashPaymentMetaDetails($storePayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'cheque') {
                $this->saveStoreMiscellaneousChequePaymentMetaDetails($storePayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'remit') {
                $this->saveStoreMiscellaneousRemitPaymentMetaDetails($storePayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'wallet') {
                if($validatedData['wallet_code'] == (new DigitalWallet())->getConnectIpsCode()){
                    $this->saveStoreMiscellaneousWalletConnectIpsExtraMetaDetails($storePayment,$validatedData);
                }else{
                    $this->saveStoreMiscellaneousWalletExceptConnectIpsExtraMetaDetails($storePayment,$validatedData);
                }
                $this->saveStoreMiscellaneousWalletPaymentMetaDetails($storePayment, $validatedData);
            }elseif($validatedData['payment_type'] == 'mobile_banking'){
                $this->saveStoreMiscellaneousMobileBankingMetaDetails($storePayment,$validatedData);
            }

            DB::commit();
            return $storePayment;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    public function saveStoreMiscellaneousCashPaymentMetaDetails(StoreMiscellaneousPayment $storeMiscellaneousPayment,
                                                                  $validatedData)
    {

        $data = [
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

        $this->storeMiscellaneousPaymentRepo->savePaymentMetaDetail($storeMiscellaneousPayment, $data);
    }

    public function saveStoreMiscellaneousChequePaymentMetaDetails(StoreMiscellaneousPayment $storeMiscellaneousPayment,
                                                                    $validatedData)
    {
        $data = [
            [
                'key' => 'deposit_bank_name',
                'value' => $validatedData['deposit_bank_name']
            ],
            [
                'key' => 'deposited_branch_name',
                'value' => $validatedData['deposited_branch_name']
            ],
            [
                'key' => 'bank_code',
                'value' => $validatedData['bank_code']
            ],
            [
                'key' => 'cheque_bank',
                'value' => $validatedData['cheque_bank']
            ],
            [
                'key' => 'cheque_bank_code',
                'value' => $validatedData['cheque_bank_code']
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

        $this->storeMiscellaneousPaymentRepo->savePaymentMetaDetail($storeMiscellaneousPayment, $data);
    }

    public function saveStoreMiscellaneousRemitPaymentMetaDetails(StoreMiscellaneousPayment $storeMiscellaneousPayment,
                                                                   $validatedData)
    {
        $transactionNumberData = [];
        if(count($validatedData['transaction_number']) > 0 ){
            foreach($validatedData['transaction_number'] as $key => $transactionNumber){
                $transactionNumberData[$key] = [
                    'key'=> 'transaction_number',
                    'value' => $transactionNumber
                ];
            }
        }

        $data = [
            [
                'key' => 'remit_name',
                'value' => $validatedData['remit_name']
            ],
            [
                'key' => 'remit_code',
                'value' => $validatedData['remit_code']
            ],
            [
                'key' => 'remit_branch_name',
                'value' => $validatedData['remit_branch_name']
            ],
            [
                'key' => 'bank_name',
                'value' => $validatedData['bank_name']
            ],
            [
                'key' => 'bank_code',
                'value' => $validatedData['bank_code']
            ],
            [
                'key' => 'receiver_name',
                'value' => $validatedData['receiver_name']
            ],
            [
                'key' => 'receiver_bank_account_name',
                'value' => $validatedData['receiver_bank_account_name']
            ],
        ];

        $data = array_merge($data , $transactionNumberData);

        $this->storeMiscellaneousPaymentRepo->savePaymentMetaDetail($storeMiscellaneousPayment, $data);
    }

    public function saveStoreMiscellaneousWalletPaymentMetaDetails(StoreMiscellaneousPayment $storeMiscellaneousPayment,
                                                                    $validatedData)
    {
        $data = [
            [
                'key' => 'payment_partner',
                'value' => $validatedData['payment_partner']
            ], [
                'key' => 'wallet_code',
                'value' => $validatedData['wallet_code']
            ],
        ];

        $this->storeMiscellaneousPaymentRepo->savePaymentMetaDetail($storeMiscellaneousPayment, $data);
    }

    public function saveStoreMiscellaneousWalletExceptConnectIpsExtraMetaDetails(
        StoreMiscellaneousPayment $storeMiscellaneousPayment,
                                  $validatedData
    ){

        $transactionNumberData = [];
        if(count($validatedData['transaction_number']) > 0 ){
            foreach($validatedData['transaction_number'] as $key => $transactionNumber){
                $transactionNumberData[$key] = [
                    'key'=> 'transaction_number',
                    'value' => $transactionNumber
                ];
            }
        }

        $data = [
            [
                'key' => 'receiver_name',
                'value' => $validatedData['receiver_name']
            ],
            [
                'key' => 'receiver_id',
                'value' => $validatedData['receiver_id']
            ]
        ];
        $data = array_merge($data , $transactionNumberData);
        $this->storeMiscellaneousPaymentRepo->savePaymentMetaDetail($storeMiscellaneousPayment, $data);
    }

    public function saveStoreMiscellaneousWalletConnectIpsExtraMetaDetails(StoreMiscellaneousPayment $storeMiscellaneousPayment,
                                                                                                $validatedData)
    {
       // dd($validatedData['transaction_number']);
        $transactionNumberData = [];
        if(count($validatedData['transaction_number']) > 0 ){
            foreach($validatedData['transaction_number'] as $key => $transactionNumber){
                $transactionNumberData[$key] = [
                    'key'=> 'transaction_number',
                    'value' => $transactionNumber
                ];
            }
        }

        $data = [
            [
                'key' => 'bank_code',
                'value' => $validatedData['bank_code']
            ],
            [
                'key' => 'bank_name',
                'value' => $validatedData['bank_name'],
            ],
            [
                'key' => 'branch_name',
                'value' => $validatedData['branch_name']
            ],
            [
                'key' => 'account_number',
                'value' => $validatedData['account_number']
            ],
            [
                'key' => 'account_holder_name',
                'value' => $validatedData['account_holder_name']
            ],
            [
                'key' => 'remark',
                'value' => $validatedData['remark']
            ],
            [
                'key' => 'sender_bank_code',
                'value' => $validatedData['sender_bank_code']
            ],
            [
                'key' => 'sender_bank_name',
                'value' => $validatedData['sender_bank_name']
            ],
            [
                'key' => 'sender_branch_name',
                'value' => $validatedData['sender_branch_name']
            ],
            [
                'key' => 'sender_account_number',
                'value' => $validatedData['sender_account_number']
            ]
        ];

        $data = array_merge($data , $transactionNumberData);

        $this->storeMiscellaneousPaymentRepo->savePaymentMetaDetail($storeMiscellaneousPayment, $data);
    }

    public function saveStoreMiscellaneousMobileBankingMetaDetails(StoreMiscellaneousPayment $storeMiscellaneousPayment,
                                                                                             $validatedData)
    {
        // dd($validatedData['transaction_number']);
        $transactionNumberData = [];
        if(count($validatedData['transaction_number']) > 0 ){
            foreach($validatedData['transaction_number'] as $key => $transactionNumber){
                $transactionNumberData[$key] = [
                    'key'=> 'transaction_number',
                    'value' => $transactionNumber
                ];
            }
        }

        $data = [
            [
                'key' => 'bank_code',
                'value' => $validatedData['bank_code']
            ],
            [
                'key' => 'bank_name',
                'value' => $validatedData['bank_name'],
            ],
            [
                'key' => 'account_number',
                'value' => $validatedData['account_number']
            ],
            [
                'key' => 'account_holder_name',
                'value' => $validatedData['account_holder_name']
            ],
            [
                'key' => 'remark',
                'value' => $validatedData['remark']
            ],
            [
                'key' => 'sender_bank_code',
                'value' => $validatedData['sender_bank_code']
            ],
            [
                'key' => 'sender_bank_name',
                'value' => $validatedData['sender_bank_name']
            ],
            [
                'key' => 'sender_account_number',
                'value' => $validatedData['sender_account_number']
            ]
        ];

        $data = array_merge($data , $transactionNumberData);

        $this->storeMiscellaneousPaymentRepo->savePaymentMetaDetail($storeMiscellaneousPayment, $data);
    }

    private function saveStoreMiscellaneousPaymentDocuments(StoreMiscellaneousPayment $storeMiscellaneousPayment, $documents, $documentTypes)
    {
        foreach ($documents as $i => $document) {
            $this->storeMiscellaneousPaymentRepo->savePaymentDocument($storeMiscellaneousPayment, $document, $documentTypes[$i]);
        }
    }

    public function adminUpdateMiscPayment($storePaymentCode,$validatedData)
    {
        try{
           $storePayment = $this->storeMiscellaneousPaymentRepo->findOrFailByCode($storePaymentCode);
           $validatedPaymentData['transaction_date'] = $validatedData['transaction_date'];
           $this->storeMiscellaneousPaymentRepo->updateStorePayment($storePayment,$validatedPaymentData);

           if(in_array($storePayment->payment_type ,['remit','mobile_banking'])
               || $storePayment->paymentMetaData()->where('key','wallet_code')->where('value','DW06')->first()
           ){
                 foreach($storePayment->paymentMetaData()->where('key','transaction_number')->get() as $key => $transactionsNumbers){
                     $transactionNumber = trim($validatedData['transaction_number'][$key]);
                     $paymentMetaCode = $validatedData['payment_meta_code'][$key];
                     if(empty($paymentMetaCode)){
                         throw new Exception('Payment Code Is Required');
                     }
                     if(empty($transactionNumber)){
                         throw new Exception('Transaction Number Is Required');
                     }
                 }

               foreach($validatedData['payment_meta_code'] as $key =>  $paymentMeta){
                   $validatedTransactionMetaData = [];
                   $storePaymentMeta = $this->storeMiscellaneousPaymentRepo->findOrFailPaymentMetaByCode($paymentMeta);
                   $validatedTransactionMetaData['value'] = $validatedData['transaction_number'][$key];
                   $this->storeMiscellaneousPaymentRepo->updatePaymentMetaDetails($storePaymentMeta,$validatedTransactionMetaData);
               }
           }

           $validatedRemarkMetaData = [];

            if(in_array($storePayment->payment_type ,['mobile_banking'])
                || $storePayment->paymentMetaData()->where('key','wallet_code')->where('value','DW06')->first()
            ){
                    $paymentMetaRemarkCode = $validatedData['payment_meta_remark_code'];
                    $remark = $validatedData['remark'];
                    if(empty($paymentMetaRemarkCode)){
                        throw new Exception('Payment Remark Code Is Required');
                    }
                    if(empty($remark)){
                        throw new Exception('Remark Is Required');
                    }

                if(isset($validatedData['payment_meta_remark_code'])){
                    $storePaymentRemarkMeta = $this->storeMiscellaneousPaymentRepo->findOrFailPaymentMetaByCode($validatedData['payment_meta_remark_code']);
                    $validatedRemarkMetaData['value'] = $validatedData['remark'];
                    $this->storeMiscellaneousPaymentRepo->updatePaymentMetaDetails($storePaymentRemarkMeta,$validatedRemarkMetaData);
                }
            }

            if(!($validatedData['payment_meta_admin_description_code'])){
                $adminDecriptionValidatedData['store_misc_payment_code'] = $storePaymentCode;
                $adminDecriptionValidatedData['key'] = 'admin_description';
                $adminDecriptionValidatedData['value'] = $validatedData['admin_description'];
                $paymentMetaDescription = $this->storeMiscellaneousPaymentRepo->createPaymentMetaDetails($adminDecriptionValidatedData);
            }else{
                $paymentMetaAdminDescriptionCode = $validatedData['payment_meta_admin_description_code'];
                $adminDecription = $validatedData['admin_description'];
                if(empty($paymentMetaAdminDescriptionCode)){
                    throw new Exception('Admin Decription Code Is Required');
                }
                if(empty($adminDecription)){
                    throw new Exception('Admin Description Is Required');
                }
                $storePaymentAdminDecriptionMeta = $this->storeMiscellaneousPaymentRepo->findOrFailPaymentMetaByCode($validatedData['payment_meta_admin_description_code']);
                $validatedAdminDescriptionMetaData['value'] = $validatedData['admin_description'];
                $paymentMetaDescription = $this->storeMiscellaneousPaymentRepo->updatePaymentMetaDetails($storePaymentAdminDecriptionMeta,$validatedAdminDescriptionMetaData);
            }
            if($paymentMetaDescription){
                $adminDescription = removeSpecialChar($validatedData['admin_description']);
                $this->updateHasMatched($storePayment,$adminDescription);
            }

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function checkRequiredValidationForMiscPaymentUpdate(){

    }

    private function updateHasMatched($storePayment,$adminDescription)
    {
        try{
            $balanceReconcilationDetail = $this->balanceReconciliationRepo->getUnusedReconcilation($storePayment,$adminDescription);
            if($balanceReconcilationDetail){
                return $this->storeMiscellaneousPaymentRepo->updateHasMatched($storePayment);
            }
        }catch(Exception $exception){
            throw $exception;
        }
    }


}
