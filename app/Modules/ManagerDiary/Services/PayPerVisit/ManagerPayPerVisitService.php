<?php

namespace App\Modules\ManagerDiary\Services\PayPerVisit;

use App\Modules\ManagerDiary\Repositories\ManagerPayPerVisitRepository;
use Exception;

class ManagerPayPerVisitService
{
    private $managerPayPerVisitRepository;
    public function __construct(ManagerPayPerVisitRepository $managerPayPerVisitRepository)
    {
      $this->managerPayPerVisitRepository = $managerPayPerVisitRepository;
    }

    public function findOrFailPayPerVisitByCode($payPerVisitCode,$with =[]){
     return $this->managerPayPerVisitRepository->findOrFailManagerPayPerVisitByCode($payPerVisitCode,$with);
    }

    public function saveManagerPayPerVisitDetails($validatedData){
        try{
            $authUserCode = getAuthUserCode();
            $validatedData['created_by'] = $authUserCode;
            $validatedData['updated_by'] = $authUserCode;
            $managerPayPerVisit =  $this->managerPayPerVisitRepository->store($validatedData);
            return $managerPayPerVisit;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function updateManagerPayPerVisitDetails($managerPayPerVisitCode,$validatedData){
        try{
            $managerPayPerVisit = $this->managerPayPerVisitRepository->findOrFailManagerPayPerVisitByCode($managerPayPerVisitCode);
            $validatedData['updated_by'] = getAuthUserCode();
            $this->managerPayPerVisitRepository->update($managerPayPerVisit,$validatedData);
            return $managerPayPerVisit;
        }catch (Exception $exception){
            throw $exception;
        }
    }

}
