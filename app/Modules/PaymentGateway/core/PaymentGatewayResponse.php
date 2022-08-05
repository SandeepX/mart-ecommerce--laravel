<?php


namespace App\Modules\PaymentGateway\core;

use App\Modules\InvestmentPlan\Events\UpdateInvestmentPlanSubscriptionPaymentStatusEvent;
use App\Modules\Store\Event\StoreOnlineLoadBalanceResponseEvent;
use Exception;


class PaymentGatewayResponse
{

    private $onlinePaymentMasterData;
    private $validatedData;

    /**
     * PaymentGatewayResponse constructor.
     * @param $onlinePaymentMasterData
     */
    public function __construct($onlinePaymentMasterData,$validatedData=[])
    {
        $this->onlinePaymentMasterData = $onlinePaymentMasterData;
        $this->validatedData = $validatedData;
    }


    public function throwParentImplementation(): void
    {
        try {
            switch ($this->onlinePaymentMasterData->transaction_type) {
                case 'investment':
                    event(new UpdateInvestmentPlanSubscriptionPaymentStatusEvent($this->onlinePaymentMasterData,$this->validatedData));
                    break;
                case 'load_balance':
                    event(new StoreOnlineLoadBalanceResponseEvent($this->onlinePaymentMasterData));
                    break;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }


}
