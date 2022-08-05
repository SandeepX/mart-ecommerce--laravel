<?php

namespace App\Modules\SMSProcessor\Adapters;

use App\Modules\SMSProcessor\Interfaces\SmsAdapter;

class NepalTelecomAdapter implements SmsAdapter
{

    public function sendSMS(array $to, string $message)
    {
        return 1123;
    }
}
