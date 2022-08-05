<?php

namespace App\Modules\Wallet\Factories;

use App\Modules\Store\Classes\StoreOfflineLoadBalance;
use App\Modules\Wallet\Interfaces\OfflineLoadBalanceFactoryInterface;
use App\Modules\Wallet\Interfaces\OfflineLoadBalanceInterface;

class OfflineLoadBalanceFactory implements OfflineLoadBalanceFactoryInterface
{


    public function make($entity): OfflineLoadBalanceInterface
    {
        $createMethod = 'create'.ucfirst($entity).'LoadBalanceService';
        if (!method_exists($this, $createMethod)) {
            throw new \Exception("Offline Load Balance $entity is not supported");
        }
        $service = $this->{$createMethod}();
        return $service;
    }


    private function createStoreLoadBalanceService() : StoreOfflineLoadBalance
    {
        $service = new StoreOfflineLoadBalance();
        return $service;
    }
}
