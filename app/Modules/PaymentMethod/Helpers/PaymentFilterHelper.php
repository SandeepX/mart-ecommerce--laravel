<?php

namespace App\Modules\PaymentMethod\Helpers;

use App\Modules\OfflinePayment\Models\OfflinePaymentMaster;
use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;
use Illuminate\Support\Facades\DB;

class PaymentFilterHelper
{

    public static function getPaymentAllLists($paymentHolderCode, $filterParameters,$paginateBy = 10){

        $amountCondition = (isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'], ['>', '<', '>=', '<=', '='])) ? true : false;

        $onlinePayments = OnlinePaymentMaster::select(
                                   'online_payment_master_code as payment_code',
                                   'transaction_type as payment_for',
                                   'status as verification_status',
                                    DB::raw('"online" as payment_method'),
                                    'digital_wallets.wallet_slug as payment_type',
                                    'online_payment_master.created_at as created_at',
                                    DB::raw('amount/100 as amount')
                                   )
                                   ->join('digital_wallets','digital_wallets.wallet_code','=','online_payment_master.wallet_code')
                                   ->where('initiator_code',$paymentHolderCode)
                                   ->latest();

        $payments = OfflinePaymentMaster::select(
                                  'offline_payment_code as payment_code',
                                  'payment_for',
                                  'verification_status',
                                  DB::raw('"offline" as payment_method'),
                                  'payment_type',
                                  'created_at as created_at',
                                  'amount as amount'
                                )
                                ->where('offline_payment_holder_code',$paymentHolderCode)
                                ->union($onlinePayments)
                                ->when(isset($filterParameters['payment_type']), function ($query) use ($filterParameters) {
                                     $query->having('payment_type', $filterParameters['payment_type']);
                                })
                                ->when(isset($filterParameters['payment_method']), function ($query) use ($filterParameters) {
                                    $query->having('payment_method', $filterParameters['payment_method']);
                                })
                                ->when(isset($filterParameters['verification_status']), function ($query) use ($filterParameters) {
                                     $query->having('verification_status', $filterParameters['verification_status']);
                                })
                                ->when(isset($filterParameters['payment_date_from']), function ($query) use ($filterParameters) {
                                     $query->havingDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['payment_date_from'])));
                                })
                                ->when(isset($filterParameters['payment_date_to']), function ($query) use ($filterParameters) {
                                     $query->havingData('created_at', '<=', date('y-m-d', strtotime($filterParameters['payment_date_to'])));
                                })
                                ->when($amountCondition && isset($filterParameters['amount']), function ($query) use ($filterParameters) {
                                    $query->where('amount', $filterParameters['amount_condition'], $filterParameters['amount']);
                                });

                    $payments = $payments->when(isset($filterParameters['global_search_keyword']), function ($q) use ($filterParameters) {
                             $q->having(function ($query) use ($filterParameters) {
                                 $query->having('payment_code', 'like', '%' . $filterParameters['global_search_keyword'] . '%')
                                     ->orHaving('verification_status', 'like', '%' . $filterParameters['global_search_keyword'] . '%')
                                     ->orHaving('payment_type', 'like', '%' . $filterParameters['global_search_keyword'] . '%')
                                     ->orHavingDate('created_at', date('y-m-d', strtotime($filterParameters['global_search_keyword'])))
                                     ->orHaving('amount', 'like', '%' . $filterParameters['global_search_keyword'] . '%');
                             });
                         });
                    $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;
                    $payments = $payments->latest()->paginate($paginateBy);
                    return $payments;
    }

}
