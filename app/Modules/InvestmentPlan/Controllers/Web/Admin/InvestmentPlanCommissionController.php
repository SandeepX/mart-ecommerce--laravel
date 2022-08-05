<?php


namespace App\Modules\InvestmentPlan\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\InvestmentPlan\Requests\InvestmentPlanCommissionRequest;
use App\Modules\InvestmentPlan\Services\InvestmentPlanCommissionService;
use Exception;
use Illuminate\Http\Request;


class InvestmentPlanCommissionController extends BaseController
{
    public $title = 'Investment plan';
    public $base_route = 'admin.investment';
    public $sub_icon = 'file';
    public $module = 'InvestmentPlan::';
    public $view = 'investment-plan-commission.';

    private $investmentPlanCommissionService;

    public function __construct(InvestmentPlanCommissionService $investmentPlanCommissionService)
    {
        $this->middleware('permission:View Investment Plan Commission Lists', ['only' => ['show']]);
        $this->middleware('permission:Create Investment Plan Commission', ['only' => ['create', 'store']]);
        //$this->middleware('permission:Show Investment Plan Commission Detail', ['only' => ['show']]);
        $this->middleware('permission:Update Investment Plan Commission', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Change Investment Plan Commission Status', ['only' => ['changeInvestmentCommissionStatus']]);

        $this->investmentPlanCommissionService = $investmentPlanCommissionService;
    }

    public function show($IPCode)
    {
        try {
            $investmentCommissionDetail = $this->investmentPlanCommissionService->getAllInvestmentPlanCommissionByIPCode($IPCode);
            return view(Parent::loadViewData($this->module . $this->view . 'show'), compact('investmentCommissionDetail', 'IPCode'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create($IPCode)
    {
        return view(Parent::loadViewData($this->module . $this->view . 'create'), compact('IPCode'));
    }

    public function store(InvestmentPlanCommissionRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $investmentPlanCommission = $this->investmentPlanCommissionService->storeInvestmentPlanCommission($validatedData);
            return redirect()->back()->with('success', 'Investment Plan Commission Created Successfully');

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function edit($IPCCode)
    {
        try {
            $investmentCommissionDetail = $this->investmentPlanCommissionService->findOrFailInvestmentPlanCommissionByCode($IPCCode);
            return view(Parent::loadViewData($this->module . $this->view . 'edit'), compact('investmentCommissionDetail'));

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(InvestmentPlanCommissionRequest $request, $IPCCode)
    {
        try {
            $validatedData = $request->validated();
            $investmentCommission = $this->investmentPlanCommissionService->updateInvestmentCommission($validatedData, $IPCCode);
            return redirect()->back()->with('success', 'Investment Commission ' . $IPCCode . ' :  Updated Successfully');

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changeInvestmentCommissionStatus($IPCCode)
    {
        try {
            $investmentPlanCommission = $this->investmentPlanCommissionService->changeInvestmentCommissionStatus($IPCCode);
            return redirect()->back()->with('success', 'Status Changed Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}




