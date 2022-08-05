<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\PermissionDeniedException;
use Closure;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IsAdmin
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
        if(!$authUser->isAdminUser()){
            throw new PermissionDeniedException('Permission Denied !');
        }
        if($authUser->isBanned()){
            Session::flash('danger', 'Your account is banned !');
            Auth::guard()->logout();
            return redirect()->route('admin.login');
        }
        if($authUser->isSuspended()){
            Session::flash('danger', 'Your account is suspended !');
            Auth::guard()->logout();
            return redirect()->route('admin.login');
        }
        if(!$authUser->isActive()){
            Session::flash('danger', 'Your account is deactivated !');
            Auth::guard()->logout();
            return redirect()->route('admin.login');
        }

        return $next($request);
    }

}
