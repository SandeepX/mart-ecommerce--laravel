<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\PermissionDeniedException;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IsWarehouseUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authUser = auth()->user();

        if(!$authUser->isWarehouseAdminOrUser()){
            throw new PermissionDeniedException('Permission Denied !');
        }
        if($authUser->isBanned()){
            Session::flash('danger', 'You have been logged out since you are banned !');
            Auth::guard()->logout();
            return redirect()->route('warehouse.login');
        }
        if($authUser->isSuspended()){
            Session::flash('danger', 'You have been logged out since you are suspended !');
            Auth::guard()->logout();
            return redirect()->route('warehouse.login');
        }
        if(!$authUser->isActive()){
            Session::flash('danger', 'You have been logged out since you are Deactivated !');
            Auth::guard()->logout();
            return redirect()->route('warehouse.login');
        }
        return $next($request);
    }
}
