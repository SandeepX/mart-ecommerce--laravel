<?php


namespace App\Modules\SalesManager\Helpers;



use App\Modules\SalesManager\Models\SMIManagerAttendance;

class SMIManagerAttendanceHelper
{
    public static function filterPaginatedSMIManagerAttendance($filterParameters)
    {
        $managerAttendance = SMIManagerAttendance::where('msmi_code',$filterParameters['msmi_code'])

            ->when(isset($filterParameters['from_date']), function ($query) use ($filterParameters) {
                $query->whereDate('attendance_date', '>=', date('y-m-d', strtotime($filterParameters['from_date'])));
            })

            ->when(isset($filterParameters['to_date']), function ($query) use ($filterParameters) {
                $query->whereDate('attendance_date', '<=', date('y-m-d', strtotime($filterParameters['to_date'])));
            })

            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : 20;
        $managerAttendance = $managerAttendance->latest()->paginate($paginateBy);
        return $managerAttendance;

    }

}

