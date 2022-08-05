<?php


namespace App\Modules\InvestmentPlan\Repositories;


use App\Modules\InvestmentPlan\Models\InvestmentInterestRelease;

class InvestmentInterestReleaseRepository
{

    public function getAllInvestmentInterestReleaseByIPCode($IPCode)
    {
        return InvestmentInterestRelease::where('investment_plan_code',$IPCode)->get();
    }

    public function findOrFailActiveInvestmentInterestReleaseByIPCode($IPIRCode)
    {
        return InvestmentInterestRelease::where('ipir_option_code',$IPIRCode)
            ->where('is_active',1)
            ->first();
    }

    public function findOrFailInvestmentInterestReleaseByIPCode($IPIRCode)
    {
        return InvestmentInterestRelease::where('ipir_option_code',$IPIRCode)
            ->firstOrFail();
    }

    public function store($validatedData)
    {
        return InvestmentInterestRelease::create($validatedData)->fresh();
    }

    public function update($investmentInterestReleaseDetail, $validatedData)
    {
        return $investmentInterestReleaseDetail->update($validatedData);
    }

    public function changeInvestmentInterestReleaseStatus($investmentInterestRelease)
    {
        return $investmentInterestRelease->update([
            'is_active' => !$investmentInterestRelease['is_active']
        ]);
    }


}
