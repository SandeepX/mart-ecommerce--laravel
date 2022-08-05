<?php

namespace App\Modules\Vendor\Providers;

use App\Modules\Vendor\Models\VendorWareHouse;
use App\Modules\Vendor\Observers\VendorWarehouseObserver;
use Illuminate\Support\ServiceProvider;

class VendorWarehouseModelServiceProvider extends ServiceProvider
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
        VendorWareHouse::observe(VendorWarehouseObserver::class);
    }
}