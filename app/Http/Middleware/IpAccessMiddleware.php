<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\IpAccessEnabledException;
use App\Modules\SystemSetting\Helpers\GeneralSettingHelper;
use App\Modules\SystemSetting\Helpers\IpAccessSettingHelper;
use App\Modules\SystemSetting\Models\GeneralSetting;
use Closure;

class IpAccessMiddleware
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
        $ipFilteringEnabled= GeneralSettingHelper::isIpFilteringEnabled();

        if ($ipFilteringEnabled){
            $requestIp= request()->ip();
            $allowedIpAddresses = IpAccessSettingHelper::getAllowedIpAddresses();
            if (!in_array($requestIp,$allowedIpAddresses)){
                throw new IpAccessEnabledException();
            }
        }
        return $next($request);
    }
}
