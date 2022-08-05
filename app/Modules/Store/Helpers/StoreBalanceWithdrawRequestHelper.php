<?php


namespace App\Modules\Store\Helpers;


use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use Illuminate\Support\Facades\DB;

class StoreBalanceWithdrawRequestHelper
{

    public static function getAllWithdrawRequest($paginatedBy,$filterData)
    {
        $latestWithdrawRequest=StoreBalanceWithdrawRequest::select('store_balance_withdraw_request_code',DB::raw('MAX(created_at) as last_created_at'))
            ->addSelect('store_code','status as last_verification_status')
            ->groupBy('store_code');

        $allwithdrawrequest=StoreBalanceWithdrawRequest::select(
            'store_balance_withdraw_request.store_balance_withdraw_request_code',
            'store_balance_withdraw_request.store_code',
            'store_balance_withdraw_request.status',
            'latest_withdraw_request.last_created_at',
            'store_balance_withdraw_request_lists_view.last_verification_status',
            'store_balance_withdraw_request_lists_view.pending',
            'store_balance_withdraw_request_lists_view.processing',
            'store_balance_withdraw_request_lists_view.rejected',
            'store_balance_withdraw_request_lists_view.completed'
        )
            ->joinSub($latestWithdrawRequest, 'latest_withdraw_request', function ($join) {
                $join->on('store_balance_withdraw_request.store_balance_withdraw_request_code', '=', 'latest_withdraw_request.store_balance_withdraw_request_code');
            })
            ->join('store_balance_withdraw_request_lists_view', function ($join) {
                $join->on('store_balance_withdraw_request_lists_view.store_code', '=', 'store_balance_withdraw_request.store_code');
            })

            ->when(isset($filterData['store_name']),function ($query) use ($filterData){
                $query->whereHas('store',function ($q) use ($filterData){
                    $q-> where('store_name', 'like', '%' . $filterData['store_name'] . '%');
                });
            })

            ->groupBy('store_balance_withdraw_request.store_code')
            ->orderByRaw('store_balance_withdraw_request_lists_view.last_created_at DESC')
            ->paginate($paginatedBy);

         return $allwithdrawrequest;
    }
}
