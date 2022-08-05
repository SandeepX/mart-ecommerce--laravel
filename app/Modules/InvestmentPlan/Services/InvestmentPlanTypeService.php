<?php


namespace App\Modules\InvestmentPlan\Services;
use App\Modules\InvestmentPlan\Models\InvestmentPlan;
use App\Modules\InvestmentPlan\Repositories\InvestmentPlanTypeRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class InvestmentPlanTypeService
{
    public $investmentTypeRepo;

    public function __construct(InvestmentPlanTypeRepository $investmentTypeRepo){
        $this->investmentTypeRepo = $investmentTypeRepo;
    }

    public function getAllInvestmentPlanTypes()
    {
        try{
            return $this->investmentTypeRepo->getAllInvestmentPlanType();
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getAllActiveInvestmentPlanTypes($select)
    {
        try{
            return $this->investmentTypeRepo->getAllActiveInvestmentPlanType($select);
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getInvestmentPlanTypeByCode($IPTCode)
    {
        try{
            return $this->investmentTypeRepo->findOrfailInvestmentPlanTypeByCode($IPTCode);
        }catch(Exception $ex){
            throw $ex;
        }
    }

    public function storeInvestmentType($validatedData)
    {
        DB::beginTransaction();
        try{
            $investmentPlanType = $this->investmentTypeRepo->store($validatedData);
            DB::commit();
            return $investmentPlanType;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateInvestmentPlan($validatedData, $IPTCode)
    {
        DB::beginTransaction();
        try{
            if(!isset($validatedData['is_active'])){
                $validatedData['is_active'] = 0;
            }
            $investmentTypeDetail = $this->getInvestmentPlanTypeByCode($IPTCode);
            $investmentTypeUpdate = $this->investmentTypeRepo->update($investmentTypeDetail,$validatedData);
            DB::commit();
            return $investmentTypeUpdate;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeInvestmentStatus($IPTCode)
    {
        DB::beginTransaction();
        try{
            $investmentPlanType = $this->getInvestmentPlanTypeByCode($IPTCode);
            $changeInvestmentTypeStatus = $this->investmentTypeRepo->changeInvestmentPlanTypeStatus($investmentPlanType);

            DB::commit();
            return $changeInvestmentTypeStatus;

        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

}
