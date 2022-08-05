<?php
declare(strict_types=1);

namespace App\Modules\SMSProcessor\Interfaces;

interface SmsAdapter
{
    public function sendSMS(array $to, string $message);
}
