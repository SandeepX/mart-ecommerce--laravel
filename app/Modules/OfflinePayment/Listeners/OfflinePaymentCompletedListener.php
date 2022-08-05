<?php


namespace App\Modules\OfflinePayment\Listeners;


use App\Modules\InvestmentPlan\Repositories\InvestmentSubscriptionRepository;
use App\Modules\OfflinePayment\Events\OfflinePaymentEvent;
use App\Modules\OfflinePayment\Services\OfflinePaymentService;
use App\Modules\SalesManager\Services\SalesManagerService;
use App\Modules\SMSProcessor\Jobs\SendSmsJob;
use App\Modules\Store\Events\LoadBalanceCompletedEvent;
use App\Modules\Store\Services\Payment\StorePaymentService;


class OfflinePaymentCompletedListener
{

    private $storePaymentService;
    private $investmentSubscriptionRepo;
    private $salesManagerService;
    private $offlinePaymentService;

    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct(StorePaymentService $storePaymentService,
                                InvestmentSubscriptionRepository $investmentSubscriptionRepo,
                                SalesManagerService $salesManagerService,
                                OfflinePaymentService $offlinePaymentService
    )
    {
        $this->storePaymentService = $storePaymentService;
        $this->investmentSubscriptionRepo = $investmentSubscriptionRepo;
        $this->salesManagerService = $salesManagerService;
        $this->offlinePaymentService = $offlinePaymentService;
    }

    /**
     * Handle the event.
     *
     * @param LoadBalanceCompletedEvent $event
     * @return void
     */
    public function handle(OfflinePaymentEvent $event)
    {
        $offlinePaymentData = $event->offlinePaymentData;
        $investmentSubscriptionDetail = $event->subscriptionData;
        $validatedData = $event->validatedData;

        if($investmentSubscriptionDetail && $offlinePaymentData->isVerified()){
            $validatedData['has_paid'] = 1;
            $validatedData['status'] = 'accepted';
            $this->investmentSubscriptionRepo->update($validatedData,$investmentSubscriptionDetail);
            $this->offlinePaymentService->handleBrCodeAfterBalanceLoad($validatedData['balance_reconciliation_code'], $offlinePaymentData);

            if($investmentSubscriptionDetail->referredBy){
                $this->salesManagerService->prepareWalletTransactionForSalesManagerInvestmentCommission(
                    $investmentSubscriptionDetail->referredBy,
                    $investmentSubscriptionDetail,
                    true
                );
            }

            $data['purpose'] = 'investment';
            $data['purpose_code'] = $offlinePaymentData->reference_code;
            $message = "Your offline Payment of Amount '.$offlinePaymentData->amount. ' for Investment Subcription is successfully verified";
            $message .= " @ https://allpasal.com/ ";
            if (isset($validatedData['sms']) && $validatedData['sms']) {
                SendSmsJob::dispatch(
                    $offlinePaymentData->contact_phone_no,
                    $message,
                    $data
                );
            }
        }else{
            $validatedData['has_paid'] = 0;
            $validatedData['status'] = 'rejected';
            $this->investmentSubscriptionRepo->update($validatedData,$investmentSubscriptionDetail);
        }
    }


}
