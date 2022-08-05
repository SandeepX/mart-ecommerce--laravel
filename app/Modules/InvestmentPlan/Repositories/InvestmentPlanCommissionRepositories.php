<?php


namespace App\Modules\InvestmentPlan\Repositories;


use App\Modules\InvestmentPlan\Models\InvestmentPlanCommission;

class InvestmentPlanCommissionRepositories
{

    public function getAllInvestmentCommissionByIPCode($IPCode)
    {
        return InvestmentPlanCommission::where('investment_plan_code',$IPCode)->get();
    }

    public function findOrFailInvestmentCommissionByCode($IPCCode)
    {
        return InvestmentPlanCommission::where('ipc_code',$IPCCode)->firstOrFail();
    }

    public function store($validatedData)
    {
        return InvestmentPlanCommission::create($validatedData)->fresh();
    }

    public function update($investmentCommissionDetail, $validatedData)
    {
        return $investmentCommissionDetail->update($validatedData);
    }

    public function changeStatus($investmentPlanCommission)
    {
        return $investmentPlanCommission->update([
            'is_active' => !$investmentPlanCommission['is_active']
        ]);
    }

}
