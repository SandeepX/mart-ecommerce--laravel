<?php


namespace App\Modules\SalesManager\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\SalesManager\Models\SMIManagerAttendance;
use Carbon\Carbon;

class ManangerAttendanceRepository extends RepositoryAbstract
{

    public function getManagerPastAttandanceDetailBySMICode($MSMICode)
    {
       return SMIManagerAttendance::where('msmi_code',$MSMICode)
           ->where('attendance_date','<',Carbon::today())
           ->latest()
           ->paginate(20);
    }

    public function findSMIManagerTodaysAttendanceByCode($MSMICode)
    {
        return SMIManagerAttendance::where('msmi_code',$MSMICode)
            ->where('attendance_date',Carbon::today())
            ->latest()
            ->first();
    }

    public function findManagerAttendanceDetailByAttendanceCode($attendance_code)
    {
        return SMIManagerAttendance::where('msmi_attendance_code',$attendance_code)->first();
    }

    public function storeAttedance($validatedData)
    {
        return SMIManagerAttendance::create($validatedData)->fresh();
    }

    public function updateAttedance($validatedData,$managersTodayAttedanceDetail)
    {
        return $managersTodayAttedanceDetail->update([
                'status'=>$validatedData['status'],
                'remarks'=>$validatedData['remarks']
            ]);

    }

}
