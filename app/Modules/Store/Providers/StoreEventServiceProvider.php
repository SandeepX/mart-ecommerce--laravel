<?php

namespace App\Modules\Store\Providers;

use App\Modules\Store\Events\LoadBalanceCompletedEvent;
use App\Modules\Store\Events\StoreRegisteredEvent;
use App\Modules\Store\Events\StoreWalletTransactionEvent;
use App\Modules\Store\Listeners\CreateStoreWalletListener;
use App\Modules\Store\Listeners\LoadBalanceCompletedListener;
use App\Modules\Store\Listeners\StoreWalletTransactionListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class StoreEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        StoreRegisteredEvent::class => [
            CreateStoreWalletListener::class,
        ],
        StoreWalletTransactionEvent::class =>[
            StoreWalletTransactionListener::class
        ],
         LoadBalanceCompletedEvent::class =>[
            LoadBalanceCompletedListener::class
        ]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
