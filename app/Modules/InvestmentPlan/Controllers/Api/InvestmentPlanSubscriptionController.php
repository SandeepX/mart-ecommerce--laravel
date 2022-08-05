<?php


namespace App\Modules\InvestmentPlan\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\InvestmentPlan\Events\UpdateInvestmentPlanSubscriptionPaymentStatusEvent;
use App\Modules\InvestmentPlan\Requests\InvestmentPlanSubscribePayingOfflineRequest;
use App\Modules\InvestmentPlan\Requests\InvestmentSubscriptionRequest;
use App\Modules\InvestmentPlan\Resources\InvestmentPlanSubscription\InvestmentPlanSubscriptionCollection;
use App\Modules\InvestmentPlan\Services\InvestmentPlanSubscriptionService;
use App\Modules\PaymentGateway\Services\ConnectIpsService;
use Exception;
use Illuminate\Http\Request;

class InvestmentPlanSubscriptionController extends Controller
{
    private $subscriptionService;
    private $connectIpsService;

    public function __construct(InvestmentPlanSubscriptionService $subscriptionService,
                                ConnectIpsService $connectIpsService
    )
    {
        $this->subscriptionService = $subscriptionService;
        $this->connectIpsService = $connectIpsService;
    }

    public function createSubscription(InvestmentSubscriptionRequest $request)
    {
        try{
            $data = $request->validated();
            $onlinePayForInvestment = $this->subscriptionService->storeOnlineSubscription($data);
            $ipsApiRequestData = json_decode($onlinePayForInvestment->request);
            return sendSuccessResponse('Data found',$ipsApiRequestData);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function createSubscriptionByPayingOffline(InvestmentPlanSubscribePayingOfflineRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $validatedData['amount'] = roundPrice($validatedData['amount']);
            $investmentPlanSubscribeOffline = $this->subscriptionService->storeOfflineSubscription($validatedData);
            if($investmentPlanSubscribeOffline->has_matched == 1){
                $message = 'Congratulation your data is matched , It will take upto 3 office  hours to verify your transaction';
            }else{
                $message = 'Thanks for the payment .It may take upto 12 working hours for the verification.';
            }
            return sendSuccessResponse($message);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getAllSubscribedInvestmentPlanByUser()
    {
        try{
            $investmentPlanSubscriptionDetail = $this->subscriptionService->getAllSubscribedInvestmentPlanByUser(getAuthManagerCode());
            $subscribedInvestmentPlan = new InvestmentPlanSubscriptionCollection($investmentPlanSubscriptionDetail);
            return sendSuccessResponse('Data found',$subscribedInvestmentPlan);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function getAllReferredInvestmentSubscribed()
    {
        try{
            $investmentSubscribedReferredByManager = $this->subscriptionService->getALlSubscribedInvestmentReferredByManager(getAuthManagerCode());
            $subscribedReferredInvestmentPlan = new InvestmentPlanSubscriptionCollection($investmentSubscribedReferredByManager);
            return sendSuccessResponse('Data found',$subscribedReferredInvestmentPlan);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

}


