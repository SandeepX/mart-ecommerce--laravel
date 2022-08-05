<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/29/2020
 * Time: 1:26 PM
 */

namespace App\Modules\Store\Helpers;


use App\Modules\Store\Models\Balance\StoreBalanceFreeze;
use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use App\Modules\Store\Models\Balance\StoreCurrentBalance;
use App\Modules\Store\Models\StoreFrozenBalanceView;
use Illuminate\Support\Facades\DB;


class StoreTransactionHelper
{


    public static function getStoreCumulativeBalanceQuery($storeCode){
        $bindings = [
            'store_code' => $storeCode
        ];

        $creditTypes = implode(',',StoreBalanceMaster::CREDIT_TYPES);

        $query = "
         with balanceRecords as (
                  select
                   *,
                    case when ( FIND_IN_SET(transaction_type,'".$creditTypes."'))
                    then round((transaction_amount),2)
                    else round((-1 * transaction_amount),2)
                    end as amount
                    from store_balance_master
                ),cumulativeBalanceRecords As (
                select
                 id,
                 store_balance_master_code,
                 store_code,
                 transaction_amount,
                 amount,
                 remarks,
                 proof_of_document,
                 transaction_type,
                 created_at,
                 created_by,
                 current_balance,
                 round(
                     sum(amount) over (order by id),
                      2) as balance
                from balanceRecords
                where store_code= '" .$bindings['store_code'] . "')

                SELECT * from cumulativeBalanceRecords
        ";
        return $query;
    }


    public static function getStoreLatestCumulativeBalanceQuery($storeCode)
    {
        $storeCumulativeBalanceQuery = self::getStoreCumulativeBalanceQuery($storeCode);
        $selectLatestBalancePartialQuery = "order by id desc limit 1";
        $query = $storeCumulativeBalanceQuery.$selectLatestBalancePartialQuery;

        return $query;
    }

    /*
     * frozen balance of a store:
     * withdraw requests : that are pending or processing (sum)
     * preorder : store preorders that are pending : total
     *
     *
     *
     *
     *
     * */


    public static function getStoreCumulativeBalanceRecords($storeCode)
    {
        $results = DB::select(self::getStoreCumulativeBalanceQuery($storeCode));
        return $results;
    }

    public static function getLatestStoreCumulativeBalance($storeCode){
        $bindings = [
            'store_code' => $storeCode
        ];
        $creditTypes = implode(',',StoreBalanceMaster::CREDIT_TYPES);

        $query = "
                    select
                    ROUND(sum(case when ( FIND_IN_SET(transaction_type,'".$creditTypes."'))
                    then round((transaction_amount),2)
                    else round((-1 * transaction_amount),2)
                    end),2) as amount
                    from store_balance_master
                     where store_code= '" .$bindings['store_code'] . "'";
        $results = DB::select($query);

        if($results[0]->amount){
            return $results[0]->amount;
        }
        return 0;
    }


