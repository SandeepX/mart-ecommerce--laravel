<?php

namespace App\Modules\SMSProcessor\Adapters;

use App\Modules\SMSProcessor\Classes\SparrowSms;
use App\Modules\SMSProcessor\Interfaces\SmsAdapter;

class SparrowSmsAdapter implements SmsAdapter
{

    private  $sparrowSms;

    public function __construct(SparrowSms $sparrowSms){
          $this->sparrowSms = $sparrowSms;
    }

    public function sendSMS(array $to, string $message)
    {
       return  $this->sparrowSms->sendSMS($to,$message);
    }
}
