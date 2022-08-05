<?php


namespace App\Modules\OfflinePayment\Services;

use App\Modules\OfflinePayment\Events\OfflinePaymentEvent;
use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\OfflinePayment\Repositories\OfflinePaymentMetaRepository;
use App\Modules\OfflinePayment\Repositories\OfflinePaymentRepository;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Events\LoadBalanceCompletedEvent;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Repositories\Payment\StoreMiscellaneousPaymentRepository;
use App\Modules\Store\Repositories\StoreBalanceReconciliation\BalanceReconciliationUsageRepository;
use App\Modules\Store\Repositories\StoreBalanceReconciliation\StoreBalanceReconciliationRepository;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Services\WalletTransactionService;
use App\Modules\PaymentMedium\Models\DigitalWallet;
use App\Modules\OfflinePayment\Repositories\OfflinePaymentDocumentRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class OfflinePaymentService
{
    public $transactionNotificationConfiguration;
    public $storeMiscellaneousPaymentRepository;
    public $offlinePaymentRepository;
    public $offlinePaymentMetaRepository;
    public $balanceReconciliationRepo;
    public $balanceReconciliationUsageRepo;
    public $walletTransactionService;
    public $storeBalance;
    public $offlinePaymentDocumentRepository;

    public function __construct(TransactionNotificationConfiguration $transactionNotificationConfiguration,
                                StoreMiscellaneousPaymentRepository $storeMiscellaneousPaymentRepository,
                                StoreRepository $storeDetailRepo,
                                OfflinePaymentRepository $offlinePaymentRepository,
                                OfflinePaymentMetaRepository $offlinePaymentMetaRepository,
                                StoreBalanceReconciliationRepository $balanceReconciliationRepo,
                                BalanceReconciliationUsageRepository $balanceReconciliationUsageRepo,
                                WalletTransactionService $walletTransactionService,
                                StoreBalance $storeBalance,
                                OfflinePaymentDocumentRepository $offlinePaymentDocumentRepository
    ){
        $this->transactionNotificationConfiguration = $transactionNotificationConfiguration;
        $this->storeMiscellaneousPaymentRepo = $storeMiscellaneousPaymentRepository;
        $this->storeDetailRepo = $storeDetailRepo;
        $this->offlinePaymentRepository = $offlinePaymentRepository;
        $this->offlinePaymentMetaRepository = $offlinePaymentMetaRepository;
        $this->balanceReconciliationRepo = $balanceReconciliationRepo;
        $this->balanceReconciliationUsageRepo = $balanceReconciliationUsageRepo;
        $this->walletTransactionService = $walletTransactionService;
        $this->storeBalance =$storeBalance;
        $this->offlinePaymentDocumentRepository = $offlinePaymentDocumentRepository;
    }

    public function findOrFailOfflinePaymentByCodeWithEager($offlinePaymentCode,$with = [])
    {
        try {
            return $this->offlinePaymentRepository->findOrFailByCode($offlinePaymentCode,$with);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function createStoreOfflinePayment($paymentData){
        return $this->offlinePaymentRepository->save($paymentData);
    }

    public function saveOfflinePayment($validatedData){
        try{
            $validatedData['verification_status'] = 'pending';

            $offlineData['verification_status'] = 'pending';
            $offlineData['payment_for'] = $validatedData['payment_for'];
            $offlineData['payment_type'] = $validatedData['payment_type'];
            $offlineData['offline_payment_holder_code'] = $validatedData['offline_payment_holder_code'];

            $latestStoreVerificationStatus = $this->offlinePaymentRepository->getLatestOfflinePaymentVerificationStatus($offlineData);
            if (!empty($latestStoreVerificationStatus)) {
                $offlinePaymentDetail = [];
                $offlinePaymentDetail['deposited_by'] = $latestStoreVerificationStatus->deposited_by;
                $offlinePaymentDetail['transaction_date'] = $latestStoreVerificationStatus->transaction_date;
                $offlinePaymentDetail['amount'] = $latestStoreVerificationStatus->amount;
                $offlinePaymentDetail['contact_phone_no'] = $latestStoreVerificationStatus->contact_phone_no;

                /*payment Detail*/
                $metaDetails = [];
                if (!is_null($latestStoreVerificationStatus)) {
                    $storeOfflinePaymentCode = $latestStoreVerificationStatus->offline_payment_code;
                    $storeMiscellaneousPaymentsMetaData = $this->offlinePaymentMetaRepository->getOfflinePaymentDetail($storeOfflinePaymentCode);

                    foreach ($storeMiscellaneousPaymentsMetaData as $metadata) {
                        if($metadata->key != 'transaction_number'){
                            $metaDetails[$metadata->key] = $metadata->value;
                        }
                    }
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
                        $offlineDataFront = [];
                        $offlineDataFront['deposited_by'] = $validatedData['deposited_by'];
                        $offlineDataFront['transaction_date'] = $validatedData['transaction_date'];
                        $offlineDataFront['amount'] = $validatedData['amount'];
                        $offlineDataFront['contact_phone_no'] = $validatedData['contact_phone_no'];

                        $diffInMiscPaymentDetailWhileLoadBalance = array_diff($offlinePaymentDetail, $offlineDataFront);

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
                throw new Exception('cannot find payment body code of this payment.', 404);
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
            if (count($loadBalanceReconcileDetail) > 0) {
                $validatedData['has_matched'] = 1;
            }
            $validatedData['created_by'] = getAuthUserCode();
            $storeOfflinePayment = $this->createStoreOfflinePayment($validatedData);

            $this->saveStoreOfflinePaymentDocuments($storeOfflinePayment, $validatedData['document_images'],
                $validatedData['document_types']);

            //meta details
            if ($validatedData['payment_type'] == 'cash') {
                $this->saveStoreOfflineCashPaymentMetaDetails($storeOfflinePayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'cheque') {
                $this->saveStoreOfflineChequePaymentMetaDetails($storeOfflinePayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'remit') {
                $this->saveStoreOfflineRemitPaymentMetaDetails($storeOfflinePayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'wallet') {
                if($validatedData['wallet_code'] == (new DigitalWallet())->getConnectIpsCode()){
                    $this->saveStoreOfflineWalletConnectIpsExtraMetaDetails($storeOfflinePayment,$validatedData);
                }else{
                    $this->saveStoreOfflineWalletExceptConnectIpsExtraMetaDetails($storeOfflinePayment,$validatedData);
                }
                $this->saveStoreOfflineWalletPaymentMetaDetails($storeOfflinePayment, $validatedData);
            }elseif($validatedData['payment_type'] == 'mobile_banking'){
                $this->saveStoreOfflineMobileBankingMetaDetails($storeOfflinePayment,$validatedData);
            }
            return $storeOfflinePayment;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveStoreOfflineCashPaymentMetaDetails(OfflinePaymentMaster $storeOfflinePayment,$validatedData){
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

        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($storeOfflinePayment, $data);
    }

    public function saveStoreOfflineChequePaymentMetaDetails(OfflinePaymentMaster $storeOfflinePayment, $validatedData)
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

        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($storeOfflinePayment, $data);
    }

    public function saveStoreOfflineRemitPaymentMetaDetails(OfflinePaymentMaster $storeOfflinePayment,
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

        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($storeOfflinePayment, $data);
    }

    public function saveStoreOfflineWalletPaymentMetaDetails(OfflinePaymentMaster $storeOfflinePayment,
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

        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($storeOfflinePayment, $data);
    }

    public function saveStoreOfflineWalletExceptConnectIpsExtraMetaDetails(
        OfflinePaymentMaster $storeOfflinePayment,
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
        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($storeOfflinePayment, $data);
    }

    public function saveStoreOfflineWalletConnectIpsExtraMetaDetails(OfflinePaymentMaster $storeOfflinePayment,
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

        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($storeOfflinePayment, $data);
    }

    public function saveStoreOfflineMobileBankingMetaDetails(OfflinePaymentMaster $storeOfflinePayment,
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

        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($storeOfflinePayment, $data);
    }

    private function saveStoreOfflinePaymentDocuments(OfflinePaymentMaster $offlinePaymentMaster, $documents, $documentTypes)
    {
        foreach ($documents as $i => $document) {
            $this->offlinePaymentDocumentRepository->savePaymentDocument($offlinePaymentMaster, $document, $documentTypes[$i]);
        }
    }

    public function respondToOfflinePaymentByAdmin($validatedData,$SMPCode)
    {
        $this->transactionNotificationConfiguration->setSMSSendStatus(true);
        $validatedData['sms'] = $this->transactionNotificationConfiguration->getSMSSendStatus();
        return $this->updateStatusOfOfflinePayment($validatedData,$SMPCode);
    }

    public function updateStatusOfOfflinePayment($validatedData,$SMPCode){
        try{
            $offlinePayment = $this->offlinePaymentRepository->findOrFailByCode($SMPCode);

            if($offlinePayment->payment_for != 'load_balance' ){
                throw new Exception('Only load balance payments can be handled .');
            }
            $paymemtHolderEntity = $offlinePayment->payment_holder_type;
            if($paymemtHolderEntity != 'store' ){
                throw new Exception('Only store payments can be handled .');
            }

            if (!$offlinePayment->isPending()){
                throw new Exception(
                    'This '.convertToWords($offlinePayment->payment_for,'_').' payment status : (Code : '.$offlinePayment->store_misc_payment_code.') cannot be changed at the moment !
                    Since , this payment has been already '.$offlinePayment->verification_status.'
            ',403);
            }
            DB::beginTransaction();
            $validatedData['remarks']= isset($validatedData['remarks']) ? $validatedData['remarks']:null;
            $offlinePaymentData = $this->offlinePaymentRepository->updateVerificationStatus($offlinePayment,$validatedData);

            if ($offlinePayment->isVerified()){
                //offline payment -> creator (store,manager,customer)

                switch ($paymemtHolderEntity){
                    case 'store':
                        $storeCode = $offlinePayment['payment_holder_code'];
                        $store = $this->storeDetailRepo->findStoreByCode($storeCode);
                        event(new LoadBalanceCompletedEvent($store, $offlinePaymentData, $validatedData));
                }
//                //   $store = $this->storeDetailRepo->findStoreByCode($offlinePayment['store_code']);
//                if($offlinePaymentData['payment_for'] == 'investment') {
//                    $paymentMetaData = $offlinePaymentData->paymentMetaData->where('key','investment_subscription_code')->first();
//                    event(new OfflinePaymentEvent($paymentMetaData,$offlinePaymentData,$validatedData));
//                }else{
//                    event(new LoadBalanceCompletedEvent($store, $offlinePaymentData, $validatedData));
//                }
            }
            DB::commit();
            return $offlinePayment;
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function adminUpdateOfflinePayment($offlinePaymentCode,$validatedData)
    {
        try{
            $offlinePayment = $this->offlinePaymentRepository->findOrFailByCode($offlinePaymentCode);
            $validatedPaymentData['transaction_date'] = $validatedData['transaction_date'];
            $this->offlinePaymentRepository->updateOfflinePayment($offlinePayment,$validatedPaymentData);

            if(in_array($offlinePayment->payment_type ,['remit','mobile_banking'])
                || $offlinePayment->paymentMetaData()->where('key','wallet_code')->where('value','DW06')->first()
            ){
                foreach($offlinePayment->paymentMetaData()->where('key','transaction_number')->get() as $key => $transactionsNumbers){
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
                    $storePaymentMeta = $this->offlinePaymentMetaRepository->findOrFailOfflinePaymentMetaByCode($paymentMeta);
                    $validatedTransactionMetaData['value'] = $validatedData['transaction_number'][$key];
                    $this->offlinePaymentMetaRepository->updatePaymentMetaDetails($storePaymentMeta,$validatedTransactionMetaData);
                }
            }

            $validatedRemarkMetaData = [];

            if(in_array($offlinePayment->payment_type ,['mobile_banking'])
                || $offlinePayment->paymentMetaData()->where('key','wallet_code')->where('value','DW06')->first()
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
                    $storePaymentRemarkMeta = $this->offlinePaymentMetaRepository->findOrFailOfflinePaymentMetaByCode($validatedData['payment_meta_remark_code']);
                    $validatedRemarkMetaData['value'] = $validatedData['remark'];
                    $this->offlinePaymentMetaRepository->updatePaymentMetaDetails($storePaymentRemarkMeta,$validatedRemarkMetaData);
                }
            }

            if(!($validatedData['payment_meta_admin_description_code'])){
                $adminDecriptionValidatedData['offline_payment_code'] = $offlinePaymentCode;
                if(!$validatedData['admin_description']){
                    throw new Exception('Admin Description Is Required');
                }
                $adminDecriptionValidatedData['key'] = 'admin_description';
                $adminDecriptionValidatedData['value'] = $validatedData['admin_description'];
                $paymentMetaDescription = $this->offlinePaymentMetaRepository->createPaymentMetaDetails($adminDecriptionValidatedData);
            }else{
                $paymentMetaAdminDescriptionCode = $validatedData['payment_meta_admin_description_code'];
                $adminDecription = $validatedData['admin_description'];
                if(!$paymentMetaAdminDescriptionCode){
                    throw new Exception('Admin Decription Code Is Required');
                }
                if(!$adminDecription){
                    throw new Exception('Admin Description Is Required');
                }
                $storePaymentAdminDecriptionMeta = $this->offlinePaymentMetaRepository
                                                        ->findOrFailOfflinePaymentMetaByCode($validatedData['payment_meta_admin_description_code']);

                $validatedAdminDescriptionMetaData['value'] = $validatedData['admin_description'];
                $paymentMetaDescription = $this->offlinePaymentMetaRepository->updatePaymentMetaDetails($storePaymentAdminDecriptionMeta,$validatedAdminDescriptionMetaData);
            }
            if($paymentMetaDescription){
                $adminDescription = removeSpecialChar($validatedData['admin_description']);
                $this->updateHasMatched($offlinePayment,$adminDescription);
            }

        }catch (Exception $exception){
            throw $exception;
        }
    }

    private function updateHasMatched($offlinePayment,$adminDescription)
    {
        try{
            $balanceReconcilationDetail = $this->balanceReconciliationRepo->getUnusedReconcilation($offlinePayment,$adminDescription);
            if($balanceReconcilationDetail){
                return $this->offlinePaymentRepository->updateHasMatched($offlinePayment);
            }
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function handleBrCodeAfterBalanceLoad($balanceReconciliationCode, $offlinePayment)
    {
        //dd($balanceReconciliationCode,$offlinePayment);
        $balanceReconciliation = $this->balanceReconciliationRepo->getBalanceReconciliationForAdminVerfication($balanceReconciliationCode);
        if (is_null($balanceReconciliation)) {
            throw new Exception(
                'please wait the transaction record not found in balance reconciliation table
         or transation with this id already used'
            );
        }
        /**This updates the status of balance transaction detail to used using upcoming BRCode */
        $this->balanceReconciliationRepo->updateOnlyStatusWhenOfflinePaymentVerified($balanceReconciliation);
        /**********Store In Balance Reconciliaiton Usage*********/
        $balanceReconciliationUsageData['balance_reconciliation_code'] = $balanceReconciliationCode;
        $balanceReconciliationUsageData['used_for_code'] = $offlinePayment->offline_payment_code;
        $balanceReconciliationUsageData['used_for'] ='offline_payment';
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
       //     $referralRegistrationIncentiveAmount = $storeTypePackage->referal_registration_incentive_amount;
            if ($store->status == "approved") {
                if ($store->has_purchase_power == 0 && ($payingAmount >= $storeBaseInvestmentCharge)){
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
                    ){
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
                    ){
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
                ){
                    $this->storeDetailRepo->enablePurchasingPower($store);
                }

            }
        }
        return $transactionPurposes;
    }

    public function prepareWalletTransactionForLoadBalance(
        Store $store,
        OfflinePaymentMaster $offlinePayment,
        $validatedData
    ){
        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $store->getWalletTransactionPurposeForLoadBalance();
        $walletTransaction['amount'] = roundPrice($offlinePayment['amount']);
        $walletTransaction['remarks'] = $validatedData['remarks'];
        $walletTransaction['transaction_purpose_reference_code'] = $offlinePayment->offline_payment_code;
        $walletTransaction['transaction_notification_details'] = [
            'sms' => [
                'contact_no' => $store->store_contact_mobile,
                'status' => $this->transactionNotificationConfiguration->getSMSSendStatus()
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


}
