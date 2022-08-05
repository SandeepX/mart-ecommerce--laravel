<?php


namespace App\Modules\InvestmentPlan\Helper;


use App\Modules\InvestmentPlan\Models\InvestmentPlanType;

class InvestmentPlanTypeFilterHelper
{
    public static function getAllInvestmentPlanTypeByFilter($filterParameters)
    {
        $allInvestmentPlan = InvestmentPlanType::when(isset($filterParameters['name']), function ($query) use ($filterParameters) {
            $query->where('name', 'like', '%' . $filterParameters['name'] . '%');
        })

            ->when(isset($filterParameters['is_active']), function ($query) use ($filterParameters) {
                $query->where('is_active', $filterParameters['is_active']);
            })

             ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return $allInvestmentPlan;

    }
}


