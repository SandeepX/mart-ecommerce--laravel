<?php


namespace App\Modules\InvestmentPlan\Services;

use App\Modules\InvestmentPlan\Repositories\InvestmentInterestReleaseRepository;

use Illuminate\Support\Facades\DB;
use Exception;

class InvestmentPlanInterestReleaseService
{
    private $investmentInterestReleaseRepository;

    public function __construct(InvestmentInterestReleaseRepository $investmentInterestReleaseRepository)
    {
        $this->investmentInterestReleaseRepository = $investmentInterestReleaseRepository;
    }

    public function getAllInvestmentInterestReleaseByIPCode($IPCode)
    {
        try {
            return $this->investmentInterestReleaseRepository->getAllInvestmentInterestReleaseByIPCode($IPCode);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function findOrFailInvestmentInterestReleaseByCode($IPIRCode)
    {
        return $this->investmentInterestReleaseRepository->findOrFailInvestmentInterestReleaseByIPCode($IPIRCode);
    }

    public function findOrFailActiveInvestmentInterestReleaseByIPCode($IPIRCode)
    {
        $investmentPlanRelease = $this->investmentInterestReleaseRepository->findOrFailActiveInvestmentInterestReleaseByIPCode($IPIRCode);
        if(!$investmentPlanRelease){
            throw new Exception('Investment Plan Not found !');
        }
        return $investmentPlanRelease;
    }

    public function storeInvestmentPlanInterestRelease($validatedData)
    {
        DB::beginTransaction();
        try {
            $investmentInterestRelease = $this->investmentInterestReleaseRepository->store($validatedData);
            DB::commit();
            return $investmentInterestRelease;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateInvestmenInterestReleaseOption($validatedData, $IPIRCode)
    {
        DB::beginTransaction();
        try {
            if (!isset($validatedData['is_active'])) {
                $validatedData['is_active'] = 0;
            }
            $investmentInterestReleaseDetail = $this->findOrFailInvestmentInterestReleaseByCode($IPIRCode);
            $investmentInterestRelease = $this->investmentInterestReleaseRepository->update($investmentInterestReleaseDetail, $validatedData);

            DB::commit();
            return $investmentInterestRelease;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeInvestmentInterestReleaseStatus($IPIRCode)
    {
        DB::beginTransaction();
        try {
            $investmentInterestRelease = $this->findOrFailInvestmentInterestReleaseByCode($IPIRCode);
            $changeInvestmentInterestReleaseStatus = $this->investmentInterestReleaseRepository->changeInvestmentInterestReleaseStatus($investmentInterestRelease);

            DB::commit();
            return $changeInvestmentInterestReleaseStatus;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}

