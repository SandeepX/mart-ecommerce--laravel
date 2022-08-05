<?php


namespace App\Modules\InvestmentPlan\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\InvestmentPlan\Requests\InvestmentInterestReleaseOptionRequest;
use App\Modules\InvestmentPlan\Services\InvestmentPlanInterestReleaseService;
use Illuminate\Http\Request;
use Exception;


class InvestmentPlanInterestReleaseController extends BaseController
{
    public $title = 'Investment plan';
    public $base_route = 'admin.investment';
    public $sub_icon = 'file';
    public $module = 'InvestmentPlan::';
    public $view = 'investment-interest-release.';

    private $investmentInterestReleaseService;

    public function __construct(InvestmentPlanInterestReleaseService $investmentInterestReleaseService)
    {
        $this->middleware('permission:View Investment Plan Interest Release Option Lists', ['only' => ['show']]);
        $this->middleware('permission:Create Investment Plan Interest Release Option', ['only' => ['create', 'store']]);
        //$this->middleware('permission:Show Investment Plan Interest Release Option List', ['only' => ['show']]);
        $this->middleware('permission:Update Investment Plan Interest Release Option', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Change Investment Plan Interest Release Option Status', ['only' => ['changeStatus']]);

        $this->investmentInterestReleaseService = $investmentInterestReleaseService;
    }

    public function show($IPCode)
    {
        try{
            $investmentInterestReleaseDetail = $this->investmentInterestReleaseService->getAllInvestmentInterestReleaseByIPCode($IPCode);
            return view(Parent::loadViewData($this->module . $this->view . 'show'), compact('investmentInterestReleaseDetail','IPCode'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function create($IPCode)
    {
        return view(Parent::loadViewData($this->module . $this->view . 'create'), compact('IPCode'));
    }

    public function store(InvestmentInterestReleaseOptionRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $investmentInterestRelease = $this->investmentInterestReleaseService->storeInvestmentPlanInterestRelease($validatedData);
            return redirect()->back()->with('success','Investment plan Interest Release Option Created Successfully');

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function edit($IPIRCode)
    {
        try{
            $investmentInterestReleaseDetail = $this->investmentInterestReleaseService->findOrFailInvestmentInterestReleaseByCode($IPIRCode);
            return view(Parent::loadViewData($this->module . $this->view . 'edit'),compact('investmentInterestReleaseDetail'));

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function update(InvestmentInterestReleaseOptionRequest $request, $IPIRCode)
    {
        try{
            $validatedData = $request->validated();
            $investmentInterestReleaseOption = $this->investmentInterestReleaseService->updateInvestmenInterestReleaseOption($validatedData,$IPIRCode);
            return redirect()->back()->with('success','Investment Interest Release option  '. $IPIRCode. ' :  updated Successfully');

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function changeStatus($IPIRCode)
    {
        try{
            $investmentPlan = $this->investmentInterestReleaseService->changeInvestmentInterestReleaseStatus($IPIRCode);
            return redirect()->back()->with('success','Status Changed Successfully');
        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}



