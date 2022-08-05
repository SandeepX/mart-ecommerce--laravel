<?php

namespace App\Modules\Wallet\Interfaces;

interface OnlineLoadBalanceFactoryInterface
{
    public function make($entity) : OnlineLoadBalanceInterface;
}
