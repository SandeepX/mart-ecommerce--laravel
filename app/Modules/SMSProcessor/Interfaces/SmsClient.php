<?php


namespace App\Modules\SMSProcessor\Interfaces;


interface SmsClient
{
    public function getSparrowSmsConfiguration() : array ;

    public function sendSMS(array $to,string $message);

}
