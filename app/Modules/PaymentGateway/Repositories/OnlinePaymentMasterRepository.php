<?php


namespace App\Modules\PaymentGateway\Repositories;


use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;
use Exception;

class OnlinePaymentMasterRepository
{
    public function getAllOnlinePaymentLists($paginateBy=20){
      return OnlinePaymentMaster::latest()->paginate($paginateBy);
    }
    public function findByOnlinePaymentCode($onlinePaymentCode,$with= []){
         return OnlinePaymentMaster::with($with)->where('online_payment_master_code',$onlinePaymentCode)->first();
    }
    public function findOrFailByOnlinePaymentCode($onlinePaymentCode,$with=[]){
         $onlinePayment = $this->findByOnlinePaymentCode($onlinePaymentCode,$with);
         if($onlinePayment){
             return $onlinePayment;
         }
         throw new Exception('Online Payment Does\'t not exists :(');
    }

    public function findByTransactionId($transactionId){
        return OnlinePaymentMaster::where('transaction_id',$transactionId)->first();
    }

    public function findByInitiatorCode($initiatorCode,$transactionId){
        return OnlinePaymentMaster::where('initiator_code',$initiatorCode)->where('transaction_id',$transactionId)->first();
    }

    public function saveOnlinePayment($validatedData){

        return OnlinePaymentMaster::create($validatedData);
    }

    public function updateOnlinePayment(OnlinePaymentMaster $onlinePaymentMaster,$validatedData){

        $onlinePaymentMaster->update($validatedData);
        return $onlinePaymentMaster->fresh();
    }

}
