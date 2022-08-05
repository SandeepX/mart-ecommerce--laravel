<?php

namespace App\Modules\PaymentProcessor\Providers;

use App\Modules\PaymentProcessor\Console\Commands\TestPayment;
use Illuminate\Support\ServiceProvider;

class PaymentCommandProvider extends ServiceProvider
{
    /**
     * The Custom Artisan Commands for the  application.
     *
     * @var array
     */
    protected $commands = [
        TestPayment::class
    ];


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->register_includes();

    }

    public function register_includes(){
        $this->commands($this->commands);
    }
}
