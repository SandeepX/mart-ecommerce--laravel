<?php


namespace App\Modules\InvestmentPlan\Helper;


use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;

class InvestmentPlanSubscriptionHelper
{

    public static function getAllInvestmentPlanSubcribedGroupBy($filterParameters)
    {
        $allInvestmentPlanSubscribed = InvestmentPlanSubscription::when(isset($filterParameters['investment_plan_name']), function ($query) use ($filterParameters) {
            $query->where('investment_plan_name', 'like', '%' . $filterParameters['investment_plan_name'] . '%');
        })
            ->groupBy('investment_plan_name')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return $allInvestmentPlanSubscribed;

    }

    public static function getAllInvestmentPlanSubscribedByFilter($filterParameters)
    {

        $amountCondition = isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'], ['>', '<', '>=', '<=', '=']) ? true : false;
        $interestRateCondition = isset($filterParameters['interest_rate_condition']) && in_array($filterParameters['interest_rate_condition'], ['>', '<', '>=', '<=', '=']) ? true : false;

        $allInvestmentPlanSubscribed = InvestmentPlanSubscription::where('investment_plan_code',$filterParameters['ip_code'])

        ->when(isset($filterParameters['investment_plan_name']), function ($query) use ($filterParameters) {
            $query->where('investment_plan_name', 'like', '%' . $filterParameters['investment_plan_name'] . '%');
        })
            ->when(isset($filterParameters['investment_holder_type']), function ($query) use ($filterParameters) {
                $query->where('investment_holder_type', $filterParameters['investment_holder_type']);
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
            ->when(isset($filterParameters['payment_mode']),function ($query) use ($filterParameters){
                $query->where('payment_mode',$filterParameters['payment_mode']);
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

    public static function getSubscriptionHolderName(InvestmentPlanSubscription $investmentSubscription)
    {
        $holderName = '';
        if($investmentSubscription->investment_holder_type == 'store'){
            $subscriptionHolderName = $investmentSubscription->investmentSubscriptionable->store_name;
        }
        if($investmentSubscription->investment_holder_type == 'vendor'){
            $subscriptionHolderName = $investmentSubscription->investmentSubscriptionable->vendor_name;
        }
        if($investmentSubscription->investment_holder_type == 'manager'){
            $subscriptionHolderName = $investmentSubscription->investmentSubscriptionable->manager_name;
        }
        return $subscriptionHolderName;
    }

}
