<?php

namespace App\Modules\PaymentMethod\Services;

use App\Modules\OfflinePayment\Repositories\OfflinePaymentRepository;
use App\Modules\OfflinePayment\Resources\OfflinePaymentResource;
use App\Modules\PaymentGateway\Repositories\OnlinePaymentMasterRepository;
use App\Modules\PaymentGateway\Resources\OnlinePaymentResource;
use Exception;

class PaymentMethodService
{
   protected $offlinePaymentRepository;
   protected $onlinePaymentRepository;
    public function __construct(
        OfflinePaymentRepository $offlinePaymentRepository,
        OnlinePaymentMasterRepository $onlinePaymentRepository
    ){
      $this->offlinePaymentRepository = $offlinePaymentRepository;
      $this->onlinePaymentRepository = $onlinePaymentRepository;
    }

    public function getDetailsOfPayment($paymentMethod,$paymentCode,$paymentHolderCode){
        try{
          //  dd($paymentMethod);
            switch($paymentMethod){
               case 'online':
                   $with = ['paymentMetaData'];
                   $payment =  $this->onlinePaymentRepository->findOrFailByOnlinePaymentCode($paymentCode,$with);
                   if($payment->initiator_code != $paymentHolderCode){
                      throw new Exception('This payment does not belongs this user :(');
                   }
                  $payment = new OnlinePaymentResource($payment);
                   break;
               case 'offline':
                    $with = ['paymentMetaData','paymentDocuments'];
                    $payment = $this->offlinePaymentRepository->findOrFailByCode($paymentCode,$with);
                    if($payment->offline_payment_holder_code != $paymentHolderCode){
                        throw new Exception('This payment does not belongs this user :(');
                    }
                    $payment = new OfflinePaymentResource($payment);
                    break;
               default:
                    throw new Exception('Payment Mode not found :(');
            }
            return $payment;
        }catch (\Exception $exception) {
             throw $exception;
        }
    }

}
