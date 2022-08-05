<?php

/**
 * Created by PhpStorm.
 * User: Sandeep pant
 * Date: 10/01/2021
 * Time: 1:05 PM
 */

namespace App\Modules\Reporting\Providers;

use App\Modules\Reporting\Console\Commands\RejectedItem\StoreOrderItemRejectedCommand;
use App\Modules\Reporting\Console\Commands\RejectedItem\StorePreorderRejectedItemCommand;
use Illuminate\Support\ServiceProvider;

class RejectionStoreItemCommand extends ServiceProvider
{
    /**
     * The Custom Artisan Commands for the  application.
     *
     * @var array
     */
    protected $commands = [
        StoreOrderItemRejectedCommand::class,
        StorePreorderRejectedItemCommand::class
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

    public function register_includes()
    {
        $this->commands($this->commands);
    }
}

