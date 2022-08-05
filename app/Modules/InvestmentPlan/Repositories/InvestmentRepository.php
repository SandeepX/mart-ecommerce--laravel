<?php


namespace App\Modules\InvestmentPlan\Repositories;


use App\Modules\InvestmentPlan\Models\InvestmentPlan;


class InvestmentRepository
{
    public function getAllInvestmentPlan()
    {
        return InvestmentPlan::orderBy('created_at','DESC')->paginate(20);
    }

    public function findorFailInvestmentPlanByCode($IPCode)
    {
        return InvestmentPlan::where('investment_plan_code',$IPCode)->firstOrFail();
    }

    public function getActiveInvestmentPlanByCode($IPCode)
    {
        return InvestmentPlan::where('investment_plan_code',$IPCode)
            ->where('is_active',1)
            ->first();
    }

    public function getAllActiveInvestmentPlan($with,$select='*')
    {
        $investmentPlans = InvestmentPlan::whereHas($with)
            ->where('is_active',1)
            ->select($select)
            ->orderBy('created_at','DESC')
            ->paginate(10);

        return $investmentPlans;
    }

    public function store($validatedData)
    {
        return InvestmentPlan::create($validatedData)->fresh();
    }

    public function update($investmentPlanDetail,$validatedData)
    {
        return $investmentPlanDetail->update($validatedData);
    }

    public function changeInvestmentPlanStatus($investmentPlan)
    {
        return $investmentPlan->update([
            'is_active' => !$investmentPlan['is_active']
        ]);
    }

}
