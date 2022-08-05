<?php


namespace App\Modules\Store\Helpers;

use App\Modules\Store\Models\BalanceReconciliation\StoreBalanceReconciliation;

class BalanceReconciliationHelper
{
    public static function getAllBalanceReconciliationFilter($filterParameters)
    {

        $amountCondition=isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'],['>','<', '>=','<=','='])? true:false;

        $allBalanceReconciliationDetailByFilter = StoreBalanceReconciliation::when(isset($filterParameters['transaction_type']), function ($query) use ($filterParameters) {
            $query->where('transaction_type', $filterParameters['transaction_type']);
        })
            ->when(isset($filterParameters['payment_method']), function ($query) use ($filterParameters) {
                $query->where('payment_method', $filterParameters['payment_method']);

            })
            ->when(isset($filterParameters['balance_reconciliation_code']),function ($query) use ($filterParameters){
                $query->where('balance_reconciliation_code',$filterParameters['balance_reconciliation_code']);
            })

            ->when(isset($filterParameters['payment_method_name']), function ($query) use ($filterParameters) {
                $query->where('payment_body_code','like','%' .$filterParameters['payment_method_name'].'%');

            })

            ->when(isset($filterParameters['transaction_no']), function ($query) use ($filterParameters) {
                $query->where('transaction_no', $filterParameters['transaction_no']);
            })
            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            })
            ->when(isset($filterParameters['description']), function ($query) use ($filterParameters) {
                $query->where('description', 'like', '%' . $filterParameters['description'] . '%' );
            })

            ->when(isset($filterParameters['transaction_from']), function ($query) use ($filterParameters) {
                $query->whereDate('transaction_date', '>=', date('y-m-d', strtotime($filterParameters['transaction_from'])));

            })

            ->when(isset($filterParameters['transaction_to']), function ($query) use ($filterParameters) {
                $query->whereDate('transaction_date', '<=', date('y-m-d', strtotime($filterParameters['transaction_to'])));
            })

            ->when($amountCondition && isset($filterParameters['transaction_amount']),function ($query) use($filterParameters) {
                $query->where('transaction_amount', $filterParameters['amount_condition'], $filterParameters['transaction_amount']);
            })

            ->when(isset($filterParameters['created_from']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('y-m-d', strtotime($filterParameters['created_from'])));
            })
            ->when(isset($filterParameters['created_to']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['created_to'])));
            })
            ->orderBy('transaction_date', 'DESC')
            ->paginate(20);


        return $allBalanceReconciliationDetailByFilter;

    }
}




