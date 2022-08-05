<?php

namespace App\Modules\Product\Providers;

use App\Modules\Product\Console\Commands\MostPopularProductsSyncCommand;
use Illuminate\Support\ServiceProvider;

class MostPopularProductSyncProvider extends ServiceProvider
{

    /**
     * The Custom Artisan Commands for the  application.
     *
     * @var array
     */
    protected $commands = [
        MostPopularProductsSyncCommand::class,
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
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
