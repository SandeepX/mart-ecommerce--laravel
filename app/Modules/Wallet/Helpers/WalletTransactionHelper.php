<?php


namespace App\Modules\Wallet\Helpers;


use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use App\Modules\Store\Models\Store;
use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WalletTransactionHelper
{

    public static function getWalletTransactionDetailsWithParameters($walletCode,$filterParameters){


        $allTransactionDetails = WalletTransaction::select(
                                        'wallet_transaction.*'
                                   )
                                ->where('wallet_transaction.wallet_code',$walletCode)
                                ->when(isset($filterParameters['transaction_date_from']), function ($query) use ($filterParameters) {
                                    $query->whereDate('wallet_transaction.created_at',
                                        '>=',
                                        date('y-m-d', strtotime($filterParameters['transaction_date_from']))
                                    );
                                })
                                ->when(isset($filterParameters['transaction_date_to']), function ($query) use ($filterParameters) {
                                    $query->whereDate('wallet_transaction.created_at',
                                        '<=',
                                            date('y-m-d', strtotime($filterParameters['transaction_date_to']))
                                    );
                                })
                                ->when(isset($filterParameters['wallet_transaction_code']), function ($query) use ($filterParameters) {
                                    $query->where('wallet_transaction.wallet_transaction_code', $filterParameters['wallet_transaction_code']);
                                })
                                 ->leftJoin('wallet_transaction_purpose',
                                     'wallet_transaction_purpose.wallet_transaction_purpose_code',
                                     '=',
                                     'wallet_transaction.wallet_transaction_purpose_code'
                                 )
                                ->when(isset($filterParameters['transaction_type']), function ($query) use ($filterParameters) {
                                    $query->where('wallet_transaction_purpose.slug', $filterParameters['transaction_type']);
                                })

                                  ->addSelect('wallet_transaction_purpose.purpose')
                                  ->addSelect(DB::raw('CASE WHEN  wallet_transaction_purpose.purpose_type = "increment" THEN "cr" ELSE "dr" END as accounting_entry_type'))
                                  ->joinSub(self::getStoreCumulativeBalanceQuery($walletCode), 'cumulative_store_wallet_transaction', function ($join) {
                                     $join->on('cumulative_store_wallet_transaction.wallet_transaction_code', '=', 'wallet_transaction.wallet_transaction_code');
                                  })
                                  ->addSelect('cumulative_store_wallet_transaction.balance')
                                  ->orderBy('wallet_transaction.id', 'DESC')
                                  ->paginate($filterParameters['records_per_page']);

          return $allTransactionDetails;
    }

    public static function getStoreCumulativeBalanceQuery($walletCode){

        $bindings = [
            'wallet_code' => $walletCode
        ];

        $query = "
         with balanceRecords as (
                  select
                    wallet_transaction.*,
                    case when wallet_transaction_purpose.purpose_type = 'increment'
                    then round((amount),2)
                    else round((-1 * amount),2)
                    end as current_amount
                    from wallet_transaction
                    inner join wallet_transaction_purpose
                    on wallet_transaction_purpose.wallet_transaction_purpose_code = wallet_transaction.wallet_transaction_purpose_code
                ),

                cumulativeBalanceRecords As (
                select
                 id,
                 wallet_transaction_code,
                 balanceRecords.wallet_code,
                 amount,
                 current_amount,
                 round(
                     sum(current_amount) over (order by id),
                      2) as balance
                from balanceRecords
                where balanceRecords.wallet_code= '" .$bindings['wallet_code'] . "')

                SELECT * from cumulativeBalanceRecords
        ";

        return $query;

    }

//    public static function filterPaginatedStoreAllWalletTransactionByParameters(Wallet $wallet,$filterParameters){
//
//        $allTransactionDetailByFilter = WalletTransaction::select(
//            'wallet_transaction.*',
//            'wallet_transaction_purpose.purpose'
//        )
//                ->where('wallet_transaction.wallet_code', $wallet->wallet_code)
//                ->when(isset($filterParameters['transaction_type']), function ($query) use ($filterParameters) {
//                    $query->whereHas('walletTransactionPurpose',function ($query) use ($filterParameters){
//                        $query->where('slug', $filterParameters['transaction_type']);
//                    });
//                })
//                ->when(isset($filterParameters['transaction_date_from']), function ($query) use ($filterParameters) {
//                    $query->whereDate('wallet_transaction.created_at', '>=', date('y-m-d', strtotime($filterParameters['transaction_date_from'])));
//                })
//                ->when(isset($filterParameters['transaction_date_to']), function ($query) use ($filterParameters) {
//                    $query->whereDate('wallet_transaction.created_at', '<=', date('y-m-d', strtotime($filterParameters['transaction_date_to'])));
//                })
//                ->leftJoin('wallet_transaction_purpose',
//                    'wallet_transaction_purpose.wallet_transaction_purpose_code',
//                    '=',
//                    'wallet_transaction.wallet_transaction_purpose_code'
//                )
//                ->addSelect(DB::raw('CASE WHEN  wallet_transaction_purpose.purpose_type = "increment" THEN "cr" ELSE "dr" END as accounting_entry_type'))
//                ->joinSub(self::getStoreCumulativeBalanceQuery($wallet->wallet_code), 'cumulative_store_wallet_transaction', function ($join) {
//                    $join->on('cumulative_store_wallet_transaction.wallet_transaction_code', '=', 'wallet_transaction.wallet_transaction_code');
//                })
//            ->addSelect('cumulative_store_wallet_transaction.balance')
//                ->orderBy('wallet_transaction.id', 'DESC')
//            ->paginate($filterParameters['records_per_page']);
//
//        return $allTransactionDetailByFilter;
//    }

    public static function getStoreTotalRefundReleasableAmount(Store $store){

        $totalRefundAmount = WalletTransaction::where('wallet_code',$store->wallet->wallet_code)
            ->where('wallet_transaction_purpose_code',$store->getWalletTransactionPurposeForRefundable()->wallet_transaction_purpose_code)
            ->sum('amount');


        $totalRefundReleseAmount =  WalletTransaction::where('wallet_code',$store->wallet->wallet_code)
            ->where('wallet_transaction_purpose_code',$store->getWalletTransactionPurposeForRefundRelease()->wallet_transaction_purpose_code)
            ->sum('amount');

        return $totalRefundAmount - $totalRefundReleseAmount;

    }

    public static function generateTransactionReferenceLink($purpose,$userType,$transactionReferences=[])
    {

       // dd(substr($transactionReferences['transactionReferenceCode'],0,3));

        $link = null;
        $referenceCodeLinks = [
            'sales' => [
                'store'=>[
                    'link' => route('admin.store.orders.show',
                        (isset($transactionReferences['transactionReferenceCode'])) ? $transactionReferences['transactionReferenceCode'] : 'SO1000'
                    )
                ]
            ],
            'sales-return' => [
                'store'=>[
                    'link' => route('admin.store.orders.show',
                        (isset($transactionReferences['transactionReferenceCode'])) ? $transactionReferences['transactionReferenceCode'] : 'SO1000'
                    )
                ]
            ],
            'preorder' => [
                'store'=>[
                    'link' => route('admin.store.pre-orders.show',
                        (isset($transactionReferences['transactionReferenceCode'])) ? $transactionReferences['transactionReferenceCode'] : 'SPO1000'
                    )
                ]
            ],
            'preorder-refund' => [
                'store'=>[
                    'link' => route('admin.store.pre-orders.show',
                        (isset($transactionReferences['transactionReferenceCode'])) ? $transactionReferences['transactionReferenceCode'] : 'SPO1000'
                    )
                ]
            ],
            'load-balance'=>[
                'store' => [
                    'link' => (substr($transactionReferences['transactionReferenceCode'],0,3) === 'OFP') ? ( route('admin.wallet.offline-payment.load-balance.show',
                        (isset($transactionReferences['transactionReferenceCode'])) ? $transactionReferences['transactionReferenceCode'] : 'OFPC1000')
                    ) : NULL
                ]
            ],
            'store-referred-commission' => [
                'sales-manager' => [
                    'link' =>  route('admin.stores.show',
                        (isset($transactionReferences['transactionReferenceCode'])) ? $transactionReferences['transactionReferenceCode'] : 'S1000'
                    )
                ]
            ],
            'investment-commission'=>[
                'sales-manager' => [
                    'link' => route('admin.investment-subscription.show',
                        (isset($transactionReferences['transactionReferenceCode'])) ? $transactionReferences['transactionReferenceCode'] : 'IPS1000'
                    )
                ]
            ],
        ];

        $link = (isset($referenceCodeLinks[$purpose][$userType]['link'])) ? $referenceCodeLinks[$purpose][$userType]['link'] : $link;
      //  dd($link);
        return $link;
    }

    public static function getAllWalletTransactionsForDaybookWithParameters($filterParameters)
    {

        if(!$filterParameters['transaction_date_from'] && !$filterParameters['transaction_date_from']) {
            $filterParameters['transaction_date_normal'] = Carbon::now()->subDay(30)->toDateString();
        }

        $allTransactionForDaybook = WalletTransaction::select(
            'wallet_transaction.*'
        )
            ->when(isset($filterParameters['transaction_date_normal']), function ($query) use ($filterParameters) {
                $query->whereDate('wallet_transaction.created_at',
                    '>=',
                    date('y-m-d', strtotime($filterParameters['transaction_date_normal']))
                );
            })
            ->when(isset($filterParameters['transaction_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('wallet_transaction.created_at',
                    '>=',
                    date('y-m-d', strtotime($filterParameters['transaction_date_from']))
                );
            })
            ->when(isset($filterParameters['transaction_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('wallet_transaction.created_at',
                    '<=',
                    date('y-m-d', strtotime($filterParameters['transaction_date_to']))
                );
            })

            ->leftJoin('wallet_transaction_purpose',
                'wallet_transaction_purpose.wallet_transaction_purpose_code',
                '=',
                'wallet_transaction.wallet_transaction_purpose_code'
            )


            ->when(isset($filterParameters['transaction_type']), function ($query) use ($filterParameters) {
                if(isset($filterParameters['include_exclude']) && $filterParameters['include_exclude']=='exclude'){
                    $query->whereNotIn('wallet_transaction_purpose.slug',$filterParameters['transaction_type']);
                }else{
                    $query->whereIn('wallet_transaction_purpose.slug',$filterParameters['transaction_type']);
                }
            })

            ->when(isset($filterParameters['transaction_flow']), function ($query) use ($filterParameters) {
                $query->where('wallet_transaction_purpose.purpose_type', $filterParameters['transaction_flow']);
            })

            ->Join('wallets',
                'wallets.wallet_code',
                '=',
                'wallet_transaction.wallet_code'
            )
            ->when(isset($filterParameters['store_code']), function ($query) use ($filterParameters) {
                $query->where('wallets.wallet_holder_code', $filterParameters['store_code']);
            })

            ->addSelect('wallet_transaction_purpose.purpose')
            ->addSelect(DB::raw('CASE WHEN  wallet_transaction_purpose.purpose_type = "increment" THEN "dr" ELSE "cr" END as accounting_entry_type'))
            ->orderBy('wallet_transaction.id', 'DESC')
            ->paginate($filterParameters['records_per_page']);

        return $allTransactionForDaybook;
    }

    public static function getWalletTransactionDetailsForExcelExport($walletCode)
    {
        $allTransactionDetails = WalletTransaction::select(
            'wallet_transaction.*'
        )
        ->where('wallet_transaction.wallet_code',$walletCode)
        ->leftJoin('wallet_transaction_purpose',
            'wallet_transaction_purpose.wallet_transaction_purpose_code',
            '=',
            'wallet_transaction.wallet_transaction_purpose_code'
        )
        ->addSelect('wallet_transaction_purpose.purpose')
        ->addSelect(DB::raw('CASE WHEN  wallet_transaction_purpose.purpose_type = "increment" THEN "cr" ELSE "dr" END as accounting_entry_type'))
        ->joinSub(self::getStoreCumulativeBalanceQuery($walletCode), 'cumulative_store_wallet_transaction', function ($join) {
            $join->on('cumulative_store_wallet_transaction.wallet_transaction_code', '=', 'wallet_transaction.wallet_transaction_code');
        })
        ->addSelect('cumulative_store_wallet_transaction.balance')
        ->orderBy('wallet_transaction.id', 'DESC')
        ->get();
        return $allTransactionDetails;
    }

    public static function getStoreWalletTransactionWithDispatchAmount($walletCode,$filterParameters){
        $perPage = $filterParameters['perPage'];
        $query = self::getStoreWalletTransactionWithDispatchAmountQuery($walletCode,$filterParameters);
        $totalCount = count(DB::select($query));
        $offset = (($filterParameters['page'] - 1) * $perPage);
        if($perPage){
            $query .=  ' LIMIT '.$perPage. ' OFFSET '.$offset;
        }
        $results =  DB::select($query);
       // dd($results);
        $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
      //  dd($paginator);
        return $paginator;

    }

    private static function getStoreWalletTransactionWithDispatchAmountQuery($walletCode,$filterParameters){
        $query = "WITH statementWithoutOrders AS (
            SELECT
            wt.wallet_transaction_code,
            wt.created_at as date,
            wtp.slug as purpose,
            CASE WHEN wtp.purpose_type = 'increment'
            THEN
            'cr'
            ELSE
            'dr'
            END as purpose_type,
            wt.transaction_purpose_reference_code as reference_code,
            wt.proof_of_document as 'document',
            wt.amount as total_amount,
            wt.remarks,
            NULL as 'bill_merge_master_code'
        from
        wallet_transaction wt
        join wallet_transaction_purpose wtp
        on wtp.wallet_transaction_purpose_code = wt.wallet_transaction_purpose_code
        join wallets on wallets.wallet_code = wt.wallet_code and wallets.wallet_type = 'store'
        where wtp.slug NOT IN('preorder','sales','preorder-refund','sales-return')
        and wallets.wallet_holder_code = '".$filterParameters['store_code']."'
        ),

        statementWithNormalOrders AS (
        Select
            NULL as wallet_transaction_code,
            so.updated_at as date,
            'sales' as purpose,
            'dr' as purpose_type,
            so.store_order_code as reference_code,
            NULL as document,
            so.acceptable_amount as total_amount,
            NULL as remarks,
            bmd.bill_merge_master_code
          from store_orders so
          LEFT JOIN bill_merge_details bmd ON bmd.bill_code = so.store_order_code
          where so.delivery_status = 'dispatched'
           and so.store_code = '".$filterParameters['store_code']."'
        ),
        statementWithPreOrders AS (
         select
          NULL as wallet_transaction_code,
          spov.updated_at as date,
          'preorder' as purpose,
          'dr' as purpose_type,
         spov.store_preorder_code as reference_code,
         NULL as document,
         spov.total_price as total_amount,
          NULL as remarks,
          bmd.bill_merge_master_code
         from store_pre_orders_view spov
         LEFT JOIN bill_merge_details bmd ON bmd.bill_code = spov.store_preorder_code
         where spov.status = 'dispatched'
         and spov.store_code = '".$filterParameters['store_code']."'
        ),

        allUnionTransactionData AS (
            select * from statementWithoutOrders
            UNION
            select * from statementWithNormalOrders
            UNION
            SELECT * from statementWithPreOrders
        ),

        allUnionTransactionDataWithCurrentBalance AS (
            select
                wallet_transaction_code,
                ROW_NUMBER() OVER ( ORDER BY date  ) AS id,
                date,
                purpose,
                purpose_type,
                reference_code,
                document,
                total_amount,
                remarks,
                bill_merge_master_code,
                CASE WHEN purpose_type = 'cr'
                THEN
                     round((total_amount),2)
                ELSE
                    round((-1 * total_amount),2)
                END as total_current_balance
            from
            allUnionTransactionData
        ),

         requiredTranactionDispatchData As (
                select
                    id,
                    wallet_transaction_code,
                    date,
                    purpose,
                    purpose_type,
                    reference_code,
                    document,
                    total_amount,
                    remarks,
                    bill_merge_master_code,
                    total_current_balance,
                    round(
                        sum(total_current_balance) over (order by id),
                    2) as actual_balance
                from allUnionTransactionDataWithCurrentBalance
        )

        SELECT
        *
        from requiredTranactionDispatchData WHERE TRUE";

        if($filterParameters['wallet_transaction_code']){
            $query .= " AND wallet_transaction_code = '".$filterParameters['wallet_transaction_code']."' ";
        }
        if($filterParameters['transaction_type']){
            $query .= " AND purpose = '".$filterParameters['transaction_type']."' ";
        }
        if($filterParameters['transaction_date_from']){
            $query  .= " AND date >= '".$filterParameters['transaction_date_from']."' ";
        }
        if($filterParameters['transaction_date_to']){
            $query  .= " AND date <= '".$filterParameters['transaction_date_to']."' ";
        }

       $query .= " ORDER by id DESC";

        return $query;

    }


}
