<?php


namespace App\Modules\InvestmentPlan\Helper;


use App\Modules\InvestmentPlan\Models\InvestmentPlan;

class InvestmentPlanFilterHelper
{
    public static function getAllInvestmentPlanByFilter($filterParameters)
    {
        $amountCondition = isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'], ['>', '<', '>=', '<=', '=']) ? true : false;
        $maturityPeriodCondition = isset($filterParameters['maturity_period_condition']) && in_array($filterParameters['maturity_period_condition'], ['>', '<', '>=', '<=', '=']) ? true : false;

        $allInvestmentPlan = InvestmentPlan::when(isset($filterParameters['name']), function ($query) use ($filterParameters) {
            $query->where('name', 'like', '%' . $filterParameters['name'] . '%');
        })

            ->when(isset($filterParameters['investment_type_name']), function ($query) use ($filterParameters) {
                $query->whereHas('investmentType', function ($query) use ($filterParameters) {
                    $query->where('name', 'like', '%' . $filterParameters['investment_type_name'] . '%');
                });
            })

            ->when($maturityPeriodCondition && isset($filterParameters['maturity_period']), function ($query) use ($filterParameters) {
                $query->where('maturity_period',$filterParameters['maturity_period_condition'], $filterParameters['maturity_period']);
            })
            ->when(isset($filterParameters['is_active']), function ($query) use ($filterParameters) {
                $query->where('is_active', $filterParameters['is_active']);
            })
            ->when($amountCondition && isset($filterParameters['target_capital']), function ($query) use ($filterParameters) {
                $query->where('target_capital', $filterParameters['amount_condition'], $filterParameters['target_capital']);
            })
//            ->orderBy('sort_order', 'ASC')
             ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return $allInvestmentPlan;

    }
}

