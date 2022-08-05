<?php

namespace App\Modules\Product\Providers;

use App\Modules\Product\Models\ProductPriceList;
use App\Modules\Product\Observers\ProductPriceObserver;
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