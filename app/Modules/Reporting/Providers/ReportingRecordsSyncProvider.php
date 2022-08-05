<?php

namespace App\Modules\Reporting\Providers;

use App\Modules\Reporting\Console\Commands\Dispatch\NormalOrderDispatchSyncCommand;
use App\Modules\Reporting\Console\Commands\Dispatch\PreOrderDispatchSyncCommand;
use Illuminate\Support\ServiceProvider;

class ReportingRecordsSyncProvider extends ServiceProvider
{

    /**
     * The Custom Artisan Commands for the  application.
     *
     * @var array
     */
    protected $commands = [
        NormalOrderDispatchSyncCommand::class,
        PreOrderDispatchSyncCommand::class
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
