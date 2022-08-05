<?php


namespace App\Modules\InvestmentPlan\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\InvestmentPlan\Services\InvestmentCalculationService;
use Exception;

class InvestmentPlanCalculator extends Controller
{
    private $calculationService;

    public function __construct(InvestmentCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    public function investmentReturnCalculation($IPCode,$investedAmount)
    {
        try{
            $investmentReturn = $this->calculationService->investmentReturnCalculation($IPCode,$investedAmount);
            return sendSuccessResponse('Data Found',$investmentReturn);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }


}


