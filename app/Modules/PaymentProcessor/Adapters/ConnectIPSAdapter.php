<?php

namespace App\Modules\PaymentProcessor\Adapters;

use App\Modules\PaymentProcessor\Classes\ConnectIPS;
use App\Modules\PaymentProcessor\Interfaces\PaymentAdapter;

class ConnectIPSAdapter implements PaymentAdapter
{
    private ConnectIPS $connectIPS;

    public function __construct(ConnectIPS $connectIPS){
        $this->connectIPS = $connectIPS;
    }

    public function processPayment(array $data)
    {
        return  $this->connectIPS->processPayment($data);
    }
}
