<?php

namespace App\Modules\ManagerDiary\Helpers;

use App\Modules\ManagerDiary\Models\ManagerPayPerVisit;

class ManagerPayPerVisitHelper
{

    public static function getManagerPayPerVisit($managerCode){

        $payPerVisit = ManagerPayPerVisit::where('manager_code',$managerCode)
                                ->first();
        $payPerVisitAmount = 0;
        if($payPerVisit){
            $payPerVisitAmount = $payPerVisit->amount;
        }
        return $payPerVisitAmount;
    }
}
