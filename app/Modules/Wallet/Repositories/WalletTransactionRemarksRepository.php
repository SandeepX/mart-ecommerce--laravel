<?php

namespace App\Modules\Wallet\Repositories;

use App\Modules\Wallet\Models\WalletTransactionRemark;

class WalletTransactionRemarksRepository
{
    public function saveTransactionRemarks($validatedData){
        $remarks = WalletTransactionRemark::create($validatedData);
        return $remarks->fresh();
    }

}
