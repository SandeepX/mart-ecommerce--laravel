<?php


namespace App\Modules\PaymentProcessor\Interfaces;


interface PaymentClient
{
    public function getPaymentClientConfiguration() : array ;

    public function processPayment(array $data);


}
