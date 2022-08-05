<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\PermissionDeniedException;
use Closure;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckUserType
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next, ...$userTypes)
    {
        $authUser = auth()->user();
        $authUserType = $authUser->userType->slug;

        if(! in_array($authUserType,$userTypes)){
            throw new PermissionDeniedException('Permission Denied !');
        }
        return $next($request);
    }

}
