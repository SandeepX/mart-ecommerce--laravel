<?php

namespace App\Modules\ManagerDiary\Helpers;

use App\Modules\ManagerDiary\Models\ManagerPayPerVisit;

class ManagerPayPerVisitFilter
{

    public static function filterPaginatedManagerPayPerVisit($filterParameters,$paginateBy=10,$with=[]){
        $amountCondition=isset($filterParameters['amount_condition']) && in_array($filterParameters['amount_condition'],['>','<', '>=','<=','='])? true:false;

        $managerPayPerVisits = ManagerPayPerVisit::with($with)
                              ->when(isset($filterParameters['manager_name']),function ($query) use ($filterParameters) {
                                  $query->whereHas('manager', function ($query) use ($filterParameters) {
                                      $query->where('manager_name', 'like', '%' . $filterParameters['manager_name'] . '%');
                                  });
                               })
                              ->when($amountCondition && isset($filterParameters['amount']),function ($query) use($filterParameters){
                                    $query->where('amount',$filterParameters['amount_condition'],$filterParameters['amount']);
                              });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;
        $managerPayPerVisits = $managerPayPerVisits->latest()->paginate($paginateBy);
        return $managerPayPerVisits;

    }

}
