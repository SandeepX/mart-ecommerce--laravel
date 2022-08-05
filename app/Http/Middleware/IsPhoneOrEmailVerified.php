<?php

namespace App\Http\Middleware;

use Closure;

class IsPhoneOrEmailVerified
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
        if(!$authUser->email_verified_at && !$authUser->phone_verified_at){
           throw new \Exception('Phone or Email should Be verified!',401);
        }
        return $next($request);
    }
}
