<?php

namespace App\Modules\Wallet\Factories;

use App\Modules\Store\Classes\StoreOnlineLoadBalance;
use App\Modules\Wallet\Interfaces\OnlineLoadBalanceFactoryInterface;
use App\Modules\Wallet\Interfaces\OnlineLoadBalanceInterface;

class OnlineLoadBalanceFactory implements OnlineLoadBalanceFactoryInterface
{


    public function make($entity): OnlineLoadBalanceInterface
    {
        $createMethod = 'create'.ucfirst($entity).'LoadBalanceService';
        if (!method_exists($this, $createMethod)) {
            throw new \Exception("Online Load Balance $entity is not supported");
        }
        $service = $this->{$createMethod}();
        return $service;
    }


    private function createStoreLoadBalanceService() : StoreOnlineLoadBalance
    {
        $service = new StoreOnlineLoadBalance();
        return $service;
    }
}
