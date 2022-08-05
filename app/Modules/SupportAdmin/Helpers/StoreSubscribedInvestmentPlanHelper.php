<?php


namespace App\Modules\SupportAdmin\Helpers;


use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;

class StoreSubscribedInvestmentPlanHelper
{

    public static function getAllStoreInvestmentPlanSubscribedByFilter($filterParameters)
    {

        $amountCondition = isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'], ['>', '<', '>=', '<=', '=']) ? true : false;
        $interestRateCondition = isset($filterParameters['interest_rate_condition']) && in_array($filterParameters['interest_rate_condition'], ['>', '<', '>=', '<=', '=']) ? true : false;

        $allInvestmentPlanSubscribed = InvestmentPlanSubscription::where('investment_holder_id',$filterParameters['investment_holder_id'])

            ->when(isset($filterParameters['investment_plan_name']), function ($query) use ($filterParameters) {
                $query->where('investment_plan_name', 'like', '%' . $filterParameters['investment_plan_name'] . '%');
            })

            ->when($amountCondition && isset($filterParameters['invested_amount']), function ($query) use ($filterParameters) {
                $query->where('invested_amount', $filterParameters['amount_condition'], $filterParameters['invested_amount']);
            })

            ->when(isset($filterParameters['maturity_date_from']), function ($query) use ($filterParameters) {
                $query->whereDate('maturity_date','>=',date('y-m-d',strtotime($filterParameters['maturity_date_from'])));
            })

            ->when(isset($filterParameters['maturity_date_to']), function ($query) use ($filterParameters) {
                $query->whereDate('maturity_date','<=',date('y-m-d',strtotime($filterParameters['maturity_date_to'])));
            })

            ->when($interestRateCondition && isset($filterParameters['interest_rate']), function ($query) use ($filterParameters) {
                $query->where('interest_rate', $filterParameters['interest_rate_condition'],$filterParameters['interest_rate']);
            })

            ->when(isset($filterParameters['is_active']), function ($query) use ($filterParameters) {
                $query->where('is_active', $filterParameters['is_active']);
            })

            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            })

            ->when(isset($filterParameters['referred_by']), function ($query) use ($filterParameters) {
                $query->whereHas('user', function ($query) use ($filterParameters) {
                    $query->where('name', 'like', '%' . $filterParameters['referred_by'] . '%');
                });
            })

            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return $allInvestmentPlanSubscribed;

    }

}
