<?php


namespace App\Modules\Wallet\Services;

use App\Modules\Wallet\Models\WalletTransaction;
use App\Modules\Wallet\Repositories\WalletRepository;
use App\Modules\Wallet\Repositories\WalletTransactionRemarksRepository;
use App\Modules\Wallet\Repositories\WalletTransactionRepository;
use Exception;
use Illuminate\Support\Str;

class WalletTransactionService
{
    private $walletTransactionRepository;
    private $walletRepository;
    private $walletTransactionRemarksRepository;

    public function __construct(
        WalletTransactionRepository $walletTransactionRepository,
        WalletRepository $walletRepository,
        WalletTransactionRemarksRepository $walletTransactionRemarksRepository
    )
    {
        $this->walletTransactionRepository = $walletTransactionRepository;
        $this->walletRepository = $walletRepository;
        $this->walletTransactionRemarksRepository = $walletTransactionRemarksRepository;
    }

    public function findOrfailByWalletTransactionCode($walletTransactionCode,$with=[]){
      return $this->walletTransactionRepository->findOrFailByWalletTransactionCode($walletTransactionCode,$with);
    }

    public function checkUsesOfWalletTransactionPurposeInTransactions($walletTransactionPurposeCode)
    {
        return $this->walletTransactionRepository->checkUsesOfWalletTransactionPurposeInTransactions(
            $walletTransactionPurposeCode
        );
    }

    public function createWalletTransaction($validatedData)
    {

        try {

            $wallet = $validatedData['wallet'];
            $walletTransactionPurpose = $validatedData['wallet_transaction_purpose'];
            $authUserCode = getAuthUserCode();

            $dataWalletTransaction = [];
            $dataWallet = [];
            $isNewReferenceCode = true;
            $walletTransaction = new WalletTransaction();
            $dataWalletTransaction['transaction_uuid'] = Str::uuid();
            while ($isNewReferenceCode) {
                $referenceCode = $walletTransaction->generateReferenceCode();
                $dataWalletTransaction['reference_code'] = $referenceCode;
                $existingReference = $this->walletTransactionRepository->findByReferenceCode($referenceCode);
                if (!$existingReference) {
                    $isNewReferenceCode = false;
                }
            }
            $dataWalletTransaction['wallet_code'] = $wallet->wallet_code;
            $dataWalletTransaction['wallet_transaction_purpose_code'] = $walletTransactionPurpose
                ->wallet_transaction_purpose_code;
            $dataWalletTransaction['transaction_purpose_reference_code'] = isset($validatedData['transaction_purpose_reference_code'])
                ? $validatedData['transaction_purpose_reference_code'] : NULL;
            $dataWalletTransaction['amount'] = $validatedData['amount'];
            $dataWalletTransaction['remarks'] = isset($validatedData['remarks'])
                ? $validatedData['remarks'] : NULL;
            $dataWalletTransaction['meta'] = isset($validatedData['meta'])
                ? $validatedData['meta'] : NULL;
            $dataWalletTransaction['proof_of_document'] = isset($validatedData['proof_of_document'])
                ? $validatedData['proof_of_document'] : NULL;
            $dataWalletTransaction['created_by'] = $authUserCode;
            $dataWalletTransaction['updated_by'] = $authUserCode;

            if ($walletTransactionPurpose->purpose_type == 'increment') {
                $currentBalance = $wallet->current_balance + roundPrice($validatedData['amount']);
                $dataWallet['last_balance'] = $wallet->current_balance;
                $dataWallet['current_balance'] = roundPrice($currentBalance);


            } elseif ($walletTransactionPurpose->purpose_type == 'decrement') {

                $currentBalance = $wallet->current_balance - roundPrice($validatedData['amount']);
                $dataWallet['last_balance'] = $wallet->current_balance;
                $dataWallet['current_balance'] = roundPrice($currentBalance);

            } else {
                throw new Exception('Purpose Type not found for Transaction');
            }
            $dataWallet['updated_by'] = $authUserCode;

            $walletTransaction = $this->walletTransactionRepository->saveTransaction($dataWalletTransaction);

            $this->walletRepository->updateWallet($wallet, $dataWallet);

            return $walletTransaction;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function saveExtraRemarks($validateData,$walletTransactionCode){
        try{
            $validateData['wallet_transaction_code'] = $walletTransactionCode;
            $validateData['created_by'] = getAuthUserCode();
            return $this->walletTransactionRemarksRepository->saveTransactionRemarks($validateData);
        }catch (Exception $exception){
            throw $exception;
        }
    }


}
