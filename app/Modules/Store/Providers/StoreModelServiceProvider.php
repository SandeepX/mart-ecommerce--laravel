<?php

namespace App\Modules\Store\Providers;

use App\Modules\Store\Models\Store;
use App\Modules\Store\Observers\StoreObserver;
use Illuminate\Support\ServiceProvider;

class StoreModelServiceProvider extends ServiceProvider
{
     /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Store::observe(StoreObserver::class);
    }
}