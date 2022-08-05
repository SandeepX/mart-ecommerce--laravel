<?php

namespace App\Modules\SalesManager\Classes;

use App\Modules\SalesManager\Models\Manager;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Interfaces\CurrentBalanceInterface;

class SalesManagerBalance implements CurrentBalanceInterface
{

    public function getManagerWalletCurrentBalance(Manager $manager){

        $wallet = $manager->wallet;

        if($wallet){
            return roundPrice($wallet->current_balance);
        }
        return 0.00;
    }
//
//    public function getManagerFreezeBalanceDetails(User $user){
//
//        $totalFrozenBalance['total_freeze_amount'] = 0.00;
//        $totalFrozenBalance['total_withdraw_freeze'] = 0.00;
//        $totalFrozenBalance['total_preorder_freeze'] = 0.00;
//
//        return $totalFrozenBalance;
//    }

    public function getWalletCurrentBalanceDetails(User $user){

        $manager = $user->manager;
        $managerTotalBalance = $this->getManagerWalletCurrentBalance($manager);

       // $managerFreezeBalanceDetails = $this->getManagerFreezeBalanceDetails($user);

      //  $managerCurrentBalance = roundPrice($managerTotalBalance - $managerFreezeBalanceDetails['total_freeze_amount']);
//        $balance['total_balance']=$managerTotalBalance;
        $balance['active_balance']=$managerTotalBalance;
//        $balance['freeze_balance']=$managerFreezeBalanceDetails;

        return $balance;
    }

}