    /******api filter for  Transaction of Store*********/
    public static function filterPaginatedStoreAllTransactionByParameters($filterParameters)
    {
       if(!isset($filterParameters['store_code'])){
           throw new \Exception('Store Code is Required');
       }
        $transactionType = (isset($filterParameters['transaction_type']) && in_array($filterParameters['transaction_type'], StoreBalanceMaster::TRANSACTION_TYPE)) ? true : false;


        $allTransactionDetailByFilter = StoreBalanceMaster::when(isset($filterParameters['store_code']), function ($query) use ($filterParameters) {
            $query->where('store_balance_master.store_code', $filterParameters['store_code']);
        })
            ->when($transactionType, function ($query) use ($filterParameters) {
                $query->where('store_balance_master.transaction_type', $filterParameters['transaction_type']);
            })
            ->when(isset($filterParameters['transaction_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('store_balance_master.created_at', '>=', date('y-m-d', strtotime($filterParameters['transaction_date_from'])));

            })
            ->when(isset($filterParameters['transaction_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('store_balance_master.created_at', '<=', date('y-m-d', strtotime($filterParameters['transaction_date_to'])));

            })
            ->select('cumulative_store_balance_master.*')
            ->addSelect(DB::raw('
                            CASE WHEN FIND_IN_SET(store_balance_master.transaction_type,"' . implode(',', StoreBalanceMaster::CREDIT_TYPES) . '") = 0 then "dr" else "cr" end as accounting_entry_type
                '))
            ->joinSub(self::getStoreCumulativeBalanceQuery($filterParameters['store_code']), 'cumulative_store_balance_master', function ($join) {
                $join->on('cumulative_store_balance_master.store_balance_master_code', '=', 'store_balance_master.store_balance_master_code');
            })

            ->orderBy('store_balance_master.id', 'DESC')
            ->paginate($filterParameters['records_per_page']);


        return $allTransactionDetailByFilter;

    }

    public static function adminPanelStoreAllTransactionByParameters($filterParameters)
    {
        if(!isset($filterParameters['store_code'])){
            throw new \Exception('Store Code is Required');
        }
        $transactionType = (isset($filterParameters['transaction_type']) && in_array($filterParameters['transaction_type'], StoreBalanceMaster::TRANSACTION_TYPE)) ? true : false;

        $allTransactionDetailByFilter = StoreBalanceMaster::when(isset($filterParameters['store_code']), function ($query) use ($filterParameters) {
            $query->where('store_balance_master.store_code', $filterParameters['store_code']);
        })
            ->when($transactionType, function ($query) use ($filterParameters) {
                $query->where('store_balance_master.transaction_type', $filterParameters['transaction_type']);
            })
            ->when(isset($filterParameters['transaction_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('store_balance_master.created_at', '>=', date('y-m-d', strtotime($filterParameters['transaction_date_from'])));

            })
            ->when(isset($filterParameters['transaction_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('store_balance_master.created_at', '<=', date('y-m-d', strtotime($filterParameters['transaction_date_to'])));

            })
            ->select('cumulative_store_balance_master.*')
            ->addSelect(DB::raw('
                            CASE WHEN FIND_IN_SET(store_balance_master.transaction_type,"' . implode(',', StoreBalanceMaster::CREDIT_TYPES) . '") = 0 then "dr" else "cr" end as accounting_entry_type
                '))
            ->joinSub(self::getStoreCumulativeBalanceQuery($filterParameters['store_code']), 'cumulative_store_balance_master', function ($join) {
                $join->on('cumulative_store_balance_master.store_balance_master_code', '=', 'store_balance_master.store_balance_master_code');
            })
            ->orderBy('store_balance_master.id', 'DESC')
            ->paginate(20);


        return $allTransactionDetailByFilter;

    }

    public static function getStoreActiveCurrentBalance($storeCode){
       $latestStoreBalance = self::getLatestStoreCumulativeBalance($storeCode);
//       $frozenBalance = StoreBalanceFreeze::where('store_code', $storeCode)
//                                          ->where('status', 1)
//                                          ->sum('amount');


        $frozenBalance = StoreFrozenBalanceView::where('store_code',$storeCode)
                                              ->first()->total_freeze_amount;

       // if ($latestStoreBalance >= 0) {
            return roundPrice($latestStoreBalance - $frozenBalance);
       // }
        //return 0;
    }

    public static function getStoreCurrentBalance($storeCode)
    {
        return self::getStoreActiveCurrentBalance($storeCode);
    }


    public static function checkIfNonrefundableRegistrationChargeDeducted($storeCode)
    {
        return StoreBalanceMaster::where('transaction_type','non_refundable_registration_charge')
            ->where('store_code',$storeCode)->sum('transaction_amount');
    }

    public static function checkIfRefundableRegistrationChargeDeducted($storeCode)
    {
        return StoreBalanceMaster::where('transaction_type','refundable')
            ->where('store_code',$storeCode)->sum('transaction_amount');
    }

    public static function getStoreFreezeAmountDetails($storeCode)
    {
        $totalFrozenBalance=[];

        $frozenBalance = StoreFrozenBalanceView::where('store_code',$storeCode)
            ->first();

        $totalFrozenBalance['total_freeze_amount'] = (double) $frozenBalance->total_freeze_amount;
        $totalFrozenBalance['total_withdraw_freeze'] = !is_null($frozenBalance->total_withdraw_freeze) ? (double) $frozenBalance->total_withdraw_freeze : 0 ;
        $totalFrozenBalance['total_preorder_freeze'] = !is_null($frozenBalance->total_preorder_freeze) ? (double) $frozenBalance->total_preorder_freeze : 0;

        return $totalFrozenBalance;

    }

}
