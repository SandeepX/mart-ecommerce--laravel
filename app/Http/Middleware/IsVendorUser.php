<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\PermissionDeniedException;
use Closure;

class IsVendorUser
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
        if(!auth()->user()->isVendorUser()){
            throw new PermissionDeniedException('Permission Denied !',['user_log_out'=>false]);
        }
        if(auth()->user()->isBanned()){
            throw new PermissionDeniedException('You account is banned  !',['user_log_out'=>true]);
        }
        if(auth()->user()->isSuspended()){
            throw new PermissionDeniedException('Your account is suspended  !',['user_log_out'=>true]);
        }
        if(!auth()->user()->isActive()){
            throw new PermissionDeniedException('Your account is deactivated  !',['user_log_out'=>true]);
        }
        return $next($request);
    }
}
