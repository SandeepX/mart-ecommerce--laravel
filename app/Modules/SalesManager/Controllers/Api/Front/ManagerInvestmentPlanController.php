<?php


namespace App\Modules\SalesManager\Controllers\Api\Front;


use App\Http\Controllers\Controller;
use App\Modules\InvestmentPlan\Resources\InvestmentPlanDetailResourceForManager;
use App\Modules\InvestmentPlan\Services\InvestmentPlanSubscriptionService;
use App\Modules\InvestmentPlan\Services\InvestmentService;
use App\Modules\SalesManager\Services\SalesManagerService;
use Exception;

class ManagerInvestmentPlanController extends Controller
{
    private $investmentService;
    private $subscriptionService;
    private $manager;

    public function __construct(InvestmentService $investmentService,
                                InvestmentPlanSubscriptionService $subscriptionService,
                                SalesManagerService $manager
    )
    {
        $this->investmentService = $investmentService;
        $this->subscriptionService = $subscriptionService;
        $this->manager = $manager;
    }

    public function getActiveInvestmentPlanDetail($IPCode)
    {
        try {
            $investmentPlan = $this->investmentService->getActiveInvestmentPlanDetailByCode($IPCode);
            $investmentPlanDetail = new InvestmentPlanDetailResourceForManager($investmentPlan);
            return sendSuccessResponse('Data Found', $investmentPlanDetail);
        } catch (\Exception $exception) {
            return sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function storeInvestmentPlanSubscription($IPIRCode)
    {
        try {
            $manager = $this->manager->getManagerDetail(getAuthUserCode());

            $investmentPlanHolder = get_class($manager);
            $investmentHolderType = basename($investmentPlanHolder);
            $validatedData['investment_plan_holder'] = $investmentPlanHolder;
            $validatedData['investment_holder_type'] = 'Sales Manager';
            $validatedData['investment_holder_id'] = getAuthUserCode();
            $validatedData['referred_by'] = null;

            $investmentPlanSubscription = $this->subscriptionService->storeSubscription($validatedData, $IPIRCode);

            return sendSuccessResponse('Investment Plan Subscribed Successfully');
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }



    }
}


