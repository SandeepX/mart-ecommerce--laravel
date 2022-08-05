<?php

namespace App\Modules\AdminWarehouse\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;

class NotificationPartialShareProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    private function getUnreadNotifications(){
        if(auth()->check()){

            $unreadNotificationCount = auth()->user()->unreadNotifications()->select('id')->count('id');
            return $unreadNotificationCount;
        }
        return  0;
    }

    private function getLimitedNotifications(){
        if(auth()->check()){
            $latestLimitedNotifications = auth()->user()->notifications()->select('data','created_at')->limit(10)->get();
            return $latestLimitedNotifications;
        }
        return collect();
    }

    public function boot()
    {

        view()->composer('AdminWarehouse::layout.partials.nav-bar', function ($view)
        {
            $view->with('unreadNotifications', $this->getUnreadNotifications());
            $view->with('latestLimitedNotifications', $this->getLimitedNotifications());

        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
