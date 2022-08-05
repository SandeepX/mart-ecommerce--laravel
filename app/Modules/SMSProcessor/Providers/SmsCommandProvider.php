<?php
/**
 * Created by PhpStorm.
 * User: shramik
 * Date: 3/26/18
 * Time: 1:05 PM
 */

namespace App\Modules\SMSProcessor\Providers;




use App\Modules\SMSProcessor\Console\Commands\TestSendSms;
use Illuminate\Support\ServiceProvider;

class SmsCommandProvider extends  ServiceProvider
{


    /**
     * The Custom Artisan Commands for the  application.
     *
     * @var array
     */
    protected $commands = [
        TestSendSms::class
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
