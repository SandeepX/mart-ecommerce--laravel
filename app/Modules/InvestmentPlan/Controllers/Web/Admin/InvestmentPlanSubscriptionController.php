<?php


namespace App\Modules\InvestmentPlan\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\InvestmentPlan\Helper\InvestmentPlanSubscriptionHelper;
use App\Modules\InvestmentPlan\Requests\InvestmentSubscriptionPlanRespondRequest;
use App\Modules\InvestmentPlan\Services\InvestmentCalculationService;
use App\Modules\InvestmentPlan\Services\InvestmentPlanSubscriptionService;
use App\Modules\OfflinePayment\Helpers\OfflinePaymentHelper;
use App\Modules\OfflinePayment\Services\OfflinePaymentService;
use App\Modules\PaymentGateway\Helpers\OnlinePaymentHelper;
use App\Modules\Store\Services\StoreBalanceReconciliation\StoreBalanceReconciliationService;
use Exception;
use Illuminate\Http\Request;

class InvestmentPlanSubscriptionController extends BaseController
{
    public $title = 'Investment plan subscription';
    public $base_route = 'admin.investment-subscription';
    public $sub_icon = 'file';
    public $module = 'InvestmentPlan::';
    public $view = 'Investment-plan-subscription.admin.';

    private $ipSubscriptionService;
    private $investmentCalculationService;
    private $balanceReconciliationService;
    private $offlinePaymentService;

    public function __construct(
        InvestmentPlanSubscriptionService $ipSubscriptionService,
        InvestmentCalculationService $investmentCalculationService,
        StoreBalanceReconciliationService $balanceReconciliationService,
        OfflinePaymentService $offlinePaymentService
    ){
        $this->middleware('permission:View Investment Plan Subscription Lists', ['only' => ['index']]);
        $this->middleware('permission:View Investment Plan Subscription Details', ['only' => ['detailSubscription']]);
        $this->middleware('permission:Show Investment Plan Subscription', ['only' => ['show']]);
        $this->middleware('permission:Respond Investment Plan Subscription', ['only' => ['respondIS']]);
        $this->middleware('permission:Change Investment Plan Subscription Status', ['only' => ['toggleStatus']]);

        $this->ipSubscriptionService = $ipSubscriptionService;
        $this->investmentCalculationService = $investmentCalculationService;
        $this->balanceReconciliationService = $balanceReconciliationService;
        $this->offlinePaymentService = $offlinePaymentService;
    }

    public function index(Request $request)
    {
        try{
                $filterParameters = [
                    'investment_plan_name' =>$request->get('investment_plan_name'),
                ];
            $subscribedIP = InvestmentPlanSubscriptionHelper::getAllInvestmentPlanSubcribedGroupBy($filterParameters);

            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('subscribedIP',
                'filterParameters'
                )
            );
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function detailSubscription(Request $request,$IPCode)
    {
        try{
            $filterParameters = [
                'investment_plan_name' =>$request->get('investment_plan_name'),
                'investment_holder_type' => $request->get('investment_holder_type'),
                //'investment_holder_name' => $request->get('investment_holder_name'),
                'maturity_date_from' => $request->get('maturity_date_from'),
                'maturity_date_to' => $request->get('maturity_date_to'),
                'interest_rate' => $request->get('interest_rate'),
                'amount_condition' => $request->get('amount_condition'),
                'interest_rate_condition' => $request->get('interest_rate_condition'),
                'invested_amount' => $request->get('invested_amount'),
                'referred_by' => $request->get('referred_by'),
                'is_active'=> $request->get('is_active'),
                'status'=> $request->get('status'),
                'ip_code' => $IPCode,
                'payment_mode' => $request->get('payment_mode')
            ];
            $amountConditions=[
                'Greater Than >'=>'>',
                'Less Than <'=>'<' ,
                'Greater Than & Equal To >='=>'>=' ,
                'Less Than & Equal To <='=>'<=',
                'Equal To ='=>'=',
            ];
            $subscribedIP = InvestmentPlanSubscriptionHelper::getAllInvestmentPlanSubscribedByFilter($filterParameters);
 //  dd($subscribedIP);
            return view(Parent::loadViewData($this->module . $this->view . 'detailSubscription'),
                compact('subscribedIP',
                    'amountConditions',
                    'filterParameters'
                )
            );

        }catch(Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public  function show($ISCode)
    {
        try{
            $with = ['investmentSubscriptionable','onlinePayment','offlinePayment'];
            $subscribedIP = $this->ipSubscriptionService->findInvesmentPlanSubcriptionByCode($ISCode,$with);
            $subscribedIP->subscription_holder_name = InvestmentPlanSubscriptionHelper::getSubscriptionHolderName($subscribedIP);
            if($subscribedIP->onlinePayment){
                $subscribedIP->onlinePayment->payment_holder_name = OnlinePaymentHelper::getOnlinePaymentHolderName($subscribedIP->onlinePayment);
            }
            if($subscribedIP->offlinePayment){
                $subscribedIP->offlinePayment->payment_holder_name = OfflinePaymentHelper::getOfflinePaymentHolderName($subscribedIP->offlinePayment);
            }
            $investmentReturn = $this->investmentCalculationService->investmentReturnCalculation($subscribedIP['investment_plan_code'],$subscribedIP['invested_amount']);
            return view(Parent::loadViewData($this->module . $this->view . 'show'),
                compact(
                    'subscribedIP','investmentReturn')
            );
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function respondISForm($ISCode)
    {
        try {
            $subscribedIP = $this->ipSubscriptionService->findInvesmentPlanSubcriptionByCode($ISCode);
            $balanceReconciliation = [];
            if($subscribedIP->payment_mode == 'offline'){
                $offlinePayment = $this->offlinePaymentService->findOrFailOfflinePaymentByCodeWithEager($subscribedIP->payment_code);
                $balanceReconciliation = $this->balanceReconciliationService->getBalanceReconciliationForVerificationForLoadbalance($offlinePayment);
                $balanceReconciliation = isset($balanceReconciliation) ? $balanceReconciliation : [];
            }
            return view(Parent::loadViewData($this->module.$this->view.'.partials.respond-form'),compact('subscribedIP','balanceReconciliation'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function respondIS(InvestmentSubscriptionPlanRespondRequest $request,$ISCode)
    {
        try{
            $validatedData = $request->validated();
            $subscriptionData = $this->ipSubscriptionService->respondIS($validatedData,$ISCode);
            return $request->session()->flash('success','Investment plan responded successfully');
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }

    }

    public function toggleStatus($ISCode)
    {
        try{
            $updateStatus = $this->ipSubscriptionService->changeInvestmentSubscriptionStatus($ISCode);
            return redirect()->back()->with('success', $this->title .' status updated successfully');
        }catch (\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}



