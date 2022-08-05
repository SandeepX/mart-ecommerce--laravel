<?php


namespace App\Modules\SalesManager\Services;
use App\Modules\SalesManager\Repositories\ManangerAttendanceRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ManagerAttendanceService
{

    private $managerAttendanceRepo;

    public function __construct(ManangerAttendanceRepository $managerAttendanceRepo)
    {
        $this->managerAttendanceRepo = $managerAttendanceRepo;
    }


    public function getManagerPastAttandanceDetailBySMICode($MSMICode)
    {
        try{
            $attendanceDetail = $this->managerAttendanceRepo->getManagerPastAttandanceDetailBySMICode($MSMICode);
            if(!$attendanceDetail){
                throw new Exception('Manager Attendance Detail Not Found',404);
            }
            return $attendanceDetail;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function findSMIManagerTodaysAttendanceByCode($MSMICode)
    {
        try{
            $attendanceDetail = $this->managerAttendanceRepo->findSMIManagerTodaysAttendanceByCode($MSMICode);
            return $attendanceDetail;
        }catch(Exception $e){
            throw $e;
        }

    }

    public function saveAttendance($validatedData)
    {
        DB::beginTransaction();
        try{
            $managersTodayAttedanceDetail = $this->managerAttendanceRepo
                ->findSMIManagerTodaysAttendanceByCode($validatedData['msmi_code']);
            if(!$managersTodayAttedanceDetail){
                $this->managerAttendanceRepo->storeAttedance($validatedData);
                $attendance = 'stored';

            }else{
                $this->managerAttendanceRepo->updateAttedance($validatedData,$managersTodayAttedanceDetail);
                $attendance = 'updated';
            }
            DB::commit();
            return $attendance;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updatePastAttendance($validatedData,$attendance_code)
    {
        DB::beginTransaction();
        try{
            $managersAttedanceDetail = $this->managerAttendanceRepo
                ->findManagerAttendanceDetailByAttendanceCode($attendance_code);
            if(!$managersAttedanceDetail){
                throw new Exception('Sorry ! Data not Found',404);
            }
            $this->managerAttendanceRepo->updateAttedance($validatedData,$managersAttedanceDetail);
            DB::commit();
            return true;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }





}
