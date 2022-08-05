<?php

namespace App\Modules\InvestmentPlan\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Modules\InvestmentPlan\Resources\InvestmentPlanCollection;
use App\Modules\InvestmentPlan\Resources\InvestmentPlanDetailResource;
use App\Modules\InvestmentPlan\Resources\InvestmentPlanDetailResourceForManager;
use App\Modules\InvestmentPlan\Services\InvestmentService;
use Exception;
use Illuminate\Support\Facades\Auth;


class InvestmentPlanController extends Controller
{
    private $investmentService;

    public function __construct(InvestmentService $investmentService)
    {
        $this->investmentService = $investmentService;
    }

    public function getAllActiveInvestmentPlans()
    {
        try {
            $with = 'activeInvestmentInterestDetail';
            $investmentPlans = $this->investmentService->getAllActiveInvestmentPlan($with);
            $getAllInvestmentPlans = new InvestmentPlanCollection($investmentPlans);
            return sendSuccessResponse('Data Found',$getAllInvestmentPlans);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getActiveInvestmentPlanDetail($IPCode)
    {
        try {
            $investmentPlan = $this->investmentService->getActiveInvestmentPlanDetailByCode($IPCode);

            if(Auth::guard('api')->check() && getAuthParentUserType() == 'manager'){
                $investmentPlanDetail = new InvestmentPlanDetailResourceForManager($investmentPlan);
            }else{
                $investmentPlanDetail = new InvestmentPlanDetailResource($investmentPlan);
            }
            return sendSuccessResponse('Data Found',$investmentPlanDetail);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }
}

