<?php


namespace App\Modules\SalesManager\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\SalesManager\Models\ManagerSMI;
use App\Modules\SalesManager\Models\SMIManagerAttendance;
use App\Modules\SalesManager\Requests\ManagerSMI\ManagerAttendanceCreateRequest;
use App\Modules\SalesManager\Requests\ManagerSMI\ManagerPastAttendanceUpdateRequest;
use App\Modules\SalesManager\Services\ManagerAttendanceService;
use App\Modules\SalesManager\Services\ManagerSMIService;
use Exception;
use Illuminate\Http\Request;

class ManagerAttendanceController extends BaseController
{
    public $title = 'Manager Attendace detail';
    public $base_route = 'admin.manager-smi';
    public $sub_icon = 'file';
    public $module = 'SalesManager::';
    private $view = 'admin.manager-smi.';

    public $managerAttendanceService;
    public $managerSMIService;

    public function __construct(ManagerAttendanceService $managerAttendanceService,
                                ManagerSMIService $managerSMIService
    )
    {
        $this->managerAttendanceService = $managerAttendanceService;
        $this->managerSMIService = $managerSMIService;
    }

    public function showAttendanceDetail($MSMICode)
    {
        try {
            $managerSMI = $this->managerSMIService->getManagerSMIDetailByCode($MSMICode);
            $managerAttedances = $this->managerAttendanceService->getManagerPastAttandanceDetailBySMICode($MSMICode);
            $managerTodaysAttendanceDetail = $this->managerAttendanceService->findSMIManagerTodaysAttendanceByCode($MSMICode);
            $status = SMIManagerAttendance::STATUS;
            return view(Parent::loadViewData($this->module . $this->view . 'attendance'),
                compact('MSMICode','managerSMI',
                    'managerAttedances',
                    'managerTodaysAttendanceDetail',
                    'status'
                )
            );
        }catch (Exception $ex) {
            return redirect()->back()->with('danger', $ex->getMessage());
        }
    }

    public function storeAttendaceOfSMI(ManagerAttendanceCreateRequest $request,$msmi_code)
    {
        try{
           $validatedData = $request->validated();
           $validatedData['msmi_code']= $msmi_code;
           $managerSMI = $this->managerSMIService->getManagerSMIDetailByCode($msmi_code);
           if($managerSMI->status !='approved'){
                throw new Exception('Attendance of smi manager cannot be taken since status
                    is in ' .ucfirst($managerSMI->status).' state',401);
           }
           $managerAttedance = $this->managerAttendanceService->saveAttendance($validatedData);
            return redirect()->back()
                ->with('success','Attendance '.$managerAttedance.' Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function updatePastAttendance(ManagerPastAttendanceUpdateRequest $request,$attendance_code)
    {
        try{
            $validatedData = $request->validated();
            $managerAttedance = $this->managerAttendanceService->updatePastAttendance($validatedData,$attendance_code);
            return redirect()->back()
                ->with('success','Attendance updated Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}

