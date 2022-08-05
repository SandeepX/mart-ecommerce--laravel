<?php


namespace App\Modules\Wallet\Classes;


use App\Modules\Wallet\Interfaces\TransactionConfigurationInterface;

class TransactionNotificationConfiguration
{
    private $smsStatus = false;
    private $mailStatus = false;
    private $webNotificationStatus = false;

    public function getSMSSendStatus(){
       return  $this->smsStatus;
    }

    public function setSMSSendStatus($status){
        $this->smsStatus = $status;
    }

    public function setMailSendStatus($status){
       $this->mailStatus = $status;
    }

    public function setWEBNotificationSendStatus($status){
      $this->webNotificationStatus = $status;
    }


}
