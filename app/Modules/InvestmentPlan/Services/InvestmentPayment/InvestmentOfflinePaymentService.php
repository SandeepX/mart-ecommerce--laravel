<?php

/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 08/14/2021
 * Time: 1:41 PM
 */

namespace App\Modules\InvestmentPlan\Services\InvestmentPayment;

use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\OfflinePayment\Repositories\OfflinePaymentDocumentRepository;
use App\Modules\OfflinePayment\Repositories\OfflinePaymentMetaRepository;
use App\Modules\OfflinePayment\Repositories\OfflinePaymentRepository;
use App\Modules\PaymentMedium\Models\DigitalWallet;
use App\Modules\Store\Repositories\Payment\StoreMiscellaneousPaymentRepository;
use App\Modules\Store\Repositories\StoreBalanceReconciliation\StoreBalanceReconciliationRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class InvestmentOfflinePaymentService
{
    private $storeMiscellaneousPaymentRepo;
    private $offlinePaymentRepository;
    private $offlinePaymentMetaRepository;
    private $offlinePaymentDocumentRepository;
    private $balanceReconciliationRepo;

    public function __construct(
        StoreMiscellaneousPaymentRepository $storeMiscellaneousPaymentRepository,
        StoreBalanceReconciliationRepository $balanceReconciliationRepo,
        OfflinePaymentRepository $offlinePaymentRepository,
        OfflinePaymentMetaRepository $offlinePaymentMetaRepository,
        OfflinePaymentDocumentRepository $offlinePaymentDocumentRepository
    )
    {
        $this->storeMiscellaneousPaymentRepo = $storeMiscellaneousPaymentRepository;
        $this->balanceReconciliationRepo = $balanceReconciliationRepo;
        $this->offlinePaymentRepository = $offlinePaymentRepository;
        $this->offlinePaymentMetaRepository = $offlinePaymentMetaRepository;
        $this->offlinePaymentDocumentRepository = $offlinePaymentDocumentRepository;
    }

    public function createInvestmentOfflinePayment($paymentData)
    {
        return $this->offlinePaymentRepository->save($paymentData);
    }


    public function saveOfflinePaymentForInvestmentSubscription(InvestmentPlanSubscription $investmentPlanSubscription,$validatedData)
    {
        try {
            $validatedData['verification_status'] = 'pending';
            $validatedData['offline_payment_holder_namespace'] = $investmentPlanSubscription->investment_plan_holder;
            $validatedData['payment_holder_type'] = $investmentPlanSubscription->investment_holder_type;
            $validatedData['offline_payment_holder_code'] = $investmentPlanSubscription->investment_holder_id;
            $validatedData['payment_for'] = $validatedData['transaction_type'];
            $validatedData['reference_code'] = $investmentPlanSubscription->ip_subscription_code;

            DB::beginTransaction();

            $miscData['verification_status'] = 'pending';
            $miscData['payment_for'] =  $validatedData['payment_for'];
            $miscData['payment_type'] = $validatedData['payment_type'];
            $miscData['user_code'] = getAuthUserCode();

            $lastestInvestmentVerificationStatus = $this->storeMiscellaneousPaymentRepo->getLatestMiscPaymentVerificationStatusByUserCode($miscData);

            if (!empty($lastestInvestmentVerificationStatus)) {
                $miscPaymentDetail = [];
                $miscPaymentDetail['deposited_by'] = $lastestInvestmentVerificationStatus->deposited_by;
                $miscPaymentDetail['transaction_date'] = $lastestInvestmentVerificationStatus->transaction_date;
                $miscPaymentDetail['amount'] = $lastestInvestmentVerificationStatus->amount;
                $miscPaymentDetail['contact_phone_no'] = $lastestInvestmentVerificationStatus->contact_phone_no;

                /*payment Detail*/
                $metaDetails = [];
                if (!is_null($lastestInvestmentVerificationStatus)) {
                    $miscPaymentCode = $lastestInvestmentVerificationStatus->store_misc_payment_code;
                    $miscellaneousPaymentsMetaData = $this->storeMiscellaneousPaymentRepo->getPaymentDetail($miscPaymentCode);

                    foreach ($miscellaneousPaymentsMetaData as $metadata) {
                        if ($metadata->key != 'transaction_number') {
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

                        if ($paymentDetailFront['wallet_code'] == (new DigitalWallet())->getConnectIpsCode()) {
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
                        } else {
                            $paymentDetailFront['receiver_name'] = $validatedData['receiver_name'];
                            $paymentDetailFront['receiver_id'] = $validatedData['receiver_id'];
                        }
                    }


                    if ($validatedData['payment_type'] == 'mobile_banking') {
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

            if (count($loadBalanceReconcileDetail) > 0) {
                $validatedData['has_matched'] = 1;
            }

            $validatedData['created_by'] = getAuthUserCode();

//            dd($validatedData);

            $investmentPayment = $this->createInvestmentOfflinePayment($validatedData);

            $this->saveInvestmentOfflinePaymentDocuments($investmentPayment, $validatedData['document_images'],
                $validatedData['document_types']);

            //meta details
            if ($validatedData['payment_type'] == 'cash') {
                $this->saveInvestmentOfflineCashPaymentMetaDetails($investmentPayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'cheque') {
                $this->saveInvestmentOfflineChequePaymentMetaDetails($investmentPayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'remit') {
                $this->saveInvestmentOfflineRemitPaymentMetaDetails($investmentPayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'wallet') {
                if ($validatedData['wallet_code'] == (new DigitalWallet())->getConnectIpsCode()) {
                    $this->saveInvestmentOfflineWalletConnectIpsExtraMetaDetails($investmentPayment, $validatedData);
                } else {
                    $this->saveInvestmentOfflineWalletExceptConnectIpsExtraMetaDetails($investmentPayment, $validatedData);
                }
                $this->saveInvestmentOfflineWalletPaymentMetaDetails($investmentPayment, $validatedData);
            } elseif ($validatedData['payment_type'] == 'mobile_banking') {
                $this->saveInvestmentOfflineMobileBankingMetaDetails($investmentPayment, $validatedData);
            }

            DB::commit();
            return $investmentPayment;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    public function saveInvestmentOfflineCashPaymentMetaDetails(OfflinePaymentMaster $offlinePaymentMaster,
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
            [
                'key' => 'investment_subscription_code',
                'value' => $validatedData['ip_subscription_code']
            ],

        ];
        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($offlinePaymentMaster, $data);

    }

    public function saveInvestmentOfflineChequePaymentMetaDetails(OfflinePaymentMaster $offlinePaymentMaster,
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
            [
                'key' => 'investment_subscription_code',
                'value' => $validatedData['ip_subscription_code']
            ],
        ];


        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($offlinePaymentMaster, $data);
    }

    public function saveInvestmentOfflineRemitPaymentMetaDetails(OfflinePaymentMaster $offlinePaymentMaster,
                                                                  $validatedData)
    {
        $transactionNumberData = [];
        if (count($validatedData['transaction_number']) > 0) {
            foreach ($validatedData['transaction_number'] as $key => $transactionNumber) {
                $transactionNumberData[$key] = [
                    'key' => 'transaction_number',
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
            [
                'key' => 'investment_subscription_code',
                'value' => $validatedData['ip_subscription_code']
            ],
        ];

        $data = array_merge($data, $transactionNumberData);

        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($offlinePaymentMaster, $data);
    }

    public function saveInvestmentOfflineWalletPaymentMetaDetails(OfflinePaymentMaster $offlinePaymentMaster,
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
//            [
//                'key' => 'investment_subscription_code',
//                'value' => $validatedData['ip_subscription_code']
//            ],
        ];
        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($offlinePaymentMaster, $data);
    }

    public function saveInvestmentOfflineWalletExceptConnectIpsExtraMetaDetails(
        OfflinePaymentMaster $offlinePaymentMaster,
        $validatedData
    )
    {

        $data = [
            [
                'key' => 'receiver_name',
                'value' => $validatedData['receiver_name']
            ],
            [
                'key' => 'receiver_id',
                'value' => $validatedData['receiver_id']
            ],
            [
                'key' => 'investment_subscription_code',
                'value' => $validatedData['ip_subscription_code']
            ],

        ];
        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($offlinePaymentMaster, $data);
    }

    public function saveInvestmentOfflineWalletConnectIpsExtraMetaDetails(OfflinePaymentMaster $offlinePaymentMaster,
                                                                           $validatedData)
    {
        // dd($validatedData['transaction_number']);
        $transactionNumberData = [];
        if (count($validatedData['transaction_number']) > 0) {
            foreach ($validatedData['transaction_number'] as $key => $transactionNumber) {
                $transactionNumberData[$key] = [
                    'key' => 'transaction_number',
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
            ],
            [
                'key' => 'investment_subscription_code',
                'value' => $validatedData['ip_subscription_code']
            ],
        ];

        $data = array_merge($data, $transactionNumberData);

        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($offlinePaymentMaster, $data);
    }

    public function saveInvestmentOfflineMobileBankingMetaDetails(OfflinePaymentMaster $offlinePaymentMaster,
                                                                   $validatedData)
    {
        // dd($validatedData['transaction_number']);
        $transactionNumberData = [];
        if (count($validatedData['transaction_number']) > 0) {
            foreach ($validatedData['transaction_number'] as $key => $transactionNumber) {
                $transactionNumberData[$key] = [
                    'key' => 'transaction_number',
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
            ],
            [
                'key' => 'investment_subscription_code',
                'value' => $validatedData['ip_subscription_code']
            ],
        ];

        $data = array_merge($data, $transactionNumberData);

        $this->offlinePaymentMetaRepository->savePaymentMetaDetail($offlinePaymentMaster, $data);
    }

    private function saveInvestmentOfflinePaymentDocuments(OfflinePaymentMaster $offlinePaymentMaster, $documents, $documentTypes)
    {
        foreach ($documents as $i => $document) {
            $this->offlinePaymentDocumentRepository->savePaymentDocument($offlinePaymentMaster, $document, $documentTypes[$i]);
        }
    }

}


