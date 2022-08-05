<?php

namespace App\Modules\InvestmentPlan\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\InvestmentPlan\Helper\InvestmentPlanFilterHelper;
use App\Modules\InvestmentPlan\Requests\InvestmentPlanRequest;
use App\Modules\InvestmentPlan\Services\InvestmentPlanTypeService;
use App\Modules\InvestmentPlan\Services\InvestmentService;
use Illuminate\Http\Request;
use Exception;


class InvestmentPlanController extends BaseController
{
    public $title = 'Investment plan';
    public $base_route = 'admin.investment';
    public $sub_icon = 'file';
    public $module = 'InvestmentPlan::';
    public $view = 'investment-plan.admin.';

    private $investmentService;
    private $ipTypeService;

    public function __construct(
        InvestmentService $investmentService,
        InvestmentPlanTypeService $ipTypeService
    )
    {
        $this->middleware('permission:View Investment Plan Lists', ['only' => ['index']]);
        $this->middleware('permission:Create Investment Plan', ['only' => ['create', 'store']]);
        $this->middleware('permission:Show Investment Plan', ['only' => ['show']]);
        $this->middleware('permission:Update Investment Plan', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Change Investment Plan Status', ['only' => ['changeInvestmentStatus']]);


        $this->investmentService = $investmentService;
        $this->ipTypeService = $ipTypeService;

    }

    public function index(Request $request)
    {
        try{
            $filterParameters = [
                'name' =>$request->get('name'),
                'investment_type_name' =>$request->get('investment_type_name'),
                'maturity_period_condition' =>$request->get('maturity_period_condition'),
                'maturity_period' => $request->get('maturity_period'),
                'amount_condition' => $request->get('amount_condition'),
                'target_capital' => $request->get('target_capital'),
                'is_active'=> $request->get('is_active'),
            ];
            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $allInvestmentPlan = InvestmentPlanFilterHelper::getAllInvestmentPlanByFilter($filterParameters);

            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('allInvestmentPlan',
                    'filterParameters',
                    'amountConditions'
                )
            );

        }catch(Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }


    public function create()
    {
        $getAllIPTypes = $this->ipTypeService->getAllActiveInvestmentPlanTypes($select=['ip_type_code','name']);

        return view(Parent::loadViewData($this->module . $this->view . 'create'),
            compact('getAllIPTypes')
        );
    }


    public function store(InvestmentPlanRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $investmentPlan = $this->investmentService->storeInvestmentPlan($validatedData);
            return redirect()->route('admin.investment.index')->with('success',$this->title . ':  Created Successfully');

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage())->withInput();
        }

    }


    public function show($IPCode)
    {
        try{
            $investmentDetail = $this->investmentService->getInvestmentPlanByCode($IPCode);
            return view(Parent::loadViewData($this->module . $this->view . 'show'),
                compact('investmentDetail')
            );
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function edit($IPCode)
    {
        try{
            $getAllIPTypes = $this->ipTypeService->getAllActiveInvestmentPlanTypes($select=['ip_type_code','name']);
            $investmentDetail = $this->investmentService->getInvestmentPlanByCode($IPCode);
            return view(Parent::loadViewData($this->module . $this->view . 'edit'),
                compact('investmentDetail',
                    'getAllIPTypes'
                )
            );

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function update(InvestmentPlanRequest $request,$IPCode)
    {
        try{
            $validatedData = $request->validated();
            $investmentPlan = $this->investmentService->updateInvestmentPlan($validatedData,$IPCode);
            return redirect()->route('admin.investment.index')->with('success',$this->title.' '. $IPCode. ' :  updated Successfully');

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }

    }

    public function changeInvestmentStatus($IPCode)
    {
        try{
            $investmentPlan = $this->investmentService->changeInvestmentStatus($IPCode);
            return redirect()->route('admin.investment.index')->with('success','Status changed Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function changeInvestmentPlanDisplayOrder(Request $request){
        try{
            $sortOrdersToChange = $request->sort_order;
            $updateStatus = $this->investmentService->changeInvestmentPlanDisplayOrder($sortOrdersToChange);
            return sendSuccessResponse('Display Order Updated');
        }catch(\Exception $exception){
            return sendErrorResponse('Sorry ! Could not update display order');
        }
    }
}


