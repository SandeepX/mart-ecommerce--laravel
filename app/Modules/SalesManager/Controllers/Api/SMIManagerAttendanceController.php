<?php


namespace App\Modules\SalesManager\Controllers\Api;

use App\Modules\SalesManager\Helpers\SMIManagerAttendanceHelper;
use App\Modules\SalesManager\Resources\SIMManagerAttendance\SIMManagerAttedanceCollection;
use App\Modules\SalesManager\Services\ManagerSMIService;
use Exception;
use Illuminate\Http\Request;


class SMIManagerAttendanceController
{
    public $managerSMIService;

    public function __construct(ManagerSMIService $managerSMIService){
        $this->managerSMIService = $managerSMIService;
    }


    public function showSMIManagerAttendanceDetail(Request $request)
    {

        try {
            $filterParameters = [
                'status' => $request->get('status'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'records_per_page' => $request->get('records_per_page'),
            ];

           $smiManagerDetail = $this->managerSMIService
               ->findManagerSMIDetailByManagerCode(getAuthManagerCode());
           if(!$smiManagerDetail){
               throw new Exception('Data Not found',404);
           }
            $filterParameters['msmi_code'] = $smiManagerDetail->msmi_code;

            $attendanceDetail = SMIManagerAttendanceHelper::filterPaginatedSMIManagerAttendance($filterParameters);

            if ($attendanceDetail) {
                $attendanceDetail = new SIMManagerAttedanceCollection($attendanceDetail);
            }
            return sendSuccessResponse('Data Found', $attendanceDetail);

        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}



