<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::personalAccessTokensExpireIn(Carbon::now()->addHour(1));// 1 hour to expire
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(3));

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
          return $user->isSuperAdmin() ? true : null;
        });


        Passport::personalAccessClientId(
            config('passport.personal_access_client.id')
        );

        Passport::personalAccessClientSecret(
            config('passport.personal_access_client.secret')
        );

        Passport::tokensCan([
            'view-only' => 'View Only',
            'manage-all' => 'Manage All',
        ]);
    }
}
