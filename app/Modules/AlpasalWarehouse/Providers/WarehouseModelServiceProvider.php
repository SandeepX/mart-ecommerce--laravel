<?php

namespace App\Modules\AlpasalWarehouse\Providers;

use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Observers\WarehouseObserver;
use Illuminate\Support\ServiceProvider;

class WarehouseModelServiceProvider extends ServiceProvider
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
        Warehouse::observe(WarehouseObserver::class);
    }
}