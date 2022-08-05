<?php


namespace App\Modules\PaymentGateway\Services;

use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;
use App\Modules\PaymentGateway\Repositories\OnlinePaymentMasterRepository;
use App\Modules\PaymentGateway\Repositories\OnlinePaymentMetaRepository;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Wallet\Classes\TransactionNotificationConfiguration;
use App\Modules\Wallet\Services\WalletTransactionService;

class OnlinePaymentService
{
    private $onlinePaymentMasterRepository;
    private $onlinePaymentMetaRepository;
    public $transactionNotificationConfiguration;
    public $walletTransactionService;
    public $storeBalance;
    public $storeDetailRepo;

    public function __construct(
        OnlinePaymentMasterRepository $onlinePaymentMasterRepository,
        OnlinePaymentMetaRepository $onlinePaymentMetaRepository,
        TransactionNotificationConfiguration $transactionNotificationConfiguration,
        WalletTransactionService $walletTransactionService,
        StoreBalance $storeBalance,
        StoreRepository $storeDetailRepo
    ){
        $this->onlinePaymentMasterRepository = $onlinePaymentMasterRepository;
        $this->onlinePaymentMetaRepository = $onlinePaymentMetaRepository;
        $this->transactionNotificationConfiguration = $transactionNotificationConfiguration;
        $this->walletTransactionService = $walletTransactionService;
        $this->storeBalance = $storeBalance;
        $this->storeDetailRepo = $storeDetailRepo;
    }

    public function getAllOnlinePaymentLists($paginateBy){
        return $this->onlinePaymentMasterRepository->getAllOnlinePaymentLists($paginateBy);
    }

    public function storeStautusUpdateOnLoadBalance(Store $store, $payingAmount,$smsSendStatus = false)
    {
        $transactionPurposes = [];
        $storeTypePackage = $store->storeTypePackage;
        if ($storeTypePackage) {
            $storeNonRefundableRegCharge = $storeTypePackage->non_refundable_registration_charge;
            $storeRefundableRegCharge = $storeTypePackage->refundable_registration_charge;
            $storeBaseInvestmentCharge = $storeTypePackage->base_investment;

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

                if ($nonRefundableChargePaidByStore >= $storeNonRefundableRegCharge
                    && $refundableChargePaidByStore >= $storeRefundableRegCharge
                ){
                    $store = $this->storeDetailRepo->changeStoreStatusToApproved($store);
                }

                if ($store->has_purchase_power == 0
                    && $store->status == "approved"
                    && $payingAmount >= $storeBaseInvestmentCharge
                ) {
                    $this->storeDetailRepo->enablePurchasingPower($store);
                }

            }
        }
        return $transactionPurposes;
    }

    public function prepareWalletTransactionForLoadBalance(
        Store $store,
        OnlinePaymentMaster $onlinePaymentMaster,
        $validatedData = []
    ){
        $walletTransaction['wallet'] = $store->wallet;
        $walletTransaction['wallet_transaction_purpose'] = $store->getWalletTransactionPurposeForLoadBalance();
        $walletTransaction['amount'] = roundPrice(convertPaisaToRs($onlinePaymentMaster['amount']));
        $walletTransaction['remarks'] = isset($validatedData['remarks']) ?? 'Online Payment Load Balance';
        $walletTransaction['transaction_purpose_reference_code'] = $onlinePaymentMaster->online_payment_master_code;
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

    public function saveOnlinePaymentWalletPaymentMetaDetails(OnlinePaymentMaster $onlinePaymentMaster,
                                                                                  $validatedData)
    {
        $data = [
            [
                'key' => 'payment_partner',
                'value' => $validatedData['payment_partner']
            ],
            [
                'key' => 'wallet_code',
                'value' => $validatedData['wallet_code']
            ],
        ];
        $this->onlinePaymentMetaRepository->savePaymentMetaDetail($onlinePaymentMaster, $data);
    }




}
