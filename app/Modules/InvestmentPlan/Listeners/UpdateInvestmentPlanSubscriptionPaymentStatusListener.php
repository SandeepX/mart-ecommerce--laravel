<?php

namespace App\Modules\InvestmentPlan\Listeners;

use App\Modules\InvestmentPlan\Events\UpdateInvestmentPlanSubscriptionPaymentStatusEvent;
use App\Modules\PaymentGateway\Repositories\OnlinePaymentMasterRepository;
use App\Modules\InvestmentPlan\Repositories\InvestmentSubscriptionRepository;
use App\Modules\SalesManager\Services\SalesManagerService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateInvestmentPlanSubscriptionPaymentStatusListener
{
    private $onlinePaymentRepo;
    private $investmentSubscriptionRepo;
    private $salesManagerService;


    public function __construct(OnlinePaymentMasterRepository $onlinePaymentRepo,
                                InvestmentSubscriptionRepository $investmentSubscriptionRepo,
                                SalesManagerService $salesManagerService
    )
    {
        $this->onlinePaymentRepo = $onlinePaymentRepo;
        $this->investmentSubscriptionRepo = $investmentSubscriptionRepo;
        $this->salesManagerService = $salesManagerService;
    }

    public function handle(UpdateInvestmentPlanSubscriptionPaymentStatusEvent  $event)
    {
        try{
            $onlinePaymentData = $event->onlinePaymentMasterData;
            $validatedData = $event->validatedData;

            $investmentSubscriptionDetail = $this->investmentSubscriptionRepo
                ->findActiveInvestmentPlanSubscription($onlinePaymentData['reference_code']);

                if($onlinePaymentData['status'] == "rejected"){
                    $validatedData['has_paid'] = 0;
                    $validatedData['status'] = 'rejected';
                    $updatePaymentStatus =  $this->investmentSubscriptionRepo->update($validatedData,$investmentSubscriptionDetail);
                }else{
                    $validatedData['has_paid'] = 1;
                    $validatedData['status'] = 'accepted';
                    $updatePaymentStatus = $this->investmentSubscriptionRepo->update($validatedData,$investmentSubscriptionDetail);

                    $referredByUser = $updatePaymentStatus->referredBy;
                    $smsStatus = true;
                    if($referredByUser){
                        $this->salesManagerService->prepareWalletTransactionForSalesManagerInvestmentCommission(
                            $referredByUser,
                            $updatePaymentStatus,
                            $smsStatus
                        );
                    }
                }
        }catch(Exception $exception){
            throw $exception;
        }
    }
}
