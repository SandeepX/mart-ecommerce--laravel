<?php


namespace App\Modules\SalesManager\Providers;


use App\Modules\SalesManager\Events\ManagerWalletTransactionEvent;
use App\Modules\SalesManager\Listeners\ManagerWalletTransactionListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class SalesManagerEventServiceProvider  extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ManagerWalletTransactionEvent::class =>[
            ManagerWalletTransactionListener::class
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
    }


}
