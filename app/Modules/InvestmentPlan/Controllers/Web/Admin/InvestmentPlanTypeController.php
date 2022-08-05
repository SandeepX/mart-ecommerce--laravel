<?php


namespace App\Modules\InvestmentPlan\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\InvestmentPlan\Helper\InvestmentPlanTypeFilterHelper;
use App\Modules\InvestmentPlan\Requests\InvestmentPlanTypeRequest;
use App\Modules\InvestmentPlan\Services\InvestmentPlanTypeService;
use Illuminate\Http\Request;
use Exception;


class InvestmentPlanTypeController extends BaseController
{
    public $title = 'Investment plan types';
    public $base_route = 'admin.investment-type';
    public $sub_icon = 'file';
    public $module = 'InvestmentPlan::';
    public $view = 'investment-plan-type.admin.';

    public $investmentTypeService;

    public function __construct(InvestmentPlanTypeService $investmentTypeService)
    {
        $this->middleware('permission:View Investment Plan Types List', ['only' => ['index']]);
        $this->middleware('permission:Create Investment Plan Types', ['only' => ['create', 'store']]);
        $this->middleware('permission:Show Investment Plan Types', ['only' => ['show']]);
        $this->middleware('permission:Update Investment Plan Types', ['only' => ['edit', 'update']]);


        $this->investmentTypeService = $investmentTypeService;

    }

    public function index(Request $request)
    {
        try {
            $filterParameters = [
                'name' =>$request->get('name'),
                'is_active'=> $request->get('is_active'),
            ];
            //$allInvestmentPlanTypes = $this->investmentTypeService->getAllInvestmentPlanTypes();
            $allInvestmentPlanTypes = InvestmentPlanTypeFilterHelper::getAllInvestmentPlanTypeByFilter($filterParameters);
             return view(Parent::loadViewData($this->module . $this->view . 'index'),
             compact('allInvestmentPlanTypes','filterParameters')
             );
        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }


    public function create()
    {
        return view(Parent::loadViewData($this->module . $this->view . 'create'));
    }


    public function store(InvestmentPlanTypeRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $investmentPlanType = $this->investmentTypeService->storeInvestmentType($validatedData);
            return redirect()->route('admin.investment-type.index')->with('success', $this->title . ':  Created Successfully');

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show($IPTCode)
    {
        try {
            $investmentTypeDetail = $this->investmentTypeService->getInvestmentPlanTypeByCode($IPTCode);
            return view(Parent::loadViewData($this->module . $this->view . 'show'), compact('investmentTypeDetail'));

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function edit($IPTCode)
    {
        try {
            $investmentTypeDetail = $this->investmentTypeService->getInvestmentPlanTypeByCode($IPTCode);
            return view(Parent::loadViewData($this->module . $this->view . 'edit'), compact('investmentTypeDetail'));

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(InvestmentPlanTypeRequest $request,$IPTCode)
    {
        try {
            $validatedData = $request->validated();
            $investmentPlanType = $this->investmentTypeService->updateInvestmentPlan($validatedData, $IPTCode);
            return redirect()->route('admin.investment-type.index')->with('success', $this->title . ' ' . $IPTCode . ' :  updated Successfully');

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }

    public function changeInvestmentTypeStatus($IPTCode)
    {
        try {
            $investmentPlan = $this->investmentTypeService->changeInvestmentStatus($IPTCode);
            return redirect()->route('admin.investment-type.index')->with('success', 'Status Changed Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}



