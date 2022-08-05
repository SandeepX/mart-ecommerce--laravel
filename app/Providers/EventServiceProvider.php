<?php

namespace App\Providers;


use App\Modules\OfflinePayment\Events\OfflinePaymentEvent;
use App\Modules\OfflinePayment\Listeners\OfflinePaymentCompletedListener;
use App\Modules\SalesManager\Events\ManagerStatusApprovedEvent;
use App\Modules\Vendor\Events\VendorRegisteredEvent;
use App\Modules\SalesManager\Listeners\CreateManagerWalletListener;
use App\Modules\Vendor\Listeners\CreateVendorWalletListener;

use App\Modules\InvestmentPlan\Events\UpdateInvestmentPlanSubscriptionPaymentStatusEvent;
use App\Modules\InvestmentPlan\Listeners\UpdateInvestmentPlanSubscriptionPaymentStatusListener;
use App\Modules\Store\Event\StoreOnlineLoadBalanceResponseEvent;
use App\Modules\Store\Listeners\StoreOnlineLoadBalanceResponseListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        VendorRegisteredEvent::class => [
            CreateVendorWalletListener::class,
        ],
        ManagerStatusApprovedEvent::class => [
            CreateManagerWalletListener::class,
        ],
        UpdateInvestmentPlanSubscriptionPaymentStatusEvent::class =>[
            UpdateInvestmentPlanSubscriptionPaymentStatusListener::class,
        ],
        StoreOnlineLoadBalanceResponseEvent::class =>[
            StoreOnlineLoadBalanceResponseListener::class,
        ],
        OfflinePaymentEvent::class =>[
            OfflinePaymentCompletedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
