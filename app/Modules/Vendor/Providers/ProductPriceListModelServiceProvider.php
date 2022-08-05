<?php

namespace App\Modules\Vendor\Providers;

use App\Modules\Vendor\Models\ProductPriceList;
use App\Modules\Vendor\Observers\ProductPriceObserver;
use Illuminate\Support\ServiceProvider;

class ProductPriceListModelServiceProvider extends ServiceProvider
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
        ProductPriceList::observe(ProductPriceObserver::class);
    }
}