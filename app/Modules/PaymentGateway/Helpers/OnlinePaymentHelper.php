<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/22/2020
 * Time: 4:14 PM
 */

namespace App\Modules\PaymentGateway\Helpers;


use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;
use App\Modules\Store\Models\Store;
use App\Modules\Wallet\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OnlinePaymentHelper
{

    public static function filterPaginatedOnlinePayments($filterParameters,$paginateBy,$with=[])
    {
        $onlinePaymentLists = OnlinePaymentMaster::with($with)
            ->when($filterParameters['store_name'], function ($query) use ($filterParameters) {
                $query->whereHas('store',function ($q) use ($filterParameters){
                    $q->where('store_name', 'like', '%' . $filterParameters['store_name'] . '%');
                });
            })->when($filterParameters['transaction_id'], function ($query) use ($filterParameters) {
                $query->where('transaction_id', $filterParameters['transaction_id']);
            })->when($filterParameters['status'], function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            })
            ->when(isset($filterParameters['transaction_type']) && $filterParameters['transaction_type'],function ($query) use ($filterParameters){
                $query->where('transaction_type',$filterParameters['transaction_type']);
            })
            ->when(isset($filterParameters['payment_initiator']) && $filterParameters['payment_initiator'],function ($query) use ($filterParameters){
                $query->where('payment_initiator',$filterParameters['payment_initiator']);
            })
            ->where('created_at', '<=', Carbon::now()->subMinutes(15)->toDateTimeString());

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;
        $onlinePaymentLists = $onlinePaymentLists->latest()->paginate($paginateBy);
        return $onlinePaymentLists;
    }

    public static function getOnlinePaymentHolderName(OnlinePaymentMaster $onlinePaymentMaster)
    {
        $holderName = '';
        if($onlinePaymentMaster->payment_initiator == 'App\Modules\Store\Models\Store'){
            $holderName = $onlinePaymentMaster->onlinePaymentable->store_name;
        }
        if($onlinePaymentMaster->payment_initiator == 'App\Modules\SalesManager\Models\Manager'){
            $holderName = $onlinePaymentMaster->onlinePaymentable->manager_name;
        }
        if($onlinePaymentMaster->payment_initiator == 'App\Modules\User\Models\User'){
            $holderName = $onlinePaymentMaster->onlinePaymentable->name;
        }
        return $holderName;
    }

    public static function getLinkToWalletByInitiator($initiatorCode)
    {
        $link = null;
        $getWalletCode = Wallet::select('wallet_code','wallet_type')->where('wallet_holder_code',$initiatorCode)->first();
        if($getWalletCode){
            if($getWalletCode->wallet_type == 'store'){
                $link = route('admin.wallet.transactions.store.details',$getWalletCode->wallet_code );
            }
            if($getWalletCode->wallet_type =='manager'){
                $link = route('admin.wallet.transactions.manager.details',$getWalletCode->wallet_code );
            }

            if($getWalletCode->wallet_type =='vendor'){
                $link = route('admin.wallet.transactions.vendor.details',$getWalletCode->wallet_code );
            }
        }

        return $link;

    }



}
