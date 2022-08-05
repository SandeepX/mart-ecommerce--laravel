<?php

namespace App\Modules\Wallet\Interfaces;

interface OfflineLoadBalanceFactoryInterface
{
    public function make($entity) : OfflineLoadBalanceInterface;
}
