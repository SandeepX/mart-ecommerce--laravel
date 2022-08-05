<?php


namespace App\Modules\Wallet\Helpers;


use App\Modules\Wallet\Models\WalletTransactionPurpose;

class WalletTransactionPurposeHelper
{

    public static function checkIfAlreadyExistsTransactionPurpose($userTypeCode,$slug,$walletTransactionPurposeCode = null){


        $transactionPurpose = WalletTransactionPurpose::where('user_type_code',$userTypeCode)
            ->where('slug',$slug)
            ->where('wallet_transaction_purpose_code','!=',$walletTransactionPurposeCode)
            ->first();

        if(!$transactionPurpose){
            return false;
        }
        return true;

    }


}
