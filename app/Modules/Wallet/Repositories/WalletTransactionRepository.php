<?php


namespace App\Modules\Wallet\Repositories;

use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Model;
use Exception;

class WalletTransactionRepository
{

    public function checkUsesOfWalletTransactionPurposeInTransactions($walletTransactionPurposeCode){

        $walletTransactions = WalletTransaction::where('wallet_transaction_purpose_code',$walletTransactionPurposeCode)
                                                ->first();
        if($walletTransactions){
            return true;
        }
        return false;
    }

    public function saveTransaction($validatedData){
        return WalletTransaction::create($validatedData)->fresh();
    }

    public function findByReferenceCode($referenceCode){
        return WalletTransaction::where('reference_code',$referenceCode)->first();
    }

    public function findOrFailByWalletTransactionCode($walletTransactionCode,$with=[]){
        $walletTransaction =   WalletTransaction::with($with)->where('wallet_transaction_code',$walletTransactionCode)->first();
        if(!$walletTransaction){
           throw new Exception('Wallet Transaction Cannot be found');
        }
        return $walletTransaction;
    }

    public function  getStoreWalletTotalNonRefundableRegistrationCharge(Wallet $wallet){
        $store = $wallet->walletable;

        return WalletTransaction::where('wallet_code',$wallet->wallet_code)
            ->where('wallet_transaction_purpose_code',
                $store->getWalletTransactionPurposeForNonRefundableRegistrationCharge()
                    ->wallet_transaction_purpose_code
            )
            ->sum('amount');

    }

    public function getStoreWalletTotalRefundableRegistrationCharge(Wallet $wallet){
        $store = $wallet->walletable;
        return WalletTransaction::where('wallet_code',$wallet->wallet_code)
            ->where('wallet_transaction_purpose_code',
                $store->getWalletTransactionPurposeForRefundable()
                    ->wallet_transaction_purpose_code
            )
            ->sum('amount');
    }






}
