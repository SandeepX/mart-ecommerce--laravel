<?php

namespace App\Http\Middleware;

use App\Modules\Store\Helpers\StoreAccessBarrierHelper;
use App\Exceptions\Custom\StoreAccessBarrierException;
use Closure;

class StoreAccessBarrierMiddleware
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
        try {
            $store = getAuthStore();

            if(empty($store->pan_vat_no)){
                throw new \Exception('Your Billing Information is Incomplete');
            }
            return $next($request);
        }
        catch (\Exception $exception)
        {
            return 'Your Billing Information is Incomplete';
        }

    }
}
