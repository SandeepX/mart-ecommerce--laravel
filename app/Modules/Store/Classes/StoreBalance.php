<?php


namespace App\Modules\Store\Classes;


use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreFrozenBalanceView;
use App\Modules\User\Models\User;
use App\Modules\Wallet\Interfaces\CurrentBalanceInterface;
use App\Modules\Wallet\Repositories\WalletTransactionRepository;


class StoreBalance implements CurrentBalanceInterface
{


    public function getStoreWalletCurrentBalance(Store $store){
        $wallet = $store->wallet;
        if($wallet){
            return roundPrice($wallet->current_balance);
        }
        return 0.00;
    }

    public function getStoreFrozenBalance(Store $store){
        $frozenBalance = $store->frozenBalance;
        if($frozenBalance){
            return roundPrice($frozenBalance->total_freeze_amount);
        }
       return 0.00;
    }

    public function getStoreActiveBalance(Store $store){
        $currentBalance = $this->getStoreWalletCurrentBalance($store);
        $frozenBalance = $this->getStoreFrozenBalance($store);
        $currentActiveBalance = $currentBalance - $frozenBalance;

        return roundPrice($currentActiveBalance);
    }

    public function getStoreFreezeBalanceDetails(Store $store)
    {
        $totalFrozenBalance=[];
        $frozenBalance = $store->frozenBalance;

        $totalFrozenBalance['total_freeze_amount'] = (double) $frozenBalance->total_freeze_amount;
        $totalFrozenBalance['total_withdraw_freeze'] = !is_null($frozenBalance->total_withdraw_freeze) ? (double) $frozenBalance->total_withdraw_freeze : 0 ;
        $totalFrozenBalance['total_preorder_freeze'] = !is_null($frozenBalance->total_preorder_freeze) ? (double) $frozenBalance->total_preorder_freeze : 0;

        return $totalFrozenBalance;
    }

    public function getNonRefundableRegistrationChargeDeducted(Store $store){
        $wallet = $store->wallet;

        if(!$wallet){
              return 0;
        }
        $nonRefundableRegistrationCharge = (new WalletTransactionRepository())->getStoreWalletTotalNonRefundableRegistrationCharge($wallet);
        return $nonRefundableRegistrationCharge;
    }

    public function getRefundableRegistrationChargeDeducted(Store $store){
        $wallet = $store->wallet;
        if(!$wallet){
            return 0;
        }
        $nonRefundableRegistrationCharge = (new WalletTransactionRepository())->getStoreWalletTotalRefundableRegistrationCharge($wallet);
        return $nonRefundableRegistrationCharge;
    }

    public function getWalletCurrentBalanceDetails(User $user){

        $store = $user->store;
        $storeTotalBalance = $this->getStoreWalletCurrentBalance($store);
        $storeFreezeBalanceDetails = $this->getStoreFreezeBalanceDetails($store);

        $storeCurrentBalance = roundPrice($storeTotalBalance - $storeFreezeBalanceDetails['total_freeze_amount']);
        $balance['total_balance']=$storeTotalBalance;
        $balance['active_balance']=$storeCurrentBalance;
        $balance['freeze_balance']=$storeFreezeBalanceDetails;
        return $balance;
    }



}
