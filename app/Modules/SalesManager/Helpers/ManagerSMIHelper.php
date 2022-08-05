<?php


namespace App\Modules\SalesManager\Helpers;


use App\Modules\SalesManager\Models\ManagerSMI;

class ManagerSMIHelper
{
    public static function filterPaginatedManagerSMI($filterParameters,$paginateBy,$with=[])
    {
        $managerSMI = ManagerSMI::with($with)
            ->when(isset($filterParameters['name']),function ($query) use ($filterParameters){
                $query->whereHas('manager', function ($query) use ($filterParameters) {
                    $query->where('manager_name' ,'like', '%' . $filterParameters['name'] . '%');
                });
            })
            ->when(isset($filterParameters['manager_phone_no']),function ($query) use ($filterParameters){
                $query->whereHas('manager', function ($query) use ($filterParameters) {
                    $query->where('manager_phone_no' ,$filterParameters['manager_phone_no']);
                });
            })

            ->when(isset($filterParameters['from_date']),function ($query) use($filterParameters){
                $query->whereDate('created_at','>=',date('y-m-d',strtotime($filterParameters['from_date'])));

            })

            ->when(isset($filterParameters['to_date']),function ($query) use($filterParameters) {
                $query->whereDate('created_at', '<=', date('y-m-d', strtotime($filterParameters['to_date'])));
            })

            ->when(isset($filterParameters['status']),function ($query) use ($filterParameters){
                $query->where('status',$filterParameters['status']);
            });

//        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $managerSMI = $managerSMI->latest()->paginate(ManagerSMI::RECORDS_PER_PAGE);
        return $managerSMI;
    }




}
