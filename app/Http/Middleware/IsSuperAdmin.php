<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\PermissionDeniedException;
use Closure;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IsSuperAdmin
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

        if(!auth()->check() || !$authUser->isSuperAdmin()){
            throw new PermissionDeniedException('Permission Denied !');
        }
        return $next($request);
    }

}
