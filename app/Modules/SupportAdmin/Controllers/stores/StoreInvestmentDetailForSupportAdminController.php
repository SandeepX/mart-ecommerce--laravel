<?php


namespace App\Modules\SupportAdmin\Controllers\stores;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\InvestmentPlan\Services\InvestmentCalculationService;
use App\Modules\InvestmentPlan\Services\InvestmentPlanSubscriptionService;
use App\Modules\Store\Services\StoreService;
use App\Modules\SupportAdmin\Helpers\StoreSubscribedInvestmentPlanHelper;
use Exception;
use Illuminate\Http\Request;

class StoreInvestmentDetailForSupportAdminController extends BaseController
{
    public $title = 'Store Detail For Admin Support';
    public $base_route = 'support-admin.';
    public $sub_icon = 'file';
    public $module = 'SupportAdmin::';

    private $view = 'stores.store-investment.';

    private $investmentCalculationService;
    private $ipSubscriptionService;
    private $storeService;

    public function __construct(InvestmentPlanSubscriptionService $ipSubscriptionService,
                                InvestmentCalculationService $investmentCalculationService,
                                StoreService $storeService
    )
    {
            $this->investmentCalculationService = $investmentCalculationService;
            $this->ipSubscriptionService = $ipSubscriptionService;
            $this->storeService=$storeService;
    }

    public function getStoreInvestment($storeCode, Request $request)
    {
        try {
            $store = $this->storeService->findStoreByCode($storeCode);

            $filterParameters = [
                'investment_plan_name' =>$request->get('investment_plan_name'),
                'maturity_date_from' => $request->get('maturity_date_from'),
                'maturity_date_to' => $request->get('maturity_date_to'),
                'interest_rate' => $request->get('interest_rate'),
                'amount_condition' => $request->get('amount_condition'),
                'interest_rate_condition' => $request->get('interest_rate_condition'),
                'invested_amount' => $request->get('invested_amount'),
                'referred_by' => $request->get('referred_by'),
                'is_active'=> $request->get('is_active'),
                'status'=> $request->get('status'),
                'investment_holder_id' => $storeCode,
                'store_name' => $store->store_name
            ];
            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $subscribedIP = StoreSubscribedInvestmentPlanHelper::getAllStoreInvestmentPlanSubscribedByFilter($filterParameters);

            $response  = [];
            $response['html'] = view($this->module . $this->view . 'index',
                compact(
                    'storeCode','filterParameters','amountConditions','subscribedIP'
                )
            )->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }

    public  function showDetail($ISCode)
    {
        try{
            $subscribedIP = $this->ipSubscriptionService->findInvesmentPlanSubcriptionByCode($ISCode);
            $investmentReturn = $this->investmentCalculationService->investmentReturnCalculation($subscribedIP['investment_plan_code'],$subscribedIP['invested_amount']);

            $response  = [];
            $response['html'] = view($this->module . $this->view .'investment-return-modal',
                compact(
                    'subscribedIP','investmentReturn'
                )
            )->render();
            return response()->json($response);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
