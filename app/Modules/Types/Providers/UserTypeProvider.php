<?php

namespace App\Modules\Types\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Types\Models\UserType;
use App\Modules\Types\Observers\UserTypeObserver;

class UserTypeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        UserType::observe(UserTypeObserver::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
