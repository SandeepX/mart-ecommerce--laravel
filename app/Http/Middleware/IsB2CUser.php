<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\PermissionDeniedException;
use Closure;

class IsB2CUser
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
        if (!auth()->user()->isB2CUser()) {
            throw new PermissionDeniedException('Permission Denied !', ['user_log_out' => false]);
        }
        if (auth()->user()->isBanned()) {
            throw new PermissionDeniedException('You have been logged out since you are Banned  !', ['user_log_out' => true]);
        }
        if (auth()->user()->isSuspended()) {
            throw new PermissionDeniedException('You have been logged out since you are Suspended  !', ['user_log_out' => true]);
        }
        if (!auth()->user()->isActive()) {
            throw new PermissionDeniedException('You have been logged out since you are deactivated  !', ['user_log_out' => true]);
        }
        return $next($request);
    }
}


