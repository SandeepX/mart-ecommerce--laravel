<?php


namespace App\Modules\Wallet\Interfaces;

use App\Modules\User\Models\User;

interface CurrentBalanceInterface
{
    public function getWalletCurrentBalanceDetails(User $user);
}
