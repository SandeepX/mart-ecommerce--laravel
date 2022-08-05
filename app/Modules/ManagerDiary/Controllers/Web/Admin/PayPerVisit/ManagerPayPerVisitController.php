<?php

namespace App\Modules\ManagerDiary\Controllers\Web\Admin\PayPerVisit;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\ManagerDiary\Helpers\ManagerPayPerVisitFilter;
use App\Modules\ManagerDiary\Models\ManagerPayPerVisit;
use App\Modules\ManagerDiary\Requests\PayPerVisit\CreateManagerPayPerVisitRequest;
use App\Modules\ManagerDiary\Requests\PayPerVisit\UpdateManagerPayPerVisitRequest;
use App\Modules\ManagerDiary\Services\PayPerVisit\ManagerPayPerVisitService;
use App\Modules\SalesManager\Services\SalesManagerService;
use Exception;
use Illuminate\Http\Request;

class ManagerPayPerVisitController extends BaseController
{
    public $title = 'Manager Pay Per Visits';
    public $base_route = 'admin.manager-pay-per-visits';
    public $sub_icon = 'home';
    public $module = 'ManagerDiary::';
    private $view = 'admin.pay-per-visit.';

    private $salesManagerService;
    private $managerPayPerVisitService;

    public function __construct(
        SalesManagerService $salesManagerService,
        ManagerPayPerVisitService  $managerPayPerVisitService
    ){
        $this->salesManagerService = $salesManagerService;
        $this->managerPayPerVisitService = $managerPayPerVisitService;
    }

    public function index(Request $request){
        try{
            $filterParameters = [
                'manager_name' => $request->get('manager_name'),
                'amount_condition' => $request->get('amount_condition'),
                'amount' => $request->get('amount')
            ];
            $priceConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $paginateBy = ManagerPayPerVisit::PAGINATE_BY;
            $with = ['manager'];
            $managerPayPerVisits = ManagerPayPerVisitFilter::filterPaginatedManagerPayPerVisit($filterParameters,$paginateBy,$with);
            return view(parent::loadViewData($this->module.$this->view.'index'),compact('managerPayPerVisits','filterParameters','priceConditions'));
        }catch (Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function create(){
        try{
            $managers = $this->salesManagerService->getAllManagersLists();
            return view(parent::loadViewData($this->module.$this->view.'create'),compact('managers'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function store(CreateManagerPayPerVisitRequest $request){
        try{
            $validatedData =  $request->validated();
            $managerPayPerVisit =  $this->managerPayPerVisitService->saveManagerPayPerVisitDetails($validatedData);
            return redirect()->back()->with('success', $this->title .' Created Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function edit($managerPayPerVisitCode){
        try{
            $managerPayPerVisit = $this->managerPayPerVisitService->findOrFailPayPerVisitByCode($managerPayPerVisitCode);
            return view(parent::loadViewData($this->module.$this->view.'edit'),compact('managerPayPerVisit'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function update(UpdateManagerPayPerVisitRequest $request,$managerPayPerVisitCode){
        try{
            $validatedData = $request->validated();
            $this->managerPayPerVisitService->updateManagerPayPerVisitDetails($managerPayPerVisitCode,$validatedData);
            return redirect()->back()->with('success', $this->title .' Updated Successfully');
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }


}
