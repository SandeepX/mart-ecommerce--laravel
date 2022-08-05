<?php

namespace App\Http;

use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\CheckScopeForAuthentication;
use App\Http\Middleware\CheckUserType;
use App\Http\Middleware\IpAccessMiddleware;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsB2CUser;
use App\Http\Middleware\IsBillable;
use App\Http\Middleware\IsMaintenanceMode;
use App\Http\Middleware\IsPhoneOrEmailVerified;
use App\Http\Middleware\IsSalesManagerUser;
use App\Http\Middleware\IsSessionExists;
use App\Http\Middleware\IsStoreUser;
use App\Http\Middleware\IsSuperAdmin;
use App\Http\Middleware\IsSupportAdmin;
use App\Http\Middleware\IsVendorUser;
use App\Http\Middleware\IsWarehouseUser;
use App\Http\Middleware\StoreAccessBarrierMiddleware;
use App\Http\Middleware\SupportAdminAuthenticate;
use App\Http\Middleware\WarehouseAuthenticate;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'auth:api',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.auth' => AdminAuthenticate::class,
        'warehouse.auth' => WarehouseAuthenticate::class,
        'supportAdmin.auth' => SupportAdminAuthenticate::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'isMaintenanceModeOn' => IsMaintenanceMode::class,
        'isVendorUser' => IsVendorUser::class,
        'isStoreUser' => IsStoreUser::class,
        'isSalesManageUser' => IsSalesManagerUser::class,
        'isB2CUser' => IsB2CUser::class,
        'storeAccessBarrier' =>StoreAccessBarrierMiddleware::class,
        'isAdmin' => IsAdmin::class,
        'isWarehouseUser' => IsWarehouseUser::class,
        'ipAccess' =>IpAccessMiddleware::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
        'IsSessionExists' => IsSessionExists::class,
        'checkScope' => CheckScopeForAuthentication::class,
        'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
        'isSuperAdmin'=>IsSuperAdmin::class,
        'isSupportAdmin' => IsSupportAdmin::class,
        'checkUserType'=>CheckUserType::class,
        'isPhoneOrEmailVerified' => IsPhoneOrEmailVerified::class
    ];
}
