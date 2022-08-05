<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $authUser = auth()->user();
            if ($authUser->isWarehouseAdminOrUser()){
                return redirect()->route('warehouse.dashboard');
            }
            if ($authUser->isStoreUser()){
                return redirect()->route('store.dashboard');
            }
            if ($authUser->isVendorUser()){
                return redirect()->route('vendor.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
