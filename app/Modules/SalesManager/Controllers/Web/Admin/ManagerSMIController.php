<?php


namespace App\Modules\SalesManager\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\SalesManager\Helpers\ManagerSMIHelper;
use App\Modules\SalesManager\Models\ManagerSMI;
use App\Modules\SalesManager\Requests\ManagerSMI\ManagerSMIAllowEditUpdateRequest;
use App\Modules\SalesManager\Requests\ManagerSMI\ManagerSMIUpdateStatusRequest;
use App\Modules\SalesManager\Services\ManagerSMIService;
use Exception;
use Illuminate\Http\Request;

class ManagerSMIController extends BaseController
{
    public $title = 'Manager SMI';
    public $base_route = 'admin.manager-smi';
    public $sub_icon = 'file';
    public $module = 'SalesManager::';
    private $view = 'admin.manager-smi.';

    public $managerSMIService;

    public function __construct(ManagerSMIService $managerSMIService){
        $this->managerSMIService = $managerSMIService;
    }


    public function index(Request $request)
    {
        try{
            $filterParameters = [
                'name' => $request->get('name'),
                'status' => $request->get('status'),
                'manager_phone_no' => $request->get('phoneNumber'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
            ];

            $managerSMI = ManagerSMIHelper::filterPaginatedManagerSMI($filterParameters,10);
            $status =  ManagerSMI::STATUS;

            return view(Parent::loadViewData($this->module . $this->view . 'index'),
             compact('managerSMI',
                 'status',
                 'filterParameters')
            );
        }catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleStatus($msmi_code)
    {
        try{
            $status = $this->managerSMIService->toggleStatus($msmi_code);
            return redirect()->route('admin.manager-smi.index')
                ->with('success','Status changed Successfully');
        }catch(Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }

    public function showDetail($msmi_code)
    {
        try{
            $with = ['managerLinks','manager'];
            $managerSMIDetail = $this->managerSMIService->getManagerSMIDetailByCode($msmi_code,$with);
            $managerDocs = !is_null($managerSMIDetail->managerDocs)?$managerSMIDetail->managerDocs:[];
            $status =  ManagerSMI::STATUS;

            return view(Parent::loadViewData($this->module . $this->view . 'show'),
                compact('managerSMIDetail','managerDocs','status')
            );
        }catch(Exception $e){
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function changeStatusOfManagerSMI(ManagerSMIUpdateStatusRequest $request,$msmi_code)
    {
        try{
            $validatedData = $request->validated();
            $this->managerSMIService->changeStatus($validatedData,$msmi_code);
            return redirect()->back()
                ->with('success','Status Changed TO ' .ucfirst($validatedData['status']).' Status Successfully');
        }catch(Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }

    public function toggleEditStatus(ManagerSMIAllowEditUpdateRequest $request,$msmi_code)
    {
        try{
            $validatedData = $request->validated();
            $editStatus = $this->managerSMIService->toggleEditStatus($validatedData,$msmi_code);
            return redirect()->back()
                ->with('success','Allow Edit Status changed Successfully');
        }catch(Exception $e){
            return redirect()->back()->with('danger',$e->getMessage());
        }
    }

}
