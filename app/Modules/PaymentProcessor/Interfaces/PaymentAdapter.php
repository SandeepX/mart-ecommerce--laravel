<?php
declare(strict_types=1);

namespace App\Modules\PaymentProcessor\Interfaces;

interface PaymentAdapter
{
    public function processPayment(array $data);
}
