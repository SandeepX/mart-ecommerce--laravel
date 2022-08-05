<?php

namespace App\Modules\ManagerDiary\Repositories;

use App\Modules\ManagerDiary\Models\ManagerPayPerVisit;
use Exception;

class ManagerPayPerVisitRepository
{

    public function findManagerPayPerVisitByCode($payPerVisitCode,$with=[]){
      return ManagerPayPerVisit::with($with)->where('manager_pay_per_visit_code',$payPerVisitCode)
                                ->first();
    }
    public function findOrFailManagerPayPerVisitByCode($payPerVisitCode,$with=[]){
         $managerPayPerVisit = $this->findManagerPayPerVisitByCode($payPerVisitCode,$with);
         if(!$managerPayPerVisit){
           throw new Exception('Manager Pay per visit not found :(');
         }
         return $managerPayPerVisit;
    }

    public function store($validatedData)
    {
        $managerPayPerVisit = ManagerPayPerVisit::create($validatedData);
        return $managerPayPerVisit->fresh();
    }

    public function update(ManagerPayPerVisit $managerPayPerVisit,$validatedData){
             $managerPayPerVisit->update($validatedData);
             return $managerPayPerVisit->refresh();
    }

}
