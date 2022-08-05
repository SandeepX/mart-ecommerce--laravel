<?php


namespace App\Modules\PaymentProcessor\Services;

use App\Modules\PaymentProcessor\Adapters\ConnectIPSAdapter;
use Exception;

class PaymentGatewayService
{

    private $paymentGateway ;
    private $connectIPSAdapter ;


    public function __construct(
        ConnectIPSAdapter $connectIPSAdapter
    ){
        $this->paymentGateway = config('sms.default');
        $this->connectIPSAdapter = $connectIPSAdapter;
    }

    public function setDefaultPaymentGateway($paymentGateway){
        if(!in_array($paymentGateway,[
            'connect_ips',
            'nt'
        ])){
            throw new \Exception('No Such Payment Gateway for your service');
        }
        $this->paymentGateway = $paymentGateway;
    }


   public function processPayment(array $data)
   {
        try{

            $defaultPaymentGateway = $this->paymentGateway;

            if (!$defaultPaymentGateway){
                throw new \Exception('No such payment gateway found for the service right now!');
            }


            switch ($defaultPaymentGateway){
                case 'connect_ips':
                    return $this->connectIPSAdapter->processPayment($data);


            }
        }catch(Exception  $e){
            throw $e;
        }
   }





}
