<?php


namespace App\Modules\InvestmentPlan\Repositories;

use App\Modules\InvestmentPlan\Models\InvestmentPlanType;

class InvestmentPlanTypeRepository
{
    public function getAllInvestmentPlanType()
    {
        return InvestmentPlanType::paginate();
    }

    public function getAllActiveInvestmentPlanType($select='*')
    {
        return InvestmentPlanType::where('is_active',1)
        ->select($select)
        ->get();
    }

    public function findOrfailInvestmentPlanTypeByCode($IPTCode)
    {
        return InvestmentPlanType::where('ip_type_code',$IPTCode)->firstOrFail();
    }

    public function store($validateData)
    {
        return InvestmentPlanType::create($validateData)->fresh();
    }

    public function update($investmentPlanDetail, $validatedData)
    {
        return $investmentPlanDetail->update($validatedData);
    }

    public function changeInvestmentPlanTypeStatus($investmentPlanType)
    {
        return $investmentPlanType->update([
            'is_active' => !$investmentPlanType['is_active']
        ]);
    }

}
