<?php


namespace App\Modules\InvestmentPlan\Services;


use App\Modules\InvestmentPlan\Repositories\InvestmentPlanCommissionRepositories;
use Exception;
use Illuminate\Support\Facades\DB;

class InvestmentPlanCommissionService
{
    private $investmentCommissionRepo;

    public function __construct(InvestmentPlanCommissionRepositories $investmentCommissionRepo)
    {
        $this->investmentCommissionRepo = $investmentCommissionRepo;
    }

    public function getAllInvestmentPlanCommissionByIPCode($IPCode)
    {
        return $this->investmentCommissionRepo->getAllInvestmentCommissionByIPCode($IPCode);
    }

    public function findOrFailInvestmentPlanCommissionByCode($IPCCode)
    {
        return $this->investmentCommissionRepo->findOrFailInvestmentCommissionByCode($IPCCode);
    }

    public function storeInvestmentPlanCommission($validatedData)
    {
        DB::beginTransaction();
        try {
            $investmentPlanCommission = $this->investmentCommissionRepo->store($validatedData);
            DB::commit();
            return $investmentPlanCommission;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateInvestmentCommission($validatedData, $IPCCode)
    {
        DB::beginTransaction();
        try {
            if (!isset($validatedData['is_active'])) {
                $validatedData['is_active'] = 0;
            }
            $investmentCommissionDetail = $this->findOrFailInvestmentPlanCommissionByCode($IPCCode);
            $investmentCommission = $this->investmentCommissionRepo->update($investmentCommissionDetail, $validatedData);

            DB::commit();
            return $investmentCommission;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeInvestmentCommissionStatus($IPCCode)
    {
        DB::beginTransaction();
        try {
            $investmentPlanCommission = $this->findOrFailInvestmentPlanCommissionByCode($IPCCode);
            $changeInvestmentCommissionStatus = $this->investmentCommissionRepo->changeStatus($investmentPlanCommission);

            DB::commit();
            return $changeInvestmentCommissionStatus;

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
