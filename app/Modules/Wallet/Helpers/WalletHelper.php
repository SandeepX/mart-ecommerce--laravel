<?php


namespace App\Modules\Wallet\Helpers;


use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use App\Modules\Vendor\Models\Vendor;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletHelper
{

    public static function filterPaginatedWallets($filterParameters,$paginateBy,$with = []){

        $latestWalletTransactions = WalletTransaction::select(
                                      'wallet_code',
                                       DB::raw('MAX(created_at) as last_transaction_date')
                                    )
                                    ->groupBy('wallet_code');

        $wallets = Wallet::select(
                          'wallets.wallet_code',
                          'wallets.wallet_uuid',
                          'wallets.wallet_holder_type',
                          'wallets.wallet_type',
                          'wallets.wallet_holder_code',
                          'wallets.current_balance',
                          'wallets.last_balance',
                          'wallets.is_active',
                          'latest_transactions.last_transaction_date'
                        )
                       ->with($with)
                        ->leftJoinSub($latestWalletTransactions,'latest_transactions',function ($join){
                            $join->on('wallets.wallet_code','=','latest_transactions.wallet_code');
                        })
                        ->when(isset($filterParameters['wallet_type']),function ($query) use ($filterParameters){
                           $query->where('wallet_type',$filterParameters['wallet_type']);
                               if($filterParameters['wallet_type'] == 'store' && isset($filterParameters['wallet_name'])){
                                 $query->Join('stores_detail','stores_detail.store_code','=','wallets.wallet_holder_code')
                                     ->where('wallet_type','store')
                                     ->where('store_name','like','%'.$filterParameters['wallet_name'].'%');
                               }elseif($filterParameters['wallet_type'] == 'manager' && isset($filterParameters['wallet_name'])){
                                   $query->Join('managers_detail','managers_detail.manager_code','=','wallets.wallet_holder_code')
                                       ->where('wallet_type','manager')
                                       ->where('manager_name','like','%'.$filterParameters['wallet_name'].'%');
                               }elseif($filterParameters['wallet_type'] == 'vendor' && isset($filterParameters['wallet_name'])){
                                   $query->Join('vendors_detail','vendors_detail.vendor_code','=','wallets.wallet_holder_code')
                                       ->where('wallet_type','vendor')
                                       ->where('vendor_name','like','%'.$filterParameters['wallet_name'].'%');
                               }
                        })
                        ->when(isset($filterParameters['current_balance_order']),function($query) use ($filterParameters){
                            if($filterParameters['current_balance_order']=="high_to_low")
                            {
                                $query->orderBy('current_balance','DESC');
                            }
                            elseif($filterParameters['current_balance_order']=="low_to_high")
                            {
                                $query->orderBy('current_balance','ASC');
                            }
                        });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;
        $wallets = $wallets->orderBy('latest_transactions.last_transaction_date','DESC')->paginate($paginateBy);

       // dd($wallets);
        return $wallets;
    }

    public static function getWalletHolderName(Wallet $wallet)
    {
        if($wallet->wallet_type == 'store'){
            $holderName = $wallet->walletable->store_name;
        }
        if($wallet->wallet_type == 'vendor'){
            $holderName = $wallet->walletable->vendor_name;
        }
        if($wallet->wallet_type == 'manager'){
            $holderName = $wallet->walletable->manager_name;
        }
        return $holderName;
    }

}
