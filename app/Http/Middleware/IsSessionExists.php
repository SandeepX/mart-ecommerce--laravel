<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class IsSessionExists
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

        if(!($request->session()->has('pricing_view_session')))
        {
            Session::flash('danger', 'Sorry Session is Expired !');
            return redirect()->route('product-pricing.form',$request->route('linkCode'));
        }
        return $next($request);
    }

}
