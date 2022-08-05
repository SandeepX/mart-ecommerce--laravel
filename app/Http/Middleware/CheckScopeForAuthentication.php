<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Laravel\Passport\Exceptions\MissingScopeException;

class CheckScopeForAuthentication
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
        $routeMiddlewares = request()->route()->action['middleware'];
        $allRouteMiddlewareInString =  implode(',', $routeMiddlewares);
        $scopedMiddleware = substr($allRouteMiddlewareInString,strrpos($allRouteMiddlewareInString,"checkScope"));

        if(strpos($allRouteMiddlewareInString, 'checkScope')){
            if(request()->user()){
                if(!empty(request()->user()->token()->scopes)){
                    $scopes = request()->user()->token()->scopes;
                    $trimedScopedMiddleware = str_replace('checkScope:','',$scopedMiddleware);
                    $middlewareScopes = explode(',',$trimedScopedMiddleware);
                    foreach ($middlewareScopes as $middlewareScopescope) {
                        if(!in_array($middlewareScopescope,$scopes)){
                            throw new Exception('UnAuthorized Access',401);
                            //throw new MissingScopeException($middlewareScopescope);
                        }
                    }
                }
            }
        }
        return $next($request);
    }
}
