<?php


namespace App\Modules\Wallet\Factories;


use App\Modules\SalesManager\Classes\SalesManagerBalance;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\User\Models\User;
use Exception;

class WalletCurrentBalanceDetailsFactory
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getWalletCurrentBalance()
    {
        switch ($this->user->userType->slug) {
            case 'store':
                return new StoreBalance();
            case 'sales-manager':
                return new SalesManagerBalance();
            default:
                throw new Exception('Cannot get current balance of authenticated user');
        }
    }

}
